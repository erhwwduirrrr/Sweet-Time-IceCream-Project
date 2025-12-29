<?php 
/* Template Name: Custom Products Page */ 

 
// 1. 强行开启 Session
if (!session_id()) { session_start(); }

if (isset($_GET['add'])) {
    // ====================================================
    // 🛑 这里的 ID 必须和你查到的一模一样！
    // ====================================================
    $real_login_id = 77; // ✅ Login ID (已确认)
    $real_cart_id  = 87; // ✅ Cart ID (已修正：从 46 改为 87)
    // ====================================================

    // A. 检查登录
    if (!isset($_SESSION['customer_id'])) {
        wp_redirect(home_url('/?page_id=' . $real_login_id)); 
        exit;
    }

    // B. 处理加购
    $pid = intval($_GET['add']);
    $json_file = WP_CONTENT_DIR . '/uploads/products.json';
    
    if (file_exists($json_file)) {
        $products = json_decode(file_get_contents($json_file), true);
        $found_product = null;
        
        foreach ($products as $p) { 
            if ($p['id'] == $pid) { 
                $found_product = $p; 
                break; 
            } 
        }
        
        if ($found_product) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            
            $exists = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $pid) { 
                    $item['quantity']++; 
                    $exists = true; 
                    break; 
                }
            }
            
            if (!$exists) {
                $img = $found_product['image'];
                if (strpos($img, 'http') === false) {
                    $img = get_stylesheet_directory_uri() . '/' . $img;
                }
                
                $_SESSION['cart'][] = [
                    'id' => $pid, 
                    'name' => $found_product['name'], 
                    'price' => $found_product['price'], 
                    'image' => $img, 
                    'quantity' => 1
                ];
            }
        }
    }
    
    // C. 跳转购物车 (这里现在去 87 了！)
    wp_redirect(home_url('/?page_id=' . $real_cart_id));
    exit;
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Our Ice Cream Menu 🍦</h1>
    
    <?php if(isset($_SESSION['customer_name'])): ?>
        <div style="background:#e6fffa; padding:15px; border-radius:5px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
            <span style="color:green; font-weight:bold;">
                Welcome back, <?php echo esc_html($_SESSION['customer_name']); ?>! Happy Shopping. 🛍️
            </span>
            
            <a href="<?php echo home_url('/?page_id=90'); ?>" class="btn" style="background:#666; font-size:0.8rem; padding:5px 15px;">
                Log Out 
            </a>
        </div>
    <?php endif; ?>

    <div class="product-grid">
        <?php
        $json_file = WP_CONTENT_DIR . '/uploads/products.json';
        if (file_exists($json_file)) {
            $products = json_decode(file_get_contents($json_file), true);
            if ($products) {
                foreach ($products as $product) {
                    $img_url = $product['image'];
                    if (strpos($img_url, 'http') === false) {
                        $img_url = get_stylesheet_directory_uri() . '/' . $img_url;
                    }
                    echo '<div class="product-card">';
                    echo '<img src="' . esc_url($img_url) . '">';
                    echo '<h3>' . esc_html($product['name']) . '</h3>';
                    echo '<p>' . esc_html($product['description']) . '</p>';
                    echo '<p class="product-price">¥' . number_format($product['price'], 2) . '</p>';
                    echo '<a href="?page_id=39&add=' . $product['id'] . '" class="btn">Add to Cart</a>';
                    echo '</div>';
                }
            }
        }
        ?>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>