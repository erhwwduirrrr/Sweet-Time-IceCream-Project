<?php 
/* Template Name: Custom Generic Page */ 

get_header(); 
// 使用我们刚刚修好的自动导航
get_template_part('header-part'); 
?>

<div class="container">
    <h1><?php the_title(); ?></h1>
    
    <div style="background:white; padding:2rem; border-radius:10px;">
        <?php 
        // 运行 WordPress 内容循环
        // 修正点：使用大括号 { } 替代 endwhile，这样更不容易出错
        while ( have_posts() ) {
            the_post();
            the_content(); 
        } 
        ?>
    </div>
</div>

<?php 
// 加载页脚
get_template_part('footer-part'); 
get_footer(); 
?>