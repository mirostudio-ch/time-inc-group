<?php
/**
 * sitemap.php — Native XML Sitemap + robots.txt
 */

// Rewrite rules
function tig_sitemap_rewrite() {
  add_rewrite_rule('^sitemap\.xml$', 'index.php?tig_sitemap=1', 'top');
}
add_action('init', 'tig_sitemap_rewrite');

function tig_sitemap_query_vars($vars) {
  $vars[] = 'tig_sitemap';
  return $vars;
}
add_filter('query_vars', 'tig_sitemap_query_vars');

// Render sitemap
function tig_render_sitemap() {
  if (!get_query_var('tig_sitemap')) return;

  header('Content-Type: application/xml; charset=utf-8');
  echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

  // Homepage
  echo '<url><loc>' . esc_url(home_url('/')) . '</loc><priority>1.0</priority></url>' . "\n";

  // Pages
  $pages = get_pages(['post_status' => 'publish']);
  foreach ($pages as $page) {
    if ($page->post_name === 'front-page') continue;
    echo '<url><loc>' . esc_url(get_permalink($page)) . '</loc><priority>0.7</priority></url>' . "\n";
  }

  // Posts
  $posts = get_posts(['post_type' => 'post', 'numberposts' => -1, 'post_status' => 'publish']);
  foreach ($posts as $post) {
    echo '<url><loc>' . esc_url(get_permalink($post)) . '</loc><lastmod>' . get_the_modified_date('Y-m-d', $post) . '</lastmod><priority>0.6</priority></url>' . "\n";
  }

  echo '</urlset>';
  exit;
}
add_action('template_redirect', 'tig_render_sitemap');

// robots.txt
function tig_robots_txt($output) {
  $output .= "\nSitemap: " . home_url('/sitemap.xml') . "\n";
  return $output;
}
add_filter('robots_txt', 'tig_robots_txt');
