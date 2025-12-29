<?php /* Template Name: Custom Home Page */ ?>
<?php get_header(); ?>
<?php get_template_part('header-part'); ?>

<div class="container">
    <h1>Welcome to Sweet Time Ice Cream</h1>
    <p style="font-size: 1.2rem; margin-bottom: 2rem;">
        Handcrafted delicious ice cream made with natural ingredients, no preservatives added.
    </p>
    
    <div style="background: #ffe0e9; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; display: flex; align-items: center; gap: 2rem;">
        <div style="flex: 1;">
            <h2>New Arrival</h2>
            <p>Summer Limited - Mango Passion Fruit Ice Cream!</p>
            <a href="<?php echo home_url('/?page_id=39'); ?>" class="btn">Buy Now</a>
        </div>
        <div style="flex: 1;">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/0.JPG" style="width: 100%; border-radius: 5px;">
        </div>
    </div>

    <h2>Our Advantages</h2>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 1rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3>Natural Ingredients</h3><p>Selected high-quality milk sources.</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3>Multiple Flavors</h3><p>Over 20 classic and creative flavors.</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3>Fast Delivery</h3><p>Professional cold chain delivery.</p>
        </div>
    </div>
</div>

<?php get_template_part('footer-part'); ?>