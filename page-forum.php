<?php 
/* Template Name: Custom Forum Page */ 

// 1. 开启 Session
if (!session_id()) { session_start(); }

global $wpdb;
$table_forum = $wpdb->prefix . 'custom_forum'; 
$login_page_id = 77; // 🛑 请确保这是你 Login 页面的真实 ID

// 2. 权限验证：必须登录
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Please login first!'); window.location.href='".home_url('/?page_id=' . $login_page_id)."';</script>";
    exit;
}

// 3. 自动建表逻辑 (如果表不存在，自动创建)
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table_forum (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_name varchar(100) NOT NULL,
    user_email varchar(100) NOT NULL,
    content text NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id)
) $charset_collate;";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

// 4. 处理：一键生成 20 条演示数据 (作业神器)
if (isset($_POST['generate_dummy'])) {
    $dummy_contents = [
        "Great ice cream! Loved the vanilla flavor.",
        "The delivery was super fast, thanks!",
        "Can you add matcha flavor next time?",
        "My kids really enjoyed the chocolate cone.",
        "Pricing is very reasonable.",
        "Best service in town!",
        "The strawberry sorbet is a must-try.",
        "Highly recommended for parties.",
        "Sweet Time is truly sweet!",
        "Looking forward to the new summer menu.",
        "Customer support was very helpful.",
        "Fresh ingredients make a huge difference.",
        "Just placed my second order.",
        "The packaging is so cute!",
        "Will definitely come back again.",
        "Five stars for the mango pudding.",
        "Quick question: are these gluten-free?",
        "Perfect treat for a hot day.",
        "Love the new website design.",
        "Keep up the good work!"
    ];
    
    // 循环插入20条
    foreach ($dummy_contents as $i => $text) {
        $wpdb->insert($table_forum, array(
            'user_name' => 'DemoUser_' . ($i + 1),
            'user_email' => 'demo' . ($i + 1) . '@example.com',
            'content' => $text,
            'created_at' => date('Y-m-d H:i:s', strtotime("-$i hours")) // 错开时间
        ));
    }
    echo "<script>alert('Success! 20 Sample posts generated.');</script>";
}

// 5. 处理：用户提交新帖子
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    if (!empty($_POST['content'])) {
        $wpdb->insert($table_forum, array(
            'user_name' => $_SESSION['customer_name'],  // 从 Session 获取当前用户名
            'user_email' => $_SESSION['customer_email'], // 从 Session 获取当前邮箱
            'content' => sanitize_textarea_field($_POST['content'])
        ));
        // 刷新页面防止重复提交
        echo "<script>window.location.href='';</script>";
    }
}

get_header(); 
get_template_part('header-part'); 
?>

<div class="container">
    <h1>Discussion Forum 💬</h1>
    
    <div style="background:#fff; padding:20px; border-radius:10px; margin-bottom:30px;">
        <h3>Leave your feedback</h3>
        <form method="POST">
            <div class="form-group">
                <label>Logged in as: <strong><?php echo esc_html($_SESSION['customer_name']); ?></strong></label>
                <textarea name="content" rows="3" required placeholder="Share your opinion..." style="width:100%; margin-top:10px; padding:10px;"></textarea>
            </div>
            <button type="submit" name="submit_post" class="btn">Post Comment</button>
        </form>
    </div>

    <div class="forum-list">
        <h2>Recent Discussions</h2>
        
        <?php 
        // 6. 检查是否有数据
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_forum");
        
        if ($count < 20) {
            // 如果数据少于20条，显示“一键生成”按钮
            echo '<div style="background:#ffe0e9; padding:15px; border:1px solid #ff6b8b; border-radius:5px; margin-bottom:20px;">';
            echo '<p style="color:#c0392b; font-weight:bold;">⚠️ Admin Notice: Requirements need 20 posts.</p>';
            echo '<form method="POST"><button type="submit" name="generate_dummy" class="btn" style="background:#2ecc71;">⚡ Click to Generate 20 Sample Posts</button></form>';
            echo '</div>';
        }

        // 7. 查询并显示帖子 (按时间倒序)
        $posts = $wpdb->get_results("SELECT * FROM $table_forum ORDER BY created_at DESC");
        
        if ($posts) {
            foreach ($posts as $post) {
                echo '<div style="background:white; border-bottom:1px solid #eee; padding:20px; margin-bottom:15px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">';
                echo '<div style="display:flex; justify-content:space-between; margin-bottom:10px;">';
                echo '<strong style="color:#ff6b8b;">👤 ' . esc_html($post->user_name) . '</strong>';
                echo '<span style="color:#999; font-size:0.9rem;">' . $post->created_at . '</span>';
                echo '</div>';
                echo '<p style="font-size:1.1rem; color:#333;">' . nl2br(esc_html($post->content)) . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>No posts yet. Be the first to share!</p>";
        }
        ?>
    </div>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>