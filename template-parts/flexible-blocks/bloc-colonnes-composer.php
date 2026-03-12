<?php
/**
 * Bloc Colonnes Composer
 *
 * @package Bootscore_Child
 */

// Background et padding
$background = flexible_get_background($bloc, false);
$padding_top = flexible_get_padding($bloc, 'padding_top', 30);
$padding_bottom = flexible_get_padding($bloc, 'padding_bottom', 30);

// ID de section
$section_id = !empty($bloc['section_id']) ? ' id="' . esc_attr($bloc['section_id']) . '"' : '';

// Preset de colonnes
$preset = $bloc['preset_colonnes'] ?? '6-6';

// Convertir le preset en tailles de colonnes Bootstrap
$col_sizes = explode('-', $preset);
$expected_cols = count($col_sizes);

// Content wrapper settings (commun à toutes les colonnes)
$content_padding = isset($bloc['content_padding']) ? intval($bloc['content_padding']) : 20;
$content_bg_color_choice = $bloc['content_bg_color_choice'] ?? 'transparent';
if ($content_bg_color_choice === 'custom') {
    $content_bg_color = $bloc['content_bg_color_custom'] ?? '#ffffff';
} elseif ($content_bg_color_choice === 'transparent') {
    $content_bg_color = 'transparent';
} else {
    $theme_number = str_replace('theme', '', $content_bg_color_choice);
    $content_bg_color = 'var(--colorTheme' . $theme_number . ')';
}
$content_text = $bloc['content_text'] ?? 'dark';

// Classes et styles du content-wrapper
$content_text_class = $content_text === 'light' ? 'text-light' : 'text-dark';
$content_wrapper_style = 'padding: ' . $content_padding . 'px; background-color: ' . esc_attr($content_bg_color) . ';';

// Colonnes (répéteur)
$colonnes = $bloc['colonnes'] ?? [];

// Style
$style_attr = flexible_get_padding_style($padding_top, $padding_bottom, $background['style']);
?>
<section class="bloc-section bloc-colonnes-composer <?php echo $background['class']; ?>"<?php echo $style_attr; ?><?php echo $section_id; ?>>
    <div class="container">
        <div class="row">
            <?php
            // Boucle sur le nombre de colonnes défini par le preset
            for ($i = 0; $i < $expected_cols; $i++) :
                // Récupérer la taille de colonne selon le preset
                $col_size = isset($col_sizes[$i]) ? intval($col_sizes[$i]) : 12;
                $col_class = 'col-lg-' . $col_size;

                // Récupérer la colonne du repeater si elle existe
                $colonne = isset($colonnes[$i]) ? $colonnes[$i] : null;

                // Type de contenu
                $content = '';
                if ($colonne) {
                    $content_type = $colonne['content_type'] ?? 'wysiwyg';

                    switch ($content_type) {
                        case 'wysiwyg':
                            $content = $colonne['wysiwyg_content'] ?? '';
                            break;
                        case 'shortcode':
                            $shortcode = $colonne['shortcode_content'] ?? '';
                            $content = !empty($shortcode) ? do_shortcode($shortcode) : '';
                            break;
                        case 'html':
                            $content = $colonne['html_content'] ?? '';
                            break;
                    }
                } else {
                    // Si la colonne n'existe pas dans le repeater, afficher un message pour les admins
                    if (is_user_logged_in()) {
                        $content = '<p><em>Colonne ' . ($i + 1) . ' : ajoutez du contenu dans le repeater</em></p>';
                    }
                }
            ?>
                <div class="<?php echo $col_class; ?>">
                    <div class="content-wrapper <?php echo $content_text_class; ?>" style="<?php echo $content_wrapper_style; ?>">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
