<?php
/**
 * footer.php — TIG Theme
 */
$copyright = str_replace('%YEAR%', date('Y'), tig_get('company_copyright'));
$terms_page = get_page_by_path('terms');
$imprint_page = get_page_by_path('imprint');
?>

  <!-- ====== FOOTER ====== -->
  <footer class="footer" role="contentinfo">
    <div class="footer__content">
      <p class="footer__copyright"><?php echo esc_html($copyright); ?></p>
      <div class="footer__center">
        <a href="https://mirostudio.ch" target="_blank" rel="noopener noreferrer" class="footer__credit">
          Design & Development by <span class="footer__credit-name">Mirostudio</span>
        </a>
      </div>
      <div class="footer__links">
        <?php if ($terms_page) : ?>
          <a href="<?php echo get_permalink($terms_page); ?>" class="footer__link">Terms</a>
        <?php endif; ?>
        <?php if ($imprint_page) : ?>
          <a href="<?php echo get_permalink($imprint_page); ?>" class="footer__link">Imprint</a>
        <?php endif; ?>
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
