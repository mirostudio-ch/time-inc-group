<?php
/**
 * setup-content.php — Auto-create pages and sample posts on theme activation
 */
function tig_activation() {
  flush_rewrite_rules();

  if (get_option('tig_content_created')) return;

  // --- Create "Home" front page ---
  if (!get_page_by_path('front-page')) {
    $front_id = wp_insert_post([
      'post_title'  => 'Home',
      'post_name'   => 'front-page',
      'post_type'   => 'page',
      'post_status' => 'publish',
    ]);
    update_option('page_on_front', $front_id);
    update_option('show_on_front', 'page');
  }

  // --- Create "News" posts page ---
  if (!get_page_by_path('news')) {
    $news_id = wp_insert_post([
      'post_title'  => 'News',
      'post_name'   => 'news',
      'post_type'   => 'page',
      'post_status' => 'publish',
    ]);
    update_option('page_for_posts', $news_id);
  }

  // --- Create legal pages ---
  $legal_pages = [
    [
      'title'   => 'Terms',
      'slug'    => 'terms',
      'content' => '<h2>Terms & Conditions</h2><p>These terms and conditions govern your use of the Time Inc. Group website. By accessing this website, you agree to these terms in full.</p><h3>1. Use of Content</h3><p>All content on this website is the property of TIG Time Inc. Group and protected by international copyright laws. No reproduction, distribution, or modification of any content is permitted without prior written consent.</p><h3>2. Limitation of Liability</h3><p>Time Inc. Group shall not be liable for any indirect, incidental, or consequential damages arising from the use of this website.</p><h3>3. Governing Law</h3><p>These terms are governed by the laws of the United Arab Emirates. Any disputes shall be subject to the exclusive jurisdiction of the courts of Dubai.</p>',
    ],
    [
      'title'   => 'Imprint',
      'slug'    => 'imprint',
      'content' => '<h2>Legal Notice (Imprint)</h2><p><strong>TIG Time Inc. Group</strong></p><p>Dubai Media City Management Office<br>Building – 2<br>Dubai Media City, UAE</p><p>Email: <a href="mailto:hq@time-inc-group.com">hq@time-inc-group.com</a></p><h3>Representative Office Switzerland</h3><p>Email: <a href="mailto:repoffswitz@time-inc-group.com">repoffswitz@time-inc-group.com</a></p><h3>Disclaimer</h3><p>Despite careful content control, we assume no liability for the content of external links. The operators of the linked pages are solely responsible for their content.</p>',
    ],
  ];

  foreach ($legal_pages as $page) {
    if (!get_page_by_path($page['slug'])) {
      wp_insert_post([
        'post_title'   => $page['title'],
        'post_name'    => $page['slug'],
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => $page['content'],
      ]);
    }
  }

  // --- Create sample news posts ---
  $sample_posts = [
    [
      'title'   => 'TIG Expands Strategic Consulting in the Middle East',
      'date'    => '2026-03-15 10:00:00',
      'content' => '<p>Time Inc. Group strengthens its presence in the Gulf region with new brand consulting partnerships, focusing on luxury watch distribution and market positioning.</p><p>The expansion includes strategic alliances with regional distributors and retail networks, leveraging TIG\'s 50+ years of expertise in the luxury watch industry to open new markets across the Middle East and North Africa.</p><p>This initiative underscores TIG\'s commitment to providing tailor-made 360-degree consultation services that bridge European craftsmanship with emerging luxury markets.</p>',
      'excerpt' => 'Time Inc. Group strengthens its presence in the Gulf region with new brand consulting partnerships, focusing on luxury watch distribution and market positioning.',
    ],
    [
      'title'   => 'Tourbillon Magazine — New Digital Edition Launched',
      'date'    => '2026-02-10 09:00:00',
      'content' => '<p>The latest edition of Tourbillon Magazine is now available online, featuring exclusive interviews with independent watchmakers and in-depth brand profiles.</p><p>This digital edition showcases the finest in contemporary horology, with features on emerging independent brands, technical innovation in movement design, and the latest trends in luxury watch collecting.</p><p>Visit <a href="https://tourbillon-magazine.com" target="_blank">Tourbillon-Magazine.com</a> to read the latest edition.</p>',
      'excerpt' => 'The latest edition of Tourbillon Magazine is now available online, featuring exclusive interviews with independent watchmakers and in-depth brand profiles.',
    ],
    [
      'title'   => 'Partnership with Swiss Watch Industry Leaders',
      'date'    => '2026-01-20 08:00:00',
      'content' => '<p>TIG announces new collaboration with leading Swiss manufacture brands, offering end-to-end sales strategy and global marketing solutions.</p><p>The partnership brings together TIG\'s extensive international network with Switzerland\'s finest watchmaking traditions, creating unprecedented opportunities for brand growth and global distribution.</p><p>Services include comprehensive marketing strategy, PR and communication, digital content creation, and international sales network development.</p>',
      'excerpt' => 'TIG announces new collaboration with leading Swiss manufacture brands, offering end-to-end sales strategy and global marketing solutions.',
    ],
  ];

  foreach ($sample_posts as $post_data) {
    $existing = get_page_by_title($post_data['title'], OBJECT, 'post');
    if (!$existing) {
      wp_insert_post([
        'post_title'   => $post_data['title'],
        'post_type'    => 'post',
        'post_status'  => 'publish',
        'post_date'    => $post_data['date'],
        'post_content' => $post_data['content'],
        'post_excerpt' => $post_data['excerpt'],
      ]);
    }
  }

  update_option('tig_content_created', true);
}
add_action('after_switch_theme', 'tig_activation');

// --- Reset mechanism ---
function tig_reset_content() {
  if (isset($_GET['theme_reset_content']) && $_GET['theme_reset_content'] === '1' && current_user_can('manage_options')) {
    delete_option('tig_content_created');

    // Delete auto-created posts
    $posts = get_posts(['post_type' => 'post', 'numberposts' => -1, 'post_status' => 'any']);
    foreach ($posts as $p) { wp_delete_post($p->ID, true); }

    // Delete auto-created pages
    $pages = ['front-page', 'news', 'terms', 'imprint'];
    foreach ($pages as $slug) {
      $page = get_page_by_path($slug);
      if ($page) { wp_delete_post($page->ID, true); }
    }

    tig_activation();
    wp_redirect(admin_url());
    exit;
  }
}
add_action('admin_init', 'tig_reset_content');
