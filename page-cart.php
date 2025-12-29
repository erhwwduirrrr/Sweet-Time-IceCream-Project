<?php /* Template Name: Custom Cart Page */ ?>
<?php
// --- 购物车逻辑 (PHP) ---
// 1. 处理添加商品 (在本页刷新时处理)
if (isset($_GET['add'])) {
    $pid = intval($_GET['add']);
    $json_file = WP_CONTENT_DIR . '/uploads/products.json';
    $products = json_decode(file_get_contents($json_file), true);
    
    $found_product = null;
    foreach ($products as $p) { if ($p['id'] == $pid) { $found_product = $p; break; } }
    
    if ($found_product) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $pid) { $item['quantity']++; $exists = true; break; }
        }
        if (!$exists) {
            $img = $found_product['image'];
            if (strpos($img, 'http') === false) $img = get_stylesheet_directory_uri() . '/' . $img;
            
            $_SESSION['cart'][] = [
                'id' => $pid, 'name' => $found_product['name'], 
                'price' => $found_product['price'], 'image' => $img, 'quantity' => 1
            ];
        }
    }
    // ✅ 修正 ID：87
    echo "<script>window.location.href='".home_url('/?page_id=87')."';</script>"; 
    exit;
}

// 2. 处理移除商品
if (isset($_GET['remove'])) {
    $pid = intval($_GET['remove']);
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($i) use ($pid) { return $i['id'] != $pid; });
    // ✅ 修正 ID：87
    echo "<script>window.location.href='".home_url('/?page_id=87')."';</script>"; 
    exit;
}
?>

<?php get_header(); ?>
<?php get_template_part('header-part'); ?>

<div class="container">
    <h1>Your Shopping Cart 🛒</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Cart is empty. <a href="<?php echo home_url('/?page_id=39'); ?>">Go Shopping</a></p>
    <?php else: ?>
        <table class="customer-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $total = 0; 
            foreach ($_SESSION['cart'] as $item): 
                $sub = $item['price'] * $item['quantity']; 
                $total += $sub; 
            ?>
                <tr>
                    <td>
                        <img src="<?php echo $item['image']; ?>" style="width:50px;vertical-align:middle;margin-right:10px;">
                        <?php echo $item['name']; ?>
                    </td>
                    <td>¥<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>¥<?php echo number_format($sub, 2); ?></td>
                    <td>
                        <a href="?remove=<?php echo $item['id']; ?>" style="color:red;">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3 style="text-align:right; margin-top:20px;">Total: ¥<?php echo number_format($total, 2); ?></h3>
        
        <div style="text-align:right;">
            <a href="<?php echo home_url('/?page_id=79'); ?>" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php get_template_part('footer-part'); ?>