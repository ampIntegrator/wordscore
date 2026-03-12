<?php
/**
 * Bloc Colonnes Multiples
 *
 * @package Bootscore_Child
 */

// Background et padding de la section
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 0);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 0);

// Paramètres des content-wrapper
$content_padding = flexible_get_padding($bloc, 'content_padding', 20);
$content_bg_color_choice = $bloc['content_bg_color_choice'] ?? 'theme1';
if ($content_bg_color_choice === 'custom') {
    $content_bg_color = $bloc['content_bg_color_custom'] ?? '#ffffff';
} else {
    $theme_number = str_replace('theme', '', $content_bg_color_choice);
    $content_bg_color = 'var(--colorTheme' . $theme_number . ')';
}

// Ordre d'affichage : titre/icône ou icône/titre
$ordre_affichage = $bloc['ordre_affichage'] ?? 'titre_icone';

// Paramètres des icônes
$icone_mode = $bloc['icone_mode'] ?? 'nature';

// Couleur de l'icône
$icone_color_choice = $bloc['icone_color_choice'] ?? 'theme1';
if ($icone_color_choice === 'custom') {
    $icone_color = $bloc['icone_color_custom'] ?? '#000000';
} else {
    $theme_number = str_replace('theme', '', $icone_color_choice);
    $icone_color = 'var(--colorTheme' . $theme_number . ')';
}

// Taille de l'icône (toujours disponible)
$icone_font_size = isset($bloc['icone_font_size']) ? intval($bloc['icone_font_size']) : 40;

if ($icone_mode === 'cercle') {
    $icone_circle_size = isset($bloc['icone_circle_size']) ? intval($bloc['icone_circle_size']) : 80;

    // Couleur de fond du cercle
    $icone_circle_bg_choice = $bloc['icone_circle_bg_choice'] ?? 'theme1';
    if ($icone_circle_bg_choice === 'custom') {
        $icone_circle_bg_color = $bloc['icone_circle_bg_custom'] ?? '#f0f0f0';
    } else {
        $theme_number = str_replace('theme', '', $icone_circle_bg_choice);
        $icone_circle_bg_color = 'var(--colorTheme' . $theme_number . ')';
    }

    $icone_wrapper_style = "width: {$icone_circle_size}px; height: {$icone_circle_size}px; background-color: " . esc_attr($icone_circle_bg_color) . "; color: " . esc_attr($icone_color) . "; font-size: {$icone_font_size}px;";
    $icone_wrapper_class = 'icone-wrapper icone-cercle';
} else {
    $icone_wrapper_style = "font-size: {$icone_font_size}px; color: " . esc_attr($icone_color) . ";";
    $icone_wrapper_class = 'icone-wrapper icone-nature';
}

// Balise heading (commune à toutes les colonnes)
$heading_tag = flexible_get_heading_tag($bloc['heading_tag'] ?? 'h3', 'h3');

// Alignement du texte (commun à toutes les colonnes)
$text_align = $bloc['text_align'] ?? 'center';
$text_align_class = flexible_get_text_align_class($text_align);

// Taille du heading (commune à toutes les colonnes) - fonctionne comme override
$heading_font_size = isset($bloc['heading_font_size']) ? intval($bloc['heading_font_size']) : 0;

// Colonnes (répéteur)
$colonnes = $bloc['colonnes'] ?? [];

// Calculer la classe de colonne en fonction du nombre
$nombre_colonnes = count($colonnes);
$col_class_map = [
    2 => 'col-md-6',
    3 => 'col-md-4',
    4 => 'col-md-3'
];
$col_class = $col_class_map[$nombre_colonnes] ?? 'col-md-6';

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-colonnes-multiples <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row">
            <?php if ($colonnes) :
                foreach ($colonnes as $colonne) :
                    $heading_text = $colonne['heading_text'] ?? '';
                    $icone_html = $colonne['icone_html'] ?? '';
                    $texte = $colonne['texte'] ?? '';
            ?>
                <div class="col-12 <?php echo $col_class; ?>">
                    <div class="content-wrapper" style="padding: <?php echo $content_padding; ?>px; background-color: <?php echo esc_attr($content_bg_color); ?>;">
                        <?php if ($ordre_affichage === 'icone_titre') : ?>
                            <?php if ($icone_html) : ?>
                                <div class="<?php echo $icone_wrapper_class . ' ' . $text_align_class; ?>" style="<?php echo $icone_wrapper_style; ?>">
                                    <?php echo wp_kses_post($icone_html); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($heading_text) : ?>
                                <<?php echo $heading_tag; ?> class="<?php echo $text_align_class; ?>"<?php echo flexible_get_heading_style($heading_tag, $heading_font_size); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if ($heading_text) : ?>
                                <<?php echo $heading_tag; ?> class="<?php echo $text_align_class; ?>"<?php echo flexible_get_heading_style($heading_tag, $heading_font_size); ?>><?php echo esc_html($heading_text); ?></<?php echo $heading_tag; ?>>
                            <?php endif; ?>

                            <?php if ($icone_html) : ?>
                                <div class="<?php echo $icone_wrapper_class . ' ' . $text_align_class; ?>" style="<?php echo $icone_wrapper_style; ?>">
                                    <?php echo wp_kses_post($icone_html); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($texte) : ?>
                            <p><?php echo esc_html($texte); ?></p>
                        <?php endif; ?>

                        <?php
                        // Bouton (optionnel)
                        $btn_link = $colonne['btn_link'] ?? null;
                        if ($btn_link && !empty($btn_link['url'])) {
                            $btn_html = flexible_render_button(
                                $btn_link,
                                $colonne['btn_type'] ?? 'primary',
                                $colonne['btn_outline'] ?? false
                            );
                            // Ajouter la classe w-100
                            $btn_html = str_replace('class="btn ', 'class="btn w-100 ', $btn_html);
                            echo $btn_html;
                        }
                        ?>
                    </div>
                </div>
            <?php
                endforeach;
            endif; ?>
        </div>
    </div>
</section>
