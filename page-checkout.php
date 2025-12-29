<?php 
/* Template Name: Custom Checkout Page */ 

if (!isset($_SESSION['customer_id']) || empty($_SESSION['cart'])) {
    wp_redirect(home_url('/?page_id=39'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $wpdb;
    
    // 计算总价
    $total = 0;
    foreach ($_SESSION['cart'] as $item) { $total += $item['price'] * $item['quantity']; }
    
    // 准备产品ID字符串
    $pids = array_map(function($i){ return $i['id'] . '(' . $i['quantity'] . ')'; }, $_SESSION['cart']);
    $pid_str = implode(', ', $pids);

    // 1. 存入数据库 (使用预处理)
    $table = $wpdb->prefix . 'orders';
    $wpdb->insert(
        $table,
        array(
            'customer_email' => $_SESSION['customer_email'],
            'product_ids' => $pid_str,
            'total_amount' => $total,
            'status' => 'Processing'
        ),
        array('%s', '%s', '%f', '%s')
    );

    // 2. 清空购物车
    unset($_SESSION['cart']);

    // 3. 跳转到支付模拟页 (作业要求)
    wp_redirect(home_url('/?page_id=81')); 
    exit;
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Confirm Your Order 📝</h1>
    <div style="background:white; padding:2rem; border-radius:10px;">
        <h3>Order Summary</h3>
        <ul>
            <?php 
            $grand_total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $sub = $item['price'] * $item['quantity'];
                $grand_total += $sub;
                echo "<li>" . esc_html($item['name']) . " x {$item['quantity']} = ¥" . number_format($sub, 2) . "</li>";
            }
            ?>
        </ul>
        <h2 style="color:#ff6b8b;">Total: ¥<?php echo number_format($grand_total, 2); ?></h2>
        
        <form method="POST">
            <p>Shipping to: <?php echo esc_html($_SESSION['customer_email']); ?></p>
            <button type="submit" class="btn" style="width:200px;">Confirm & Pay</button>
        </form>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>