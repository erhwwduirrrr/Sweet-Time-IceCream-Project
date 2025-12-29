<header class="custom-header">
    <nav class="navbar">
        <div class="logo">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/Logo.PNG" alt="Sweet Time Logo">
        </div>

        <?php 
        wp_nav_menu( array(
            'theme_location' => 'primary',   // 对应 WP 后台的“主菜单”位置
            'menu_class'     => 'nav-links', // 保持你的 CSS 样式类名
            'container'      => false,       // 不要在外面包一层 div
            'fallback_cb'    => false        // 如果没设置菜单，就什么都不显示（防止乱码）
        ) );
        ?>
    </nav>
</header>