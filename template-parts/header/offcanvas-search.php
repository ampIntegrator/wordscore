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
        <h5 class="offcanvas-title"><?php esc_html_e('Recherche', 'wordscore'); ?></h5>
        <button type="button" class="btn-close-custom <?= esc_attr(header_search_get_text_class()); ?>" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Fermer', 'wordscore'); ?>">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="container">
            <?php get_search_form(); ?>
        </div>
    </div>
</div>
