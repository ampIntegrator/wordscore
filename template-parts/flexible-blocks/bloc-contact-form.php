<?php
/**
 * Bloc Contact
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 0);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 0);

// Largeur du formulaire
$form_width = $bloc['form_width'] ?? '50';
$form_col_class = flexible_get_col_class($form_width, 'col-md-6');
$companion_col_class = flexible_get_image_col_class($form_width);

// Titre
$heading_tag = flexible_get_heading_tag($bloc['heading_tag'] ?? 'h2', 'h2');
$heading_text = $bloc['heading_text'] ?? '';
$heading_font_size_override = isset($bloc['heading_font_size_override']) ? intval($bloc['heading_font_size_override']) : 0;
$text_align_class = flexible_get_text_align_class($bloc['text_align'] ?? 'left');

// Texte
$texte = $bloc['texte'] ?? '';

// Content-wrapper du formulaire
$form_padding = flexible_get_padding($bloc, 'form_padding', 20);
$form_bg_color = $bloc['form_bg_color'] ?? '#ffffff';

// Shortcode CF7
$cf7_shortcode = $bloc['cf7_shortcode'] ?? '';

// Type de colonne complémentaire
$companion_type = $bloc['companion_type'] ?? 'image';

// Colonne complémentaire - Image
$companion_image_url = flexible_get_image_url($bloc['companion_image'] ?? '');
$image_min_height = isset($bloc['image_min_height']) ? max(200, min(600, intval($bloc['image_min_height']))) : 300;

// Colonne complémentaire - Contenu
$companion_content = $bloc['companion_content'] ?? '';
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';
$content_text_class = flexible_get_text_color_class($bloc['content_text'] ?? 'dark');

// Ordre
$order_class = flexible_get_order_class($bloc['inverser'] ?? false);

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-contact <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $order_class; ?>">
            <div class="col-12 <?php echo $form_col_class; ?>">
                <div class="content-wrapper" style="padding: <?php echo $form_padding; ?>px; background-color: <?php echo esc_attr($form_bg_color); ?>;">

                    <?php if ($heading_text) : ?>
                        <<?php echo $heading_tag; ?> class="<?php echo $text_align_class; ?>"<?php echo flexible_get_heading_style($heading_tag, $heading_font_size_override); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                    <?php endif; ?>

                    <?php if ($texte) : ?>
                        <p class="<?php echo $text_align_class; ?>"><?php echo esc_html($texte); ?></p>
                    <?php endif; ?>

                    <?php if ($cf7_shortcode) : ?>
                        <?php echo do_shortcode($cf7_shortcode); ?>
                    <?php else : ?>
                        <p class="text-muted">Veuillez ajouter un shortcode Contact Form 7</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 <?php echo $companion_col_class; ?>">
                <?php if ($companion_type === 'image' && $companion_image_url) : ?>
                    <div class="image-wrapper" style="background-image: url('<?php echo esc_url($companion_image_url); ?>'); min-height: <?php echo $image_min_height; ?>px;"></div>
                <?php elseif ($companion_type === 'content' && $companion_content) : ?>
                    <div class="content-wrapper <?php echo $content_text_class; ?>" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">
                        <?php echo wp_kses_post($companion_content); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
