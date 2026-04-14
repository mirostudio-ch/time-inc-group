<?php
/**
 * front-page.php — TIG Theme Homepage
 */
get_header();

$hero_bg = tig_get('hero_image') ?: tig_img('dubai-skyline.jpg');
$contact_banner = tig_get('contact_banner_image') ?: tig_img('dubai-media-city.webp');
?>

  <!-- ====== HERO / HOME ====== -->
  <section class="hero" id="home">
    <img src="<?php echo esc_url($hero_bg); ?>" alt="" class="hero__bg" aria-hidden="true" fetchpriority="high">
    <div class="hero__gradient" aria-hidden="true"></div>
    
    <div class="hero__content">
      <img src="<?php echo tig_img('logo.png'); ?>" alt="<?php bloginfo('name'); ?>" class="hero__logo">
      <p class="hero__tagline"><?php echo esc_html(tig_get('hero_tagline')); ?></p>
      <p class="hero__subtitle"><?php echo esc_html(tig_get('hero_subtitle')); ?></p>
    </div>

    <div class="hero__scroll" aria-hidden="true">
      <span class="hero__scroll-text">Scroll</span>
      <span class="hero__scroll-line"></span>
    </div>
  </section>

  <!-- ====== NEWS ====== -->
  <section class="section section--half news" id="news">
    <div class="section__container">
      <div class="news__header reveal">
        <div>
          <span class="section__label"><?php echo esc_html(tig_get('news_label')); ?></span>
          <h2 class="section__title"><?php echo esc_html(tig_get('news_title')); ?></h2>
          <p class="section__text"><?php echo esc_html(tig_get('news_description')); ?></p>
        </div>
        <?php
        $news_cta_url = tig_get('news_cta_url');
        $news_cta_text = tig_get('news_cta_text');
        if ($news_cta_url && $news_cta_text) :
        ?>
        <a href="<?php echo esc_url($news_cta_url); ?>" target="_blank" rel="noopener noreferrer" class="news__cta">
          <?php echo esc_html($news_cta_text); ?>
          <span class="news__cta-arrow">→</span>
        </a>
        <?php endif; ?>
      </div>

      <div class="news__entries reveal reveal-delay-1">
        <?php
        $news_query = new WP_Query([
          'posts_per_page' => 3,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
        ]);

        if ($news_query->have_posts()) :
          while ($news_query->have_posts()) : $news_query->the_post();
        ?>
        <a href="<?php the_permalink(); ?>" class="news__entry">
          <time class="news__entry-date"><?php echo get_the_date('F Y'); ?></time>
          <h3 class="news__entry-title"><?php the_title(); ?></h3>
          <p class="news__entry-text"><?php echo wp_trim_words(get_the_excerpt(), 25, '&hellip;'); ?></p>
          <span class="news__entry-readmore">Read more →</span>
        </a>
        <?php
          endwhile;
          wp_reset_postdata();
        else :
        ?>
        <div class="news__entry">
          <time class="news__entry-date">Coming Soon</time>
          <h3 class="news__entry-title">Upcoming Announcements</h3>
          <p class="news__entry-text">New content and industry updates will be published here. Check back soon for the latest news from Time Inc. Group.</p>
        </div>
        <?php endif; ?>
      </div>

      <?php
      $news_page = get_option('page_for_posts');
      if ($news_page) :
      ?>
      <div style="text-align: center; margin-top: var(--space-xl);">
        <a href="<?php echo get_permalink($news_page); ?>" class="news__cta">
          All News <span class="news__cta-arrow">→</span>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ====== ABOUT ====== -->
  <section class="section about" id="about">
    <div class="about__bg" style="background-image: url('<?php echo tig_img('dubai-skyline.jpg'); ?>');" aria-hidden="true"></div>
    <div class="section__container about__content">
      <div>
        <div class="reveal">
          <span class="section__label"><?php echo esc_html(tig_get('about_label')); ?></span>
          <h2 class="section__title"><?php echo esc_html(tig_get('about_title')); ?></h2>
        </div>
        <div class="reveal reveal-delay-1">
          <p class="section__text"><?php echo esc_html(tig_get('about_text_1')); ?></p>
        </div>
        <div class="section__divider reveal reveal-delay-2"></div>
        <div class="reveal reveal-delay-3">
          <p class="section__text"><?php echo esc_html(tig_get('about_text_2')); ?></p>
        </div>
      </div>

      <div class="about__highlights">
        <?php for ($i = 1; $i <= 3; $i++) : ?>
        <div class="about__highlight reveal reveal-delay-<?php echo $i; ?>">
          <div class="about__highlight-number"><?php echo esc_html(tig_get("about_stat_{$i}_number")); ?></div>
          <div class="about__highlight-label"><?php echo esc_html(tig_get("about_stat_{$i}_label")); ?></div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- ====== PERFORMANCE ====== -->
  <section class="section performance" id="performance">
    <div class="section__container">
      <div class="reveal">
        <span class="section__label"><?php echo esc_html(tig_get('perf_label')); ?></span>
        <h2 class="section__title"><?php echo esc_html(tig_get('perf_title')); ?></h2>
      </div>

      <div class="performance__content">
        <div class="performance__description reveal reveal-delay-1">
          <p><?php echo esc_html(tig_get('perf_text_1')); ?></p>
          <p><?php echo esc_html(tig_get('perf_text_2')); ?></p>
        </div>

        <div class="performance__services reveal reveal-delay-2">
          <?php
          $service_icons = [
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>',
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
          ];
          for ($i = 1; $i <= 4; $i++) :
          ?>
          <div class="performance__service">
            <div class="performance__service-icon"><?php echo $service_icons[$i - 1]; ?></div>
            <h4 class="performance__service-title"><?php echo esc_html(tig_get("perf_service_{$i}_title")); ?></h4>
            <p class="performance__service-text"><?php echo esc_html(tig_get("perf_service_{$i}_text")); ?></p>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <div class="performance__brands reveal reveal-delay-3">
        <h3 class="performance__brands-title">Brand Portfolio</h3>
        <div class="performance__brands-grid">
          <a href="https://tourbillon-magazine.com" target="_blank" rel="noopener noreferrer" class="performance__brand">Tourbillon-Magazine.com</a>
          <a href="https://tourbillon-magazin.ch" target="_blank" rel="noopener noreferrer" class="performance__brand">Tourbillon-Magazin.ch</a>
          <a href="https://tourbillon-tv.com" target="_blank" rel="noopener noreferrer" class="performance__brand">Tourbillon-TV.com</a>
          <a href="https://art-of-tourbillon.com" target="_blank" rel="noopener noreferrer" class="performance__brand">Art-of-Tourbillon.com</a>
          <a href="https://tick-talk.ch" target="_blank" rel="noopener noreferrer" class="performance__brand">Tick-Talk.ch</a>
          <a href="https://tick-talk.online" target="_blank" rel="noopener noreferrer" class="performance__brand">Tick-Talk.online</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== REFERENCES ====== -->
  <section class="section references" id="references">
    <div class="section__container">
      <div class="reveal">
        <span class="section__label"><?php echo esc_html(tig_get('ref_label')); ?></span>
        <h2 class="section__title"><?php echo nl2br(esc_html(tig_get('ref_title'))); ?></h2>
        <p class="section__text"><?php echo esc_html(tig_get('ref_text')); ?></p>
      </div>

      <div class="references__grid">
        <?php for ($i = 1; $i <= 4; $i++) : ?>
        <div class="references__item reveal reveal-delay-<?php echo $i; ?>">
          <div class="references__item-brand"><?php echo esc_html(tig_get("ref_{$i}_brand")); ?></div>
          <div class="references__item-desc"><?php echo esc_html(tig_get("ref_{$i}_desc")); ?></div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- ====== CONTACT ====== -->
  <section class="section contact" id="contact">
    <div class="contact__banner reveal">
      <img src="<?php echo esc_url($contact_banner); ?>" alt="Dubai Media City" class="contact__banner-img" loading="lazy">
      <div class="contact__banner-overlay"></div>
      <div class="contact__banner-content">
        <span class="section__label"><?php echo esc_html(tig_get('contact_label')); ?></span>
        <h2 class="section__title"><?php echo esc_html(tig_get('contact_title')); ?></h2>
      </div>
    </div>

    <div class="section__container">
      <div class="contact__grid">
        <!-- Contact Form -->
        <div class="reveal reveal-delay-1">
          <form class="contact__form" id="contactForm" novalidate>
            <div class="contact__form-status" id="formStatus" role="alert"></div>
            
            <div class="contact__field">
              <label for="contactName" class="contact__label">Name</label>
              <input type="text" id="contactName" name="name" class="contact__input" placeholder="Your name" required>
            </div>

            <div class="contact__field">
              <label for="contactEmail" class="contact__label">Email</label>
              <input type="email" id="contactEmail" name="email" class="contact__input" placeholder="your@email.com" required>
            </div>

            <div class="contact__field">
              <label for="contactSubject" class="contact__label">Subject</label>
              <input type="text" id="contactSubject" name="subject" class="contact__input" placeholder="Subject of your inquiry">
            </div>

            <div class="contact__field">
              <label for="contactMessage" class="contact__label">Message</label>
              <textarea id="contactMessage" name="message" class="contact__textarea" placeholder="Your message..." required></textarea>
            </div>

            <button type="submit" class="contact__submit" id="contactSubmit">
              Send Message
              <span>→</span>
            </button>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="contact__info reveal reveal-delay-2">
          <div class="contact__office">
            <h3 class="contact__office-title"><?php echo esc_html(tig_get('contact_hq_title')); ?></h3>
            <div class="contact__office-details">
              <?php echo esc_html(tig_get('company_address')); ?><br>
              <?php echo esc_html(tig_get('company_address_2')); ?><br>
              <?php echo esc_html(tig_get('company_address_city')); ?>
            </div>
            <a href="mailto:<?php echo esc_attr(tig_get('company_email_hq')); ?>" class="contact__office-email">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <rect x="1" y="3" width="14" height="10" rx="1" stroke="currentColor" stroke-width="1.2"/>
                <path d="M1 4l7 5 7-5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
              </svg>
              <?php echo esc_html(tig_get('company_email_hq')); ?>
            </a>
          </div>

          <div class="contact__office">
            <h3 class="contact__office-title"><?php echo esc_html(tig_get('contact_ch_title')); ?></h3>
            <a href="mailto:<?php echo esc_attr(tig_get('company_email_ch')); ?>" class="contact__office-email">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <rect x="1" y="3" width="14" height="10" rx="1" stroke="currentColor" stroke-width="1.2"/>
                <path d="M1 4l7 5 7-5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
              </svg>
              <?php echo esc_html(tig_get('company_email_ch')); ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
