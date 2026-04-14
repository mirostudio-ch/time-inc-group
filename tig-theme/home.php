<?php
/**
 * home.php — News Archive Page (Blog Listing)
 */
get_header();
?>

  <!-- Inner Page Hero -->
  <div class="hero-inner">
    <div class="hero-inner__overlay"></div>
    <div class="hero-inner__content">
      <span class="section__label">Latest Updates</span>
      <h1 class="section__title">News</h1>
    </div>
  </div>

  <!-- News Grid -->
  <section class="archive-news">
    <div class="archive-news__grid">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="news__entry">
          <time class="news__entry-date"><?php echo get_the_date('F Y'); ?></time>
          <h2 class="news__entry-title"><?php the_title(); ?></h2>
          <p class="news__entry-text"><?php echo wp_trim_words(get_the_excerpt(), 25, '&hellip;'); ?></p>
          <span class="news__entry-readmore">Read more →</span>
        </a>
        <?php endwhile; ?>
      <?php else : ?>
        <div class="archive-news__empty">
          <p>No news articles found. Check back soon.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php
    // Pagination
    $pagination = paginate_links([
      'prev_text' => '← Previous',
      'next_text' => 'Next →',
      'type'      => 'plain',
    ]);
    if ($pagination) :
    ?>
    <div class="pagination">
      <?php echo $pagination; ?>
    </div>
    <?php endif; ?>
  </section>

<?php get_footer(); ?>
