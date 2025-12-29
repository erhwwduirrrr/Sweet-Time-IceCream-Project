<?php
// 1. 加载父子主题样式
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    // 确保父主题名字 twentytwentyone 是正确的文件夹名
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
}

// 2. 开启 Session (购物车必须)
if (!function_exists('register_my_session')) {
    add_action( 'init', 'register_my_session' );
    function register_my_session() {
        if ( ! session_id() ) {
            session_start();
        }
    }
}

// 3. 【新增】注册自定义 API 端点 (作业要求 2.2.4)
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'api_get_products',
    ));
});

// API 回调函数：返回 JSON 格式的产品列表
function api_get_products() {
    $json_file = WP_CONTENT_DIR . '/uploads/products.json';
    if (file_exists($json_file)) {
        $products = json_decode(file_get_contents($json_file), true);
        if ($products) {
            return new WP_REST_Response($products, 200);
        }
    }
    // 如果没有文件或为空，返回空数组
    return new WP_REST_Response(array('message' => 'No products found'), 404);
}
?>