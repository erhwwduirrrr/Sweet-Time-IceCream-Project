<?php 
/* Template Name: Payment Simulation Page */ 
get_header(); 
get_template_part('header-part'); 
?>

<div class="container" style="text-align:center; padding-top:50px;">
    <h1>💳 Processing Payment...</h1>
    <p>Please wait while we connect to the bank.</p>
    <div style="font-size:3rem; margin:20px;">🔄</div>
    
    <script>
        setTimeout(function(){
            alert("Payment Successful! Thank you for your order.");
            window.location.href = "<?php echo home_url('/?page_id=39'); ?>";
        }, 3000);
    </script>
</div>

<?php get_template_part('footer-part'); get_footer(); ?>