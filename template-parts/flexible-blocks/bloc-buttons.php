<?php
/**
 * Bloc Buttons
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 30);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 30);

// Boutons
$buttons_group = $bloc['buttons_group'] ?? [];
$buttons_align = $bloc['buttons_align'] ?? 'center';

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-buttons <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (!empty($buttons_group)) : ?>
                    <div class="d-flex justify-content-<?php echo esc_attr($buttons_align); ?>">
                        <div class="btn-group" role="group">
                            <?php foreach ($buttons_group as $button) :
                                echo flexible_render_button(
                                    $button['link'] ?? null,
                                    $button['btn_type'] ?? 'primary',
                                    $button['btn_outline'] ?? false
                                );
                            endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
