<?php
/**
 * Bloc Tabs
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 0);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 0);

// Titre
$heading_tag = flexible_get_heading_tag($bloc['heading_tag'] ?? 'h2', 'h2');
$heading_text = $bloc['heading_text'] ?? '';
$text_align_class = flexible_get_text_align_class($bloc['text_align'] ?? 'left');
$heading_text_class = flexible_get_text_color_class($bloc['heading_text_color'] ?? 'dark');
$heading_font_size_override = isset($bloc['heading_font_size_override']) ? intval($bloc['heading_font_size_override']) : 0;

// Largeur et inversion
$tabs_width = $bloc['tabs_width'] ?? '100';
$tabs_col_class = flexible_get_col_class($tabs_width, 'col-12');
$order_class = flexible_get_order_class($bloc['inverser'] ?? false);

// Image
$has_image = ($tabs_width != '100' && !empty($bloc['image_col']));
$image_col_class = $has_image ? flexible_get_image_col_class($tabs_width) : '';
$image_url = $has_image ? flexible_get_image_url($bloc['image_col']) : false;
$image_min_height = isset($bloc['image_min_height']) ? max(200, min(600, intval($bloc['image_min_height']))) : 300;

// Content-wrapper
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';

// Tab-content background color
$tab_content_bg_choice = $bloc['tab_content_bg_color'] ?? 'theme1';
$tab_content_bg_custom = $bloc['tab_content_bg_custom_color'] ?? '#8a2be2';
$tab_content_text_color = $bloc['tab_content_text_color'] ?? 'light';

// Tabs
$tabs_items = $bloc['tabs_items'] ?? [];
$tabs_id = flexible_get_unique_id('tabs');

// Générer le CSS inline pour ce bloc tabs spécifique
$tab_bg_color_css = '';
if ($tab_content_bg_choice === 'custom') {
    $tab_bg_color_css = esc_attr($tab_content_bg_custom);
} else {
    // Convertir theme1 -> colorTheme1
    $theme_number = str_replace('theme', '', $tab_content_bg_choice);
    $tab_bg_color_css = 'var(--colorTheme' . $theme_number . ')';
}

$tab_text_color_css = $tab_content_text_color === 'light' ? 'white' : 'var(--colorText)';

$tabs_inline_style = "
<style>
#" . $tabs_id . " .nav-link.active {
    background-color: {$tab_bg_color_css} !important;
    color: {$tab_text_color_css} !important;
}
#" . $tabs_id . "-content {
    background-color: {$tab_bg_color_css};
}
#" . $tabs_id . "-content * {
    color: {$tab_text_color_css};
}
</style>
";

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<?php echo $tabs_inline_style; ?>
<section class="bloc-section bloc-tabs <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $order_class; ?>">
            <div class="col-12 <?php echo $tabs_col_class; ?>">
                <div class="content-wrapper" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">

                    <?php if ($heading_text) : ?>
                        <<?php echo $heading_tag; ?> class="<?php echo $text_align_class . ' ' . $heading_text_class; ?>"<?php echo flexible_get_heading_style($heading_tag, $heading_font_size_override); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                    <?php endif; ?>

                    <?php if (!empty($tabs_items)) : ?>
                        <ul class="nav nav-tabs" id="<?php echo $tabs_id; ?>" role="tablist">
                            <?php foreach ($tabs_items as $index => $item) :
                                $item_id = 'tab-' . $tabs_id . '-' . $index;
                                $item_title = $item['tab_title'] ?? '';
                                $is_active = ($index === 0);
                            ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo $is_active ? 'active' : ''; ?>"
                                            id="<?php echo $item_id; ?>-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#<?php echo $item_id; ?>"
                                            type="button"
                                            role="tab"
                                            aria-controls="<?php echo $item_id; ?>"
                                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
                                        <?php echo esc_html($item_title); ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content" id="<?php echo $tabs_id; ?>-content">
                            <?php foreach ($tabs_items as $index => $item) :
                                $item_id = 'tab-' . $tabs_id . '-' . $index;
                                $item_content = $item['tab_content'] ?? '';
                                $is_active = ($index === 0);
                            ?>
                                <div class="tab-pane fade <?php echo $is_active ? 'show active' : ''; ?>"
                                     id="<?php echo $item_id; ?>"
                                     role="tabpanel"
                                     aria-labelledby="<?php echo $item_id; ?>-tab">
                                    <?php echo wp_kses_post($item_content); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // CTA Button (optionnel)
                    $cta_link = $bloc['tabs_cta_link'] ?? null;
                    if ($cta_link && !empty($cta_link['url'])) :
                        $cta_margin_top = isset($bloc['tabs_cta_margin_top']) ? intval($bloc['tabs_cta_margin_top']) : 20;
                        $cta_align = $bloc['tabs_cta_align'] ?? 'center';
                    ?>
                        <div class="d-flex justify-content-<?php echo esc_attr($cta_align); ?>" style="margin-top: <?php echo $cta_margin_top; ?>px;">
                            <?php echo flexible_render_button(
                                $cta_link,
                                $bloc['tabs_cta_type'] ?? 'primary',
                                $bloc['tabs_cta_outline'] ?? false,
                                '',
                                $bloc['tabs_cta_text_color'] ?? ''
                            ); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php if ($has_image && $image_url) : ?>
                <div class="col-12 <?php echo $image_col_class; ?>">
                    <div class="image-wrapper" style="background-image: url('<?php echo esc_url($image_url); ?>'); min-height: <?php echo $image_min_height; ?>px;"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
