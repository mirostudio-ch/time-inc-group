<?php
/**
 * single.php — Individual News Post
 */
get_header();
?>

  <!-- Inner Page Hero -->
  <div class="hero-inner">
    <div class="hero-inner__overlay"></div>
    <div class="hero-inner__content">
      <span class="section__label"><?php echo get_the_date('F j, Y'); ?></span>
      <h1 class="section__title"><?php the_title(); ?></h1>
    </div>
  </div>

  <!-- Article Content -->
  <section class="single-article">
    <div class="single-article__content">
      <?php
      $news_page = get_option('page_for_posts');
      if ($news_page) :
      ?>
      <a href="<?php echo get_permalink($news_page); ?>" class="back-link">← Back to News</a>
      <?php endif; ?>

      <?php
      while (have_posts()) : the_post();
        the_content();
      endwhile;
      ?>
    </div>

    <!-- Post Navigation -->
    <?php
    $prev = get_previous_post();
    $next = get_next_post();
    if ($prev || $next) :
    ?>
    <div class="post-nav">
      <?php if ($prev) : ?>
      <a href="<?php echo get_permalink($prev); ?>" class="post-nav__link">
        <span class="post-nav__label">← Previous</span>
        <span class="post-nav__title"><?php echo esc_html($prev->post_title); ?></span>
      </a>
      <?php endif; ?>

      <?php if ($next) : ?>
      <a href="<?php echo get_permalink($next); ?>" class="post-nav__link post-nav__link--next">
        <span class="post-nav__label">Next →</span>
        <span class="post-nav__title"><?php echo esc_html($next->post_title); ?></span>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </section>

<?php get_footer(); ?>
