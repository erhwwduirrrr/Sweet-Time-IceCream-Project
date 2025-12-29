<?php 
/* Template Name: Recruitment Page */ 
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['cv'])) {
    global $wpdb;
    
    // 1. 处理文件上传
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    $uploadedfile = $_FILES['cv'];
    
    // 安全：重命名文件 (例如 john_doe_cv_17123456.pdf)
    $timestamp = time();
    $uploadedfile['name'] = sanitize_file_name(pathinfo($uploadedfile['name'], PATHINFO_FILENAME) . "_$timestamp." . pathinfo($uploadedfile['name'], PATHINFO_EXTENSION));

    $upload_overrides = array('test_form' => false);
    
    // 只允许 PDF 和 Doc (WordPress 默认允许，这里无需额外代码，依赖 WP 配置)
    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        // 2. 存入数据库 (作业要求 2.5)
        $table = $wpdb->prefix . 'job_applications'; // 对应我们刚建的表
        
        $result = $wpdb->insert(
            $table,
            array(
                'full_name'    => sanitize_text_field($_POST['app_name']),
                'email'        => sanitize_email($_POST['app_email']),
                'phone'        => sanitize_text_field($_POST['app_phone']),
                'position'     => sanitize_text_field($_POST['app_position']),
                'cover_letter' => sanitize_textarea_field($_POST['app_letter']),
                'cv_file_path' => $movefile['url'], // 存储文件 URL
                'status'       => 'received'
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );

        if ($result) {
            $msg = "Success! Your application has been received.";
        } else {
            $msg = "Database Error: Could not save application.";
        }
    } else {
        $msg = "Upload Error: " . $movefile['error'];
    }
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Join Our Team 🤝</h1>
    <?php if($msg) echo "<div style='background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px;'>$msg</div>"; ?>
    
    <div style="background:white; padding:2rem; border-radius:10px;">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="app_name" required placeholder="John Doe">
            </div>
            
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="app_email" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="app_phone" required placeholder="0412 345 678">
            </div>

            <div class="form-group">
                <label>Position Applying For *</label>
                <select name="app_position" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:5px;">
                    <option value="">-- Select a Position --</option>
                    <option value="Store Manager">Store Manager</option>
                    <option value="Ice Cream Scooper">Ice Cream Scooper</option>
                    <option value="Delivery Driver">Delivery Driver</option>
                    <option value="Pastry Chef">Pastry Chef</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cover Letter</label>
                <textarea name="app_letter" rows="5" placeholder="Tell us why you want to join..."></textarea>
            </div>

            <div class="form-group">
                <label>Upload CV (PDF/Docx) *</label>
                <input type="file" name="cv" required accept=".pdf,.doc,.docx">
                <p style="font-size:0.8rem; color:#666;">Max file size: 2MB.</p>
            </div>
            
            <button type="submit" class="btn">Submit Application</button>
        </form>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>