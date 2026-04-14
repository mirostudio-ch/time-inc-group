<?php
/**
 * Template Name: Imprint Page
 * page-imprint.php — Imprint / Legal Notice
 */
get_header();
?>
<div class="hero-inner">
  <div class="hero-inner__overlay"></div>
  <div class="hero-inner__content">
    <h1 class="section__title"><?php the_title(); ?></h1>
  </div>
</div>
<section class="section section--half" style="background: var(--color-deep);">
  <div class="legal-content">
    <?php
    while (have_posts()) : the_post();
      the_content();
    endwhile;
    ?>
  </div>
</section>
<?php get_footer(); ?>
