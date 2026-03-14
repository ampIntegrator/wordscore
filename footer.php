<?php

/**
 * The template for displaying the footer
 * Template Version: 6.3.1
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<?php do_action( 'bootscore_before_footer' ); ?>

<footer id="footer" class="bootscore-footer">

  <?php if (is_active_sidebar('footer-top')) : ?>
    <div class="<?= esc_attr(apply_filters('bootscore/class/footer/top', '')); ?> bootscore-footer-top <?= esc_attr(footer_top_get_bg_class()); ?>"<?= footer_top_get_style(); ?>>
      <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'footer-top')); ?>">
        <?php dynamic_sidebar('footer-top'); ?>
      </div>
    </div>
  <?php endif; ?>
  
  <?php if (footer_main_should_display()) : ?>
  <div class="<?= esc_attr(apply_filters('bootscore/class/footer/columns', '')); ?> footerMain <?= esc_attr(footer_get_main_background_class()); ?> <?= esc_attr(footer_main_get_text_color_class()); ?>"<?= footer_get_main_style(); ?>>

    <?php do_action( 'bootscore_footer_columns_before_container' ); ?>

    <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'footer-columns')); ?>">

      <?php do_action( 'bootscore_footer_columns_after_container_open' ); ?>

      <div class="row">

        <div class="<?= esc_attr(apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-1')); ?>">
          <div class="footerDetails">
            <?php footer_display_logo(); ?>
            <span><?= esc_html(get_bloginfo('name')); ?></span>
            <?php footer_display_address_lines(); ?>
            <?php footer_display_social_icons(); ?>
          </div>
        </div>

        <div class="<?= esc_attr(apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-2')); ?>">
          <?php if (is_active_sidebar('footer-2')) : ?>
            <?php dynamic_sidebar('footer-2'); ?>
          <?php endif; ?>
        </div>

        <div class="<?= esc_attr(apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-3')); ?>">
          <?php if (is_active_sidebar('footer-3')) : ?>
            <?php dynamic_sidebar('footer-3'); ?>
          <?php endif; ?>
        </div>

        <div class="<?= esc_attr(apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-4')); ?>">
          <?php if (is_active_sidebar('footer-4')) : ?>
            <?php dynamic_sidebar('footer-4'); ?>
          <?php endif; ?>
        </div>

      </div>

      <?php do_action( 'bootscore_footer_columns_before_container_close' ); ?>

    </div>

    <?php do_action( 'bootscore_footer_columns_after_container' ); ?>

  </div>
  <?php endif; ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/footer/info', 'text-center')); ?> bootscore-footer-info <?= esc_attr(footer_info_get_background_class()); ?> <?= esc_attr(footer_info_get_text_class()); ?>"<?= footer_info_get_style(); ?>>
    <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'footer-info')); ?>">

            <div class="d-block d-md-flex justify-content-between">
                    <?php do_action( 'bootscore_footer_info_after_container_open' ); ?>

        <div class="small bootscore-copyright"><span class="cr-symbol">&copy;</span>&nbsp;<?= esc_html(date_i18n('Y')); ?> <?= esc_html(get_bloginfo('name')); ?></div>
        <!-- Bootstrap 5 Nav Walker Footer Menu -->
        <?php get_template_part('template-parts/footer/footer-menu'); ?>
            </div>
    </div>
  </div>

</footer>

<!-- To top button -->
<?php
$scroll_icon = wordscore_get_cached_option('scroll_to_top_icon');
if (empty($scroll_icon)) {
    $scroll_icon = '<i class="bi bi-chevron-up"></i>';
}
?>
<a href="#" class="<?= esc_attr(apply_filters('bootscore/class/footer/to_top_button', 'btn btn-primary shadow')); ?> position-fixed zi-1000 top-button" role="button" aria-label="<?php esc_attr_e('Return to top', 'bootscore' ); ?>"><?= wp_kses_post($scroll_icon); ?></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
