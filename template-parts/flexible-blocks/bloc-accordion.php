<?php
/**
 * Bloc Accordion
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
$accordion_width = $bloc['accordion_width'] ?? '100';
$accordion_col_class = flexible_get_col_class($accordion_width, 'col-12');
$order_class = flexible_get_order_class($bloc['inverser'] ?? false);

// Image
$has_image = ($accordion_width != '100' && !empty($bloc['image_col']));
$image_col_class = $has_image ? flexible_get_image_col_class($accordion_width) : '';
$image_url = $has_image ? flexible_get_image_url($bloc['image_col']) : false;
$image_min_height = isset($bloc['image_min_height']) ? max(200, min(600, intval($bloc['image_min_height']))) : 300;

// Content-wrapper
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';

// Accordion
$accordion_items = $bloc['accordion_items'] ?? [];
$accordion_heading_tag = flexible_get_heading_tag($bloc['accordion_heading_tag'] ?? 'h3', 'h3');
$first_item_open = $bloc['first_item_open'] ?? 'open';
$accordion_id = flexible_get_unique_id('accordion');

// Couleurs accordion
$accordion_button_bg_choice = $bloc['accordion_button_bg_color'] ?? 'theme1';
$accordion_button_bg_custom = $bloc['accordion_button_bg_custom_color'] ?? '#8a2be2';
$accordion_body_padding = isset($bloc['accordion_body_padding']) ? intval($bloc['accordion_body_padding']) : 20;
$accordion_body_bg_color = $bloc['accordion_body_bg_color'] ?? '#ffffff';
$accordion_button_text_color = $bloc['accordion_button_text_color'] ?? 'dark';
$accordion_body_text_color = $bloc['accordion_body_text_color'] ?? 'dark';

// Icône accordion
$accordion_icon_html = $bloc['accordion_icon_html'] ?? '<i class="bi bi-chevron-down"></i>';

// Générer le CSS inline pour ce bloc accordion spécifique
$accordion_button_bg_css = '';
if ($accordion_button_bg_choice === 'custom') {
    $accordion_button_bg_css = esc_attr($accordion_button_bg_custom);
} else {
    // Convertir theme1 -> colorTheme1
    $theme_number = str_replace('theme', '', $accordion_button_bg_choice);
    $accordion_button_bg_css = 'var(--colorTheme' . $theme_number . ')';
}

$accordion_button_text_css = $accordion_button_text_color === 'light' ? 'white' : 'var(--colorText)';
$accordion_body_text_css = $accordion_body_text_color === 'light' ? 'white' : 'var(--colorText)';

$accordion_inline_style = "
<style>
#" . $accordion_id . " .accordion-button:not(.collapsed) {
    background-color: {$accordion_button_bg_css};
    color: {$accordion_button_text_css};
}
#" . $accordion_id . " .accordion-body {
    padding: {$accordion_body_padding}px;
    background-color: " . esc_attr($accordion_body_bg_color) . ";
}
#" . $accordion_id . " .accordion-body * {
    color: {$accordion_body_text_css};
}
</style>
";

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<?php echo $accordion_inline_style; ?>
<section class="bloc-section bloc-accordion <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $order_class; ?>">
            <div class="col-12 <?php echo $accordion_col_class; ?>">
                <div class="content-wrapper" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">

                    <?php if ($heading_text) : ?>
                        <<?php echo $heading_tag; ?> class="<?php echo $text_align_class . ' ' . $heading_text_class; ?>"<?php echo flexible_get_heading_style($heading_tag, $heading_font_size_override); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                    <?php endif; ?>

                    <?php if (!empty($accordion_items)) : ?>
                        <div class="accordion" id="<?php echo $accordion_id; ?>">
                            <?php foreach ($accordion_items as $index => $item) :
                                $item_id = 'collapse-' . $accordion_id . '-' . $index;
                                $item_title = $item['accordion_title'] ?? '';
                                $item_content = $item['accordion_content'] ?? '';
                                $is_open = ($first_item_open === 'open' && $index === 0);
                            ?>
                                <div class="accordion-item">
                                    <<?php echo $accordion_heading_tag; ?> class="accordion-header" id="heading-<?php echo $item_id; ?>">
                                        <button class="accordion-button <?php echo $is_open ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $item_id; ?>" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo $item_id; ?>">
                                            <?php echo esc_html($item_title); ?>
                                            <span class="accordion-icon"><?php echo wp_kses_post($accordion_icon_html); ?></span>
                                        </button>
                                    </<?php echo $accordion_heading_tag; ?>>
                                    <div id="<?php echo $item_id; ?>" class="accordion-collapse collapse <?php echo $is_open ? 'show' : ''; ?>" aria-labelledby="heading-<?php echo $item_id; ?>" data-bs-parent="#<?php echo $accordion_id; ?>">
                                        <div class="accordion-body">
                                            <?php echo wp_kses_post($item_content); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // CTA Button (optionnel)
                    $cta_link = $bloc['accordion_cta_link'] ?? null;
                    if ($cta_link && !empty($cta_link['url'])) :
                        $cta_margin_top = isset($bloc['accordion_cta_margin_top']) ? intval($bloc['accordion_cta_margin_top']) : 20;
                        $cta_align = $bloc['accordion_cta_align'] ?? 'center';
                    ?>
                        <div class="d-flex justify-content-<?php echo esc_attr($cta_align); ?>" style="margin-top: <?php echo $cta_margin_top; ?>px;">
                            <?php echo flexible_render_button(
                                $cta_link,
                                $bloc['accordion_cta_type'] ?? 'primary',
                                $bloc['accordion_cta_outline'] ?? false,
                                '',
                                $bloc['accordion_cta_text_color'] ?? ''
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
