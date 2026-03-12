<?php
/**
 * Bloc Titre
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 0);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 0);

// Contenu
$heading_tag = flexible_get_heading_tag($bloc['heading_tag'] ?? 'h2', 'h2');
$heading_text = $bloc['heading_text'] ?? '';
$heading_font_size_override = isset($bloc['heading_font_size_override']) ? intval($bloc['heading_font_size_override']) : 0;

// Largeur et alignement de la colonne
$col_class = flexible_get_col_class($bloc['col_width'] ?? '50', 'col-md-6');
$col_align = $bloc['col_align'] ?? 'center';
$align_class_map = [
    'left' => 'justify-content-start',
    'center' => 'justify-content-center',
    'right' => 'justify-content-end'
];
$align_class = $align_class_map[$col_align] ?? 'justify-content-center';

// Content-wrapper
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';
$content_text_class = flexible_get_text_color_class($bloc['content_text'] ?? 'dark');

// Alignement du texte
$text_align_class = flexible_get_text_align_class($bloc['text_align'] ?? 'left');

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-titre <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $align_class; ?>">
            <div class="col-12 <?php echo $col_class; ?>">
                <div class="content-wrapper <?php echo $content_text_class . ' ' . $text_align_class; ?>" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">
                    <?php if ($heading_text) : ?>
                        <<?php echo $heading_tag; ?><?php echo flexible_get_heading_style($heading_tag, $heading_font_size_override); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
