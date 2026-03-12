<?php
/**
 * Bloc Image Texte
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 0);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 0);

// Largeur de la colonne texte
$text_width = $bloc['text_width'] ?? '50';

// Mapping des largeurs vers les classes Bootstrap
$text_col_map = [
    '25' => 'col-md-3',
    '33' => 'col-md-4',
    '42' => 'col-md-5',
    '50' => 'col-md-6',
    '58' => 'col-md-7',
    '66' => 'col-md-8',
    '75' => 'col-md-9'
];
$image_col_map = [
    '25' => 'col-md-9',
    '33' => 'col-md-8',
    '42' => 'col-md-7',
    '50' => 'col-md-6',
    '58' => 'col-md-5',
    '66' => 'col-md-4',
    '75' => 'col-md-3'
];

$text_col_class = $text_col_map[$text_width] ?? 'col-md-6';
$image_col_class = $image_col_map[$text_width] ?? 'col-md-6';

// Image
$image_url = flexible_get_image_url($bloc['image_col'] ?? '');
$image_min_height = isset($bloc['image_min_height']) ? max(200, min(600, intval($bloc['image_min_height']))) : 300;

// Content-wrapper
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';
$content_text_class = flexible_get_text_color_class($bloc['content_text'] ?? 'dark');

// Contenu
$content = $bloc['content'] ?? '';

// Bouton
$btn_link = $bloc['btn_link'] ?? null;
$btn_type = $bloc['btn_type'] ?? 'primary';
$btn_outline = $bloc['btn_outline'] ?? false;
$btn_text_color = $bloc['btn_text_color'] ?? '';

// Ordre
$order_class = flexible_get_order_class($bloc['inverser'] ?? false);

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-image-texte <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $order_class; ?>">
            <div class="col-12 <?php echo $image_col_class; ?>">
                <?php if ($image_url) : ?>
                    <div class="image-wrapper" style="background-image: url('<?php echo esc_url($image_url); ?>'); min-height: <?php echo $image_min_height; ?>px;"></div>
                <?php endif; ?>
            </div>
            <div class="col-12 <?php echo $text_col_class; ?>">
                <div class="content-wrapper <?php echo $content_text_class; ?>" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">
                    <?php echo wp_kses_post($content); ?>

                    <?php if ($btn_link && !empty($btn_link['url'])) :
                        echo flexible_render_button(
                            $btn_link,
                            $btn_type,
                            $btn_outline,
                            '',
                            $btn_text_color
                        );
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
