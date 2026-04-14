<?php
/**
 * header.php — TIG Theme
 */
$home = home_url('/');
$is_front = is_front_page();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php if (has_site_icon()) : ?>
    <?php wp_site_icon(); ?>
  <?php else : ?>
    <link rel="icon" type="image/png" href="<?php echo tig_img('logo.png'); ?>">
  <?php endif; ?>

  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <!-- Grain Texture Overlay -->
  <div class="grain" aria-hidden="true"></div>

  <!-- ====== NAVIGATION ====== -->
  <nav class="nav" id="navbar" role="navigation" aria-label="Main navigation">
    <a href="<?php echo esc_url($home); ?>" class="nav__logo">
      <img src="<?php echo tig_img('logo.png'); ?>" alt="<?php bloginfo('name'); ?>" class="nav__logo-img">
    </a>

    <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'nav__links',
      'menu_id'        => 'navLinks',
      'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'fallback_cb'    => 'tig_fallback_menu',
      'link_before'    => '',
      'link_after'     => '',
    ]);
    ?>

    <button class="nav__toggle" id="navToggle" aria-label="Toggle navigation menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </nav>
