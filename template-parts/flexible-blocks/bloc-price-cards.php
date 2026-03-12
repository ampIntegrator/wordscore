<?php
/**
 * Bloc Price Cards
 *
 * @package Bootscore_Child
 */

// Background et padding de la section
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 30);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 30);

// Paramètres des cartes
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color_choice = $bloc['content_bg_color_choice'] ?? 'theme1';
if ($content_bg_color_choice === 'custom') {
    $content_bg_color = $bloc['content_bg_color_custom'] ?? '#ffffff';
} else {
    $theme_number = str_replace('theme', '', $content_bg_color_choice);
    $content_bg_color = 'var(--colorTheme' . $theme_number . ')';
}

// Alignement du texte
$text_align = $bloc['text_align'] ?? 'center';
$text_align_class = flexible_get_text_align_class($text_align);

// Boutons (communs à toutes les cartes)
$btn_type = $bloc['btn_type'] ?? 'primary';
$btn_outline = $bloc['btn_outline'] ?? false;
$btn_text_color = $bloc['btn_text_color'] ?? '';

// Cartes (répéteur)
$cards = $bloc['cards'] ?? [];
$nombre_cartes = count($cards);

// Classe de justification selon le nombre de cartes
$justify_class = ($nombre_cartes === 2) ? 'justify-content-between' : 'justify-content-center';

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-price-cards <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row <?php echo $justify_class; ?>">
            <?php if ($cards) :
                foreach ($cards as $card) :
                    $card_image_url = flexible_get_image_url($card['card_image'] ?? '');
                    $plan_name = $card['plan_name'] ?? '';
                    $price = $card['price'] ?? '';
                    $currency = $card['currency'] ?? '€';
                    $period = $card['period'] ?? '/mois';
                    $features = $card['features'] ?? [];
                    $btn_link = $card['btn_link'] ?? null;
                    $featured = $card['featured'] ?? false;
                    $featured_class = $featured ? 'featured' : '';
            ?>
                <div class="price-card-col">
                    <div class="price-card <?php echo $featured_class; ?>" style="--card-padding: <?php echo $content_padding; ?>px; padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">

                        <?php if ($card_image_url) : ?>
                            <div class="price-card-image" style="background-image: url('<?php echo esc_url($card_image_url); ?>');"></div>
                        <?php endif; ?>

                        <?php if ($plan_name) : ?>
                            <h3 class="<?php echo $text_align_class; ?>"><?php echo esc_html($plan_name); ?></h3>
                        <?php endif; ?>

                        <?php if ($price !== '') : ?>
                            <div class="price-display <?php echo $text_align_class; ?>">
                                <span class="price"><?php echo esc_html($price); ?></span>
                                <span class="currency"><?php echo esc_html($currency); ?></span>
                                <span class="period"><?php echo esc_html($period); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($features) : ?>
                            <ul class="features-list">
                                <?php foreach ($features as $feature) :
                                    $feature_text = $feature['feature_text'] ?? '';
                                    if ($feature_text) :
                                ?>
                                    <li><?php echo esc_html($feature_text); ?></li>
                                <?php endif; endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if ($btn_link && !empty($btn_link['url'])) :
                            $btn_html = flexible_render_button(
                                $btn_link,
                                $btn_type,
                                $btn_outline,
                                '',
                                $btn_text_color
                            );
                            // Ajouter la classe w-100
                            $btn_html = str_replace('class="btn ', 'class="btn w-100 ', $btn_html);
                            echo $btn_html;
                        endif; ?>

                    </div>
                </div>
            <?php
                endforeach;
            endif; ?>
        </div>
    </div>
</section>
