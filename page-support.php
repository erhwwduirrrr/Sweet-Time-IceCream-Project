<?php 
/* Template Name: Custom Support Page */ 
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 简单的反垃圾验证
    if (!empty($_POST['contact_name']) && !empty($_POST['message'])) {
        global $wpdb;
        $name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['contact_email']);
        $subject = sanitize_text_field($_POST['subject']); // 新增字段
        $message = sanitize_textarea_field($_POST['message']);

        // 1. 存入数据库 (使用正确的表名和字段)
        // 表名必须是 wp_support_tickets (或前缀_support_tickets)
        $table = $wpdb->prefix . 'support_tickets'; 
        
        $result = $wpdb->insert($table, array(
            'customer_name' => $name, 
            'customer_email' => $email, 
            'subject' => $subject,
            'message' => $message,
            'status' => 'open',
            'submitted_at' => current_time('mysql')
        ));

        if ($result) {
             // 2. 发送邮件
            $to = $email; // 发给客户确认信
            $admin_email = get_option('admin_email');
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            // 发给客户
            wp_mail($to, "We received your request: $subject", "Hi $name,<br>Thank you for contacting us. We will respond within 48 hours.", $headers);
            
            // 发给管理员 (可选)
            // wp_mail($admin_email, "New Support Ticket", "From: $name ($email)\nMsg: $message");

            $msg = "Thank you! Your support ticket has been created. We sent a confirmation email.";
        } else {
            $msg = "Error: Could not save ticket. Please try again.";
        }
    }
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Customer Support 🎧</h1>
    <?php if($msg) echo "<div style='background:#d1ecf1; color:#0c5460; padding:15px; margin-bottom:20px; border-radius:5px;'>$msg</div>"; ?>
    
    <div style="background:white; padding:2rem; border-radius:10px;">
        <form method="POST">
            <div class="form-group">
                <label>Your Name *</label>
                <input type="text" name="contact_name" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="contact_email" required>
            </div>
            <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="subject" required placeholder="e.g., Order Inquiry, Product Issue">
            </div>
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn">Send Feedback</button>
        </form>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>