<?php
function tig_customizer($wp_customize) {
  $defaults = tig_defaults();

  // ==========================================
  // PANEL: Global Settings
  // ==========================================
  $wp_customize->add_panel('tig_global', [
    'title'    => 'Global Settings',
    'priority' => 20,
  ]);

  // --- Section: Company Info ---
  $wp_customize->add_section('tig_company', [
    'title' => 'Company Info',
    'panel' => 'tig_global',
  ]);

  $company_fields = [
    'company_name'         => 'Company Name',
    'company_email_hq'     => 'HQ Email',
    'company_email_ch'     => 'Switzerland Office Email',
    'company_address'      => 'Address Line 1',
    'company_address_2'    => 'Address Line 2',
    'company_address_city' => 'City / Country',
    'company_copyright'    => 'Copyright Text (%YEAR% = current year)',
  ];
  foreach ($company_fields as $key => $label) {
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_company', 'type' => 'text']);
  }

  // ==========================================
  // PANEL: Homepage
  // ==========================================
  $wp_customize->add_panel('tig_homepage', [
    'title'    => 'Homepage',
    'priority' => 25,
  ]);

  // --- Section: Hero ---
  $wp_customize->add_section('tig_hero', [
    'title' => 'Hero',
    'panel' => 'tig_homepage',
  ]);

  $hero_fields = [
    'hero_tagline' => 'Tagline',
    'hero_subtitle' => 'Subtitle',
  ];
  foreach ($hero_fields as $key => $label) {
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_hero', 'type' => 'text']);
  }

  // Hero image
  $wp_customize->add_setting('hero_image', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', [
    'label'   => 'Hero Background Image',
    'section' => 'tig_hero',
  ]));

  // --- Section: News Header ---
  $wp_customize->add_section('tig_news', [
    'title' => 'News Section Header',
    'panel' => 'tig_homepage',
  ]);

  $news_fields = [
    'news_label'       => 'Label',
    'news_title'       => 'Title',
    'news_description' => ['Description', 'textarea'],
    'news_cta_text'    => 'CTA Button Text',
    'news_cta_url'     => 'CTA Button URL',
  ];
  foreach ($news_fields as $key => $field) {
    $label = is_array($field) ? $field[0] : $field;
    $type  = is_array($field) ? $field[1] : 'text';
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_news', 'type' => $type]);
  }

  // --- Section: About ---
  $wp_customize->add_section('tig_about', [
    'title' => 'About',
    'panel' => 'tig_homepage',
  ]);

  $about_fields = [
    'about_label'         => 'Label',
    'about_title'         => 'Title',
    'about_text_1'        => ['Text Paragraph 1', 'textarea'],
    'about_text_2'        => ['Text Paragraph 2', 'textarea'],
    'about_stat_1_number' => 'Stat 1 Number',
    'about_stat_1_label'  => 'Stat 1 Label',
    'about_stat_2_number' => 'Stat 2 Number',
    'about_stat_2_label'  => 'Stat 2 Label',
    'about_stat_3_number' => 'Stat 3 Number',
    'about_stat_3_label'  => 'Stat 3 Label',
  ];
  foreach ($about_fields as $key => $field) {
    $label = is_array($field) ? $field[0] : $field;
    $type  = is_array($field) ? $field[1] : 'text';
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_about', 'type' => $type]);
  }

  // --- Section: Performance ---
  $wp_customize->add_section('tig_performance', [
    'title' => 'Performance / Services',
    'panel' => 'tig_homepage',
  ]);

  $perf_fields = [
    'perf_label'           => 'Label',
    'perf_title'           => 'Title',
    'perf_text_1'          => ['Description Paragraph 1', 'textarea'],
    'perf_text_2'          => ['Description Paragraph 2', 'textarea'],
    'perf_service_1_title' => 'Service 1 Title',
    'perf_service_1_text'  => 'Service 1 Description',
    'perf_service_2_title' => 'Service 2 Title',
    'perf_service_2_text'  => 'Service 2 Description',
    'perf_service_3_title' => 'Service 3 Title',
    'perf_service_3_text'  => 'Service 3 Description',
    'perf_service_4_title' => 'Service 4 Title',
    'perf_service_4_text'  => 'Service 4 Description',
  ];
  foreach ($perf_fields as $key => $field) {
    $label = is_array($field) ? $field[0] : $field;
    $type  = is_array($field) ? $field[1] : 'text';
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_performance', 'type' => $type]);
  }

  // --- Section: References ---
  $wp_customize->add_section('tig_references', [
    'title' => 'References',
    'panel' => 'tig_homepage',
  ]);

  $ref_fields = [
    'ref_label'   => 'Label',
    'ref_title'   => 'Title',
    'ref_text'    => ['Description', 'textarea'],
    'ref_1_brand' => 'Reference 1 Brand',
    'ref_1_desc'  => 'Reference 1 Description',
    'ref_2_brand' => 'Reference 2 Brand',
    'ref_2_desc'  => 'Reference 2 Description',
    'ref_3_brand' => 'Reference 3 Brand',
    'ref_3_desc'  => 'Reference 3 Description',
    'ref_4_brand' => 'Reference 4 Brand',
    'ref_4_desc'  => 'Reference 4 Description',
  ];
  foreach ($ref_fields as $key => $field) {
    $label = is_array($field) ? $field[0] : $field;
    $type  = is_array($field) ? $field[1] : 'text';
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_references', 'type' => $type]);
  }

  // --- Section: Contact ---
  $wp_customize->add_section('tig_contact', [
    'title' => 'Contact',
    'panel' => 'tig_homepage',
  ]);

  $contact_fields = [
    'contact_label'    => 'Label',
    'contact_title'    => 'Title',
    'contact_hq_title' => 'HQ Title',
    'contact_ch_title' => 'Switzerland Office Title',
  ];
  foreach ($contact_fields as $key => $label) {
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_contact', 'type' => 'text']);
  }

  // Contact banner image
  $wp_customize->add_setting('contact_banner_image', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'contact_banner_image', [
    'label'   => 'Contact Banner Image',
    'section' => 'tig_contact',
  ]));

  // ==========================================
  // PANEL: SEO
  // ==========================================
  $wp_customize->add_section('tig_seo', [
    'title'    => 'SEO Settings',
    'priority' => 30,
  ]);

  $seo_fields = [
    'seo_home_title' => 'Homepage Meta Title',
    'seo_home_desc'  => ['Homepage Meta Description', 'textarea'],
  ];
  foreach ($seo_fields as $key => $field) {
    $label = is_array($field) ? $field[0] : $field;
    $type  = is_array($field) ? $field[1] : 'text';
    $wp_customize->add_setting($key, ['default' => $defaults[$key] ?? '', 'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
    $wp_customize->add_control($key, ['label' => $label, 'section' => 'tig_seo', 'type' => $type]);
  }
}
add_action('customize_register', 'tig_customizer');
