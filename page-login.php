<?php 
/* Template Name: Custom Login Page */ 

// 1. 如果已登录，直接跳去产品页
if (isset($_SESSION['customer_id'])) {
    // 请将下面的 62 替换为你产品页面的真实 ID
    wp_redirect(home_url('/?page_id=39')); 
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $wpdb;
    
    $email = sanitize_email($_POST['email']);
    $raw_phone = sanitize_text_field($_POST['phone']);
    
    // 查询数据库
    $table = $wpdb->prefix . 'fc_subscribers';
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE email = %s", $email));

    if ($user) {
        // --- 修复点开始：处理数据库中电话为空的情况 ---
        $db_phone = isset($user->phone) ? (string)$user->phone : '';
        $input_phone = (string)$raw_phone;

        // 清理符号，只比对数字
        $clean_db_phone = str_replace(['-',' ','+'], '', $db_phone);
        $clean_input_phone = str_replace(['-',' ','+'], '', $input_phone);

        // 如果数据库里没电话，或者电话匹配
        if ( (!empty($clean_db_phone) && strpos($clean_db_phone, $clean_input_phone) !== false) || $clean_db_phone == $clean_input_phone ) {
            
            $_SESSION['customer_id'] = $user->id;
            $_SESSION['customer_email'] = $user->email;
            $_SESSION['customer_name'] = $user->first_name;
            
            // 登录成功跳转
            wp_redirect(home_url('/?page_id=39')); 
            exit;
            
        } else {
            // 调试信息：告诉你数据库里到底存了啥，方便你排查
            $error = "手机号不匹配。<br>数据库存的是: [" . $db_phone . "]<br>你输入的是: [" . $input_phone . "]";
            
            // 如果数据库里真的是空的，临时允许登录（为了你能继续做作业）
            if (empty($db_phone)) {
                 $_SESSION['customer_id'] = $user->id;
                 $_SESSION['customer_email'] = $user->email;
                 $_SESSION['customer_name'] = $user->first_name;
                 wp_redirect(home_url('/?page_id=39')); 
                 exit;
            }
        }
        // --- 修复点结束 ---
    } else {
        $error = "未找到该邮箱用户。请检查邮箱是否正确。";
    }
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Customer Login 🔐</h1>
    
    <?php if($error): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-bottom:20px; text-align:center;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div style="background:white; padding:2rem; border-radius:10px; max-width:400px; margin:0 auto;">
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Registered Email">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required placeholder="Phone Number">
            </div>
            <button type="submit" class="btn" style="width:100%;">Login</button>
        </form>
        <p style="text-align:center; margin-top:1rem;">
             <a href="<?php echo home_url('/?page_id=62'); ?>">Register Now</a>
        </p>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>