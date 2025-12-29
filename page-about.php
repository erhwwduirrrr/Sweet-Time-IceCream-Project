<?php /* Template Name: Custom About Page */ ?>
<?php get_header(); ?>
<?php get_template_part('header-part'); ?>

<div class="container">
    <h1>About Sweet Time</h1>
    <div style="background:white; padding:2rem; border-radius:10px;">
        <h2>Our Story</h2>
        <p style="margin-bottom: 1rem;">
            Founded in 2010, <strong>Sweet Time Ice Cream</strong> began with a simple yet ambitious mission: to create the purest, most delightful ice cream experience imaginable. We believe that truly great ice cream starts with nature’s best gifts. That is why every scoop we serve is handcrafted daily using 100% fresh milk, locally-sourced seasonal fruits, and premium imported ingredients.
        </p>
        <p>
            We take pride in our "clean label" philosophy — absolutely no artificial preservatives, colors, or flavors are added to our products. From our humble beginnings as a small neighborhood cart to becoming a beloved local destination, our passion for quality has never wavered. Whether you are craving a classic Vanilla Bean or an adventurous seasonal sorbet, we invite you to take a break, relax, and enjoy a sweet moment with us.
        </p>
    </div>
    <div style="margin-top:20px;">
        <h3>Our Location</h3>
        <img 
        src="<?php echo get_stylesheet_directory_uri(); ?>/image/map.png" 
        alt="Store Location Map" 
        style="width: 100%; height: auto; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"
    >
    
    <p style="text-align:center; margin-top:10px; color:#666;">
        📍 Address: Gateway, 1 Macquarie Pl, Sydney NSW 2000
    </p>
</div>
</div>

<?php get_template_part('footer-part'); ?>