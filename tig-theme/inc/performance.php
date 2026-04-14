<?php
/**
 * performance.php — Performance optimizations
 */

// 1. Preconnect + Preload LCP image
function tig_preload_critical() {
  echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

  if (is_front_page()) {
    $hero = tig_get('hero_image') ?: tig_img('dubai-skyline.jpg');
    echo '<link rel="preload" as="image" href="' . esc_url($hero) . '" fetchpriority="high">' . "\n";
  }
}
add_action('wp_head', 'tig_preload_critical', 0);

// 2. Non-blocking Google Fonts (media=print trick)
function tig_deferred_fonts() {
  $url = 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Libre+Franklin:wght@300;400;500;600&display=swap';
  echo '<link rel="preload" as="style" href="' . $url . '">' . "\n";
  echo '<link rel="stylesheet" href="' . $url . '" media="print" onload="this.media=\'all\'">' . "\n";
  echo '<noscript><link rel="stylesheet" href="' . $url . '"></noscript>' . "\n";
}
add_action('wp_head', 'tig_deferred_fonts', 4);

// 3. Defer main JS
add_filter('script_loader_tag', function($tag, $handle) {
  if ($handle === 'tig-main') {
    return str_replace(' src=', ' defer src=', $tag);
  }
  return $tag;
}, 10, 2);

// 4. Remove WP bloat
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

function tig_remove_bloat() {
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'tig_remove_bloat', 100);

// 5. Remove jQuery Migrate
function tig_remove_jquery_migrate($scripts) {
  if (!is_admin() && isset($scripts->registered['jquery'])) {
    $script = $scripts->registered['jquery'];
    if ($script->deps) {
      $script->deps = array_diff($script->deps, ['jquery-migrate']);
    }
  }
}
add_action('wp_default_scripts', 'tig_remove_jquery_migrate');
