<?php
/**
 * seo.php — Native SEO module
 */

// Disable WP default title to use our custom one
function tig_disable_wp_title() {
  add_filter('pre_get_document_title', '__return_empty_string');
  remove_action('wp_head', '_wp_render_title_tag', 1);
}
add_action('after_setup_theme', 'tig_disable_wp_title');

// Custom <title> + meta tags
function tig_seo_head() {
  $site_name = get_bloginfo('name');

  // Title
  if (is_front_page()) {
    $title = tig_get('seo_home_title') ?: $site_name;
    $description = tig_get('seo_home_desc');
  } elseif (is_home()) {
    $title = 'News — ' . $site_name;
    $description = 'Latest news and updates from ' . $site_name;
  } elseif (is_single()) {
    $title = get_the_title() . ' — ' . $site_name;
    $description = wp_trim_words(get_the_excerpt(), 30, '');
  } elseif (is_page()) {
    $title = get_the_title() . ' — ' . $site_name;
    $description = '';
  } elseif (is_404()) {
    $title = 'Page Not Found — ' . $site_name;
    $description = '';
  } else {
    $title = $site_name;
    $description = '';
  }

  echo '<title>' . esc_html($title) . '</title>' . "\n";

  if ($description) {
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
  }

  // Canonical
  if (is_front_page()) {
    echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
  } elseif (is_singular()) {
    echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
  }

  // Open Graph
  echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
  echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
  if ($description) {
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
  }
  echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '">' . "\n";
  echo '<meta property="og:image" content="' . esc_url(tig_img('logo.png')) . '">' . "\n";

  // Schema.org — Organization
  if (is_front_page()) {
    $schema = [
      '@context' => 'https://schema.org',
      '@type'    => 'Organization',
      'name'     => tig_get('company_name'),
      'url'      => home_url('/'),
      'logo'     => tig_img('logo.png'),
      'email'    => tig_get('company_email_hq'),
      'address'  => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => tig_get('company_address') . ', ' . tig_get('company_address_2'),
        'addressLocality' => 'Dubai',
        'addressCountry'  => 'AE',
      ],
    ];
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
  }
}
add_action('wp_head', 'tig_seo_head', 1);
