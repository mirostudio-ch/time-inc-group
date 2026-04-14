<?php
/**
 * 404.php — Page Not Found
 */
get_header();
?>
<section class="page-404">
  <div>
    <div class="page-404__number">404</div>
    <h1 class="page-404__title">Page Not Found</h1>
    <p class="page-404__text">The page you're looking for doesn't exist or has been moved.</p>
    <a href="<?php echo home_url('/'); ?>" class="page-404__btn">
      Back to Homepage <span>→</span>
    </a>
  </div>
</section>
<?php get_footer(); ?>
