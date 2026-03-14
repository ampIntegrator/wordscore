<?php
/**
 * Template part pour l'offcanvas de recherche
 *
 * @package Bootscore_Child
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!header_search_should_display()) {
    return;
}
?>

<!-- Offcanvas Search -->
<div class="offcanvas offcanvas-top <?= esc_attr(header_search_get_bg_class()); ?> <?= esc_attr(header_search_get_text_class()); ?>" tabindex="-1" id="offcanvas-search"<?= header_search_get_bg_style(); ?>>
    <div class="offcanvas-header">
        <div class="container">
            <h5 class="offcanvas-title"><?php esc_html_e('Recherche', 'wordscore'); ?></h5>
            <button type="button" class="btn-close-custom <?= esc_attr(header_search_get_text_class()); ?>" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Fermer', 'wordscore'); ?>">
                <?php
                $close_icon = get_field('offcanvas_menu_close_icon', 'option');
                echo !empty($close_icon) ? wp_kses_post($close_icon) : '<i class="bi bi-x-lg"></i>';
                ?>
            </button>

        </div>
    </div>
    <div class="offcanvas-body pt-0">
        <div class="container">
            <?php get_search_form(); ?>
        </div>
    </div>
</div>
