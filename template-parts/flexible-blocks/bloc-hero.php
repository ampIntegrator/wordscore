<?php
/**
 * Bloc Hero
 *
 * @package Bootscore_Child
 */

// Dimensions et mode
$col_width = $bloc['col_width'] ?? '50';
$col_align = $bloc['col_align'] ?? 'center';
$height_mode = $bloc['height_mode'] ?? 'padding';
$padding_top = ($height_mode === 'padding') ? flexible_get_padding($bloc, 'padding_top', 60) : 60;
$padding_bottom = ($height_mode === 'padding') ? flexible_get_padding($bloc, 'padding_bottom', 60) : 60;

// Classes colonnes
$col_class = flexible_get_col_class($col_width, 'col-md-6');

// Alignement de la colonne
$col_align_class_map = [
    'left' => 'justify-content-start',
    'center' => 'justify-content-center',
    'right' => 'justify-content-end'
];
$col_align_class = $col_align_class_map[$col_align] ?? 'justify-content-center';

// Background (avec gestion vidéo)
$background = flexible_get_background($bloc, true);
$bg_video_html = flexible_get_video_background($bloc);

// Logo/Image
$logo_image = flexible_get_image_url($bloc['logo_image'] ?? '');
$logo_height = isset($bloc['logo_height']) ? intval($bloc['logo_height']) : 80;
$logo_align = $bloc['logo_align'] ?? 'center';
$logo_align_class_map = [
    'left' => 'text-start',
    'center' => 'text-center',
    'right' => 'text-end'
];
$logo_align_class = $logo_align_class_map[$logo_align] ?? 'text-center';

// Contenu
$heading_tag = flexible_get_heading_tag($bloc['heading_tag'] ?? 'h1', 'h1');
$heading_text = $bloc['heading_text'] ?? '';
$heading_text_color = flexible_get_text_color_class($bloc['heading_text_color'] ?? 'dark');
$heading_font_size_override = isset($bloc['heading_font_size_override']) ? intval($bloc['heading_font_size_override']) : 0;
$paragraph = $bloc['paragraph'] ?? '';
$paragraph_text_color = flexible_get_text_color_class($bloc['paragraph_text_color'] ?? 'dark');
$content_padding = flexible_get_padding($bloc, 'content_padding', 30);
$content_bg_color = $bloc['content_bg_color'] ?? '#ffffff';

// Boutons
$buttons = $bloc['buttons'] ?? [];
$btn_size = $bloc['btn_size'] ?? '';

// Margins top
$heading_margin_top = isset($bloc['heading_margin_top']) ? intval($bloc['heading_margin_top']) : 20;
$paragraph_margin_top = isset($bloc['paragraph_margin_top']) ? intval($bloc['paragraph_margin_top']) : 15;
$btn_margin_top = isset($bloc['btn_margin_top']) ? intval($bloc['btn_margin_top']) : 20;

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Classes et styles
$height_class = $height_mode === 'fullscreen' ? 'hero-fullscreen' : '';
$style_attr = $height_mode === 'fullscreen'
    ? ' style="height: 100vh;"'
    : flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-hero <?php echo $background['class'] . ' ' . $height_class; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <?php echo $bg_video_html; ?>
    <div class="container h-100">
        <div class="row <?php echo $col_align_class; ?> h-100 align-items-center">
            <div class="col-12 <?php echo $col_class; ?>">
                <div class="content-wrapper text-center" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">
                    <?php if ($logo_image) : ?>
                        <div class="hero-logo <?php echo $logo_align_class; ?>">
                            <img src="<?php echo esc_url($logo_image); ?>" alt="Logo" style="height: <?php echo $logo_height; ?>px; width: auto; margin-bottom: 0;">
                        </div>
                    <?php endif; ?>

                    <?php if ($heading_text) :
                        $heading_style = flexible_get_heading_style($heading_tag, $heading_font_size_override);
                        $heading_style_attr = $heading_style ? str_replace(' style="', '', rtrim($heading_style, '"')) : '';
                        $full_style = 'margin-top: ' . $heading_margin_top . 'px; margin-bottom: 0;' . ($heading_style_attr ? ' ' . $heading_style_attr : '');
                    ?>
                        <<?php echo $heading_tag; ?> class="<?php echo $heading_text_color; ?>" style="<?php echo $full_style; ?>"><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                    <?php endif; ?>

                    <?php if ($paragraph) : ?>
                        <p class="<?php echo $paragraph_text_color; ?>" style="margin-top: <?php echo $paragraph_margin_top; ?>px; margin-bottom: 0;"><?php echo esc_html($paragraph); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($buttons)) : ?>
                        <div class="hero-buttons" style="display: flex; justify-content: center; gap: 20px; margin-top: <?php echo $btn_margin_top; ?>px;">
                            <?php foreach ($buttons as $button) :
                                echo flexible_render_button(
                                    $button['link'] ?? null,
                                    $button['btn_type'] ?? 'primary',
                                    $button['btn_outline'] ?? false,
                                    $btn_size
                                );
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
