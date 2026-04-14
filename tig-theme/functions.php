<?php
define('THEME_VERSION', '1.0.0');

// ============================================
// THEME SETUP
// ============================================
function tig_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('custom-logo');
  add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'style', 'script']);

  register_nav_menus([
    'primary' => 'Primary Menu',
    'footer'  => 'Footer Menu',
  ]);
}
add_action('after_setup_theme', 'tig_setup');

// ============================================
// ENQUEUE ASSETS
// ============================================
function tig_enqueue_assets() {
  wp_enqueue_style('tig-style', get_stylesheet_uri(), [], THEME_VERSION);
  wp_enqueue_script('tig-main', get_template_directory_uri() . '/js/main.js', [], THEME_VERSION, true);

  wp_localize_script('tig-main', 'themeAjax', [
    'url'   => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('tig_contact_form'),
  ]);
}
add_action('wp_enqueue_scripts', 'tig_enqueue_assets');

// ============================================
// CONTACT FORM HANDLER
// ============================================
function tig_handle_contact() {
  check_ajax_referer('tig_contact_form', 'nonce');

  $name    = sanitize_text_field($_POST['name'] ?? '');
  $email   = sanitize_email($_POST['email'] ?? '');
  $subject = sanitize_text_field($_POST['subject'] ?? 'Website Inquiry');
  $message = sanitize_textarea_field($_POST['message'] ?? '');

  if (!$name || !$email || !$message) {
    wp_send_json_error('Please fill in all required fields.');
  }

  $to      = esc_html(tig_get('company_email_hq'));
  $headers = ["From: $name <$email>", "Reply-To: $email"];
  $body    = "Name: $name\nE-Mail: $email\nSubject: $subject\n\nMessage:\n$message";

  $sent = wp_mail($to, "Contact from $name — " . $subject, $body, $headers);

  if ($sent) {
    wp_send_json_success('Thank you! Your message has been sent successfully.');
  } else {
    wp_send_json_error('Something went wrong. Please try contacting us via email directly.');
  }
}
add_action('wp_ajax_tig_contact_form', 'tig_handle_contact');
add_action('wp_ajax_nopriv_tig_contact_form', 'tig_handle_contact');

// ============================================
// INCLUDES
// ============================================
require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/setup-content.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/sitemap.php';
require_once get_template_directory() . '/inc/performance.php';

// ============================================
// HELPER: Get theme image URL
// ============================================
function tig_img($file) {
  return get_template_directory_uri() . '/images/' . $file;
}

// ============================================
// HELPER: Get customizer or default value
// ============================================
function tig_get($key) {
  static $defaults = null;
  if ($defaults === null) {
    $defaults = function_exists('tig_defaults') ? tig_defaults() : [];
  }
  $value = get_theme_mod($key);
  if ($value !== false && $value !== '') {
    return $value;
  }
  return $defaults[$key] ?? '';
}

// ============================================
// FALLBACK MENU
// ============================================
function tig_fallback_menu() {
  $home = home_url('/');
  $is_front = is_front_page();

  $links = [
    ['href' => $is_front ? '#home' : $home . '#home', 'label' => 'Home', 'section' => 'home'],
    ['href' => $is_front ? '#news' : $home . '#news', 'label' => 'News', 'section' => 'news'],
    ['href' => $is_front ? '#about' : $home . '#about', 'label' => 'About', 'section' => 'about'],
    ['href' => $is_front ? '#performance' : $home . '#performance', 'label' => 'Performance', 'section' => 'performance'],
    ['href' => $is_front ? '#references' : $home . '#references', 'label' => 'References', 'section' => 'references'],
    ['href' => $is_front ? '#contact' : $home . '#contact', 'label' => 'Contact', 'section' => 'contact'],
  ];

  echo '<ul class="nav__links" id="navLinks">';
  foreach ($links as $link) {
    echo '<li><a href="' . esc_url($link['href']) . '" class="nav__link" data-section="' . esc_attr($link['section']) . '">' . esc_html($link['label']) . '</a></li>';
  }
  echo '</ul>';
}

// ============================================
// EXCERPT LENGTH
// ============================================
function tig_excerpt_length($length) {
  return 25;
}
add_filter('excerpt_length', 'tig_excerpt_length');

function tig_excerpt_more($more) {
  return '&hellip;';
}
add_filter('excerpt_more', 'tig_excerpt_more');
