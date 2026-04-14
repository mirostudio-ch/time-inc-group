<?php
/**
 * index.php — Fallback template
 */
get_header();
?>
<section class="section section--half">
  <div class="section__container">
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post();
        the_content();
      endwhile;
    endif;
    ?>
  </div>
</section>
<?php get_footer(); ?>
