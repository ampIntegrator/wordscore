<?php
/**
 * Bloc Carousel avec diapositives
 *
 * @package Wordscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Récupérer les données du bloc
$carousel_id = !empty($bloc['carousel_id']) ? sanitize_title($bloc['carousel_id']) : 'carousel-' . uniqid();
$height_type = $bloc['height_type'] ?? 'custom';
$height_custom = intval($bloc['height_custom'] ?? 500);
$carousel_width = $bloc['carousel_width'] ?? 'full';

// Content wrapper
$content_width = $bloc['content_width'] ?? '50';
$content_align = $bloc['content_align'] ?? 'center';
$content_valign = $bloc['content_valign'] ?? 'center';
$content_text_align = $bloc['content_text_align'] ?? 'center';
$content_bg = $bloc['content_bg'] ?? 'theme1';
$content_bg_custom = $bloc['content_bg_custom'] ?? '';
$content_padding = intval($bloc['content_padding'] ?? 30);

// Contrôles
$show_controls = !empty($bloc['show_controls']);
$show_indicators = !empty($bloc['show_indicators']);
$autoplay = !empty($bloc['autoplay']);
$interval = $autoplay ? intval($bloc['interval'] ?? 5000) : false;

// Slides
$slides = $bloc['slides'] ?? [];

if (empty($slides)) {
    return; // Pas de slides, on n'affiche rien
}

// Boutons (globaux au carousel)
$buttons = $bloc['buttons'] ?? [];

// Mapping largeurs
$col_class_map = [
    '25' => 'col-12 col-lg-3',
    '33' => 'col-12 col-lg-4',
    '50' => 'col-12 col-lg-6',
    '66' => 'col-12 col-lg-8'
];
$col_class = $col_class_map[$content_width] ?? 'col-12 col-lg-6';

// Alignement horizontal
$justify_class_map = [
    'start' => 'justify-content-start',
    'center' => 'justify-content-center',
    'end' => 'justify-content-end'
];
$justify_class = $justify_class_map[$content_align] ?? 'justify-content-center';

// Alignement vertical
$valign_class_map = [
    'start' => 'align-items-start',
    'center' => 'align-items-center',
    'end' => 'align-items-end'
];
$valign_class = $valign_class_map[$content_valign] ?? 'align-items-center';

// Alignement interne du texte
$text_align_class_map = [
    'start' => 'text-start',
    'center' => 'text-center',
    'end' => 'text-end'
];
$text_align_class = $text_align_class_map[$content_text_align] ?? 'text-center';

// Background content-wrapper
$content_bg_style = '';
$content_bg_class = '';
if ($content_bg === 'transparent') {
    $content_bg_class = 'bg-transparent';
} elseif ($content_bg === 'custom' && !empty($content_bg_custom)) {
    $content_bg_style = 'background-color: ' . esc_attr($content_bg_custom) . ';';
} else {
    $content_bg_class = 'bg-' . esc_attr($content_bg);
}

// Hauteur carousel
$carousel_height_style = ($height_type === '100vh') ? 'height: 100vh;' : "height: {$height_custom}px;";

// Récupérer les icônes depuis Global Options
$icon_left = wordscore_get_cached_option('carousel_arrow_left_icon', '<i class="bi bi-arrow-left-circle"></i>');
$icon_right = wordscore_get_cached_option('carousel_arrow_right_icon', '<i class="bi bi-arrow-right-circle"></i>');

// Wrapper class
$wrapper_class = ($carousel_width === 'container') ? 'container' : 'container-fluid px-0';
?>

<section id="<?= esc_attr($carousel_id); ?>" class="bloc-carousel">
    <div class="<?= esc_attr($wrapper_class); ?>">
        <div id="<?= esc_attr($carousel_id); ?>-slides" class="carousel slide" data-bs-ride="<?= $autoplay ? 'carousel' : 'false'; ?>"<?= $interval ? ' data-bs-interval="' . esc_attr($interval) . '"' : ''; ?>>

            <!-- Indicators / Pagination -->
            <?php if ($show_indicators) : ?>
            <div class="carousel-indicators">
                <?php foreach ($slides as $index => $slide) : ?>
                <button type="button"
                        data-bs-target="#<?= esc_attr($carousel_id); ?>-slides"
                        data-bs-slide-to="<?= esc_attr($index); ?>"
                        <?= $index === 0 ? 'class="active" aria-current="true"' : ''; ?>
                        aria-label="Slide <?= esc_attr($index + 1); ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Slides -->
            <div class="carousel-inner">
                <?php foreach ($slides as $index => $slide) :
                    $title = $slide['title'] ?? '';
                    $title_tag = $slide['title_tag'] ?? 'h2';
                    $text = $slide['text'] ?? '';
                    $bg_image = flexible_get_image_url($slide['bg_image'] ?? null);
                    $overlay = intval($slide['overlay'] ?? 50);
                    $text_color = $slide['text_color'] ?? 'light';

                    $text_color_class = 'text-' . esc_attr($text_color);
                    $active_class = ($index === 0) ? ' active' : '';

                    $slide_style = $carousel_height_style;
                    if ($bg_image) {
                        $slide_style .= " background-image: url('" . esc_url($bg_image) . "'); background-size: cover; background-position: center;";
                    }
                ?>
                <div class="carousel-item<?= $active_class; ?>" style="<?= $slide_style; ?>">
                    <?php if ($bg_image && $overlay > 0) : ?>
                    <div class="carousel-overlay" style="background-color: rgba(0,0,0,<?= $overlay / 100; ?>);"></div>
                    <?php endif; ?>

                    <div class="carousel-content h-100">
                        <?php if ($carousel_width === 'full') : ?>
                        <div class="container h-100">
                        <?php endif; ?>
                            <div class="row h-100 <?= esc_attr($justify_class); ?> <?= esc_attr($valign_class); ?>">
                                <div class="<?= esc_attr($col_class); ?>">
                                    <div class="content-wrapper <?= esc_attr($content_bg_class); ?> <?= esc_attr($text_color_class); ?> <?= esc_attr($text_align_class); ?>"
                                         style="padding: <?= esc_attr($content_padding); ?>px;<?= $content_bg_style; ?>">

                                        <?php if ($title) : ?>
                                        <<?= esc_attr($title_tag); ?> class="carousel-title">
                                            <?= esc_html($title); ?>
                                        </<?= esc_attr($title_tag); ?>>
                                        <?php endif; ?>

                                        <?php if ($text) : ?>
                                        <p class="carousel-text"><?= nl2br(esc_html($text)); ?></p>
                                        <?php endif; ?>

                                        <?php if (!empty($buttons)) : ?>
                                        <div class="carousel-buttons" style="display: flex; justify-content: <?= $content_text_align === 'start' ? 'flex-start' : ($content_text_align === 'end' ? 'flex-end' : 'center'); ?>; gap: 15px; flex-wrap: wrap;">
                                            <?php foreach ($buttons as $button) :
                                                echo flexible_render_button(
                                                    $button['link'] ?? null,
                                                    $button['btn_type'] ?? 'primary',
                                                    $button['btn_outline'] ?? false,
                                                    '',
                                                    $button['btn_text_color'] ?? ''
                                                );
                                            endforeach; ?>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php if ($carousel_width === 'full') : ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Controls / Flèches -->
            <?php if ($show_controls && count($slides) > 1) : ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#<?= esc_attr($carousel_id); ?>-slides" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"><?= wp_kses_post($icon_left); ?></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?= esc_attr($carousel_id); ?>-slides" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"><?= wp_kses_post($icon_right); ?></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>

        </div>
    </div>
</section>
