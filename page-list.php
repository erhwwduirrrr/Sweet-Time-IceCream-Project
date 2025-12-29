<?php /* Template Name: Customer List Page */ ?>
<?php get_header(); ?>
<?php get_template_part('header-part'); ?>

<div class="container">
    <h1>Customer List 📋</h1>
    <table class="customer-table">
        <thead><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Status</th></tr></thead>
        <tbody>
        <?php
        global $wpdb;
        $table = $wpdb->prefix . 'fc_subscribers';
        $results = $wpdb->get_results("SELECT * FROM $table");
        
        if ($results) {
            foreach ($results as $r) {
                // 2. 拼接逻辑：名 + 空格 + 姓
                // trim() 函数是为了防止如果 Last Name 为空，名字后面不会多出一个空格
                $full_name = trim($r->first_name . ' ' . $r->last_name);

                // 3. 输出全名
                echo "<tr><td>{$r->id}</td><td>{$full_name}</td><td>{$r->email}</td><td>{$r->status}</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No customers found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php get_template_part('footer-part'); ?>