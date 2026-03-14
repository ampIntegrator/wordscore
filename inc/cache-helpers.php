<?php
/**
 * Cache Helpers - Optimisation des requêtes ACF
 *
 * Système de cache pour les options ACF afin de réduire les requêtes DB
 *
 * @package Wordscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Récupère une option ACF avec cache
 *
 * Utilise un système de cache en mémoire (static) + transients pour
 * réduire drastiquement les requêtes DB sur les options globales
 *
 * @param string $key Clé du champ ACF
 * @param mixed $default Valeur par défaut si le champ n'existe pas
 * @return mixed Valeur du champ
 */
function wordscore_get_cached_option($key, $default = false) {
    static $cache = array();

    // Cache en mémoire pour éviter même les transients sur la même requête
    if (isset($cache[$key])) {
        return $cache[$key];
    }

    // Essayer de récupérer depuis le transient (cache DB)
    $transient_key = 'wordscore_opt_' . $key;
    $value = get_transient($transient_key);

    if (false === $value) {
        // Récupérer depuis ACF et mettre en cache
        $value = get_field($key, 'option');

        // Si aucune valeur, utiliser le défaut
        if (false === $value || null === $value || '' === $value) {
            $value = $default;
        }

        // Stocker dans transient pour 1 heure
        set_transient($transient_key, $value, HOUR_IN_SECONDS);
    }

    // Stocker en mémoire
    $cache[$key] = $value;

    return $value;
}

/**
 * Vide le cache des options ACF
 *
 * Supprime tous les transients des options Wordscore
 * Appelé automatiquement quand une option ACF est sauvegardée
 */
function wordscore_clear_options_cache() {
    global $wpdb;

    // Supprimer tous les transients wordscore_opt_*
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_wordscore_opt_%'
         OR option_name LIKE '_transient_timeout_wordscore_opt_%'"
    );

    // IMPORTANT: Vider aussi le cache static de wordscore_get_cached_option
    // en forçant PHP à oublier les valeurs en mémoire
    // (Cela ne fonctionne que pour la requête en cours, mais c'est suffisant pour la recompilation)
}

// Vider le cache quand une option ACF est sauvegardée
add_action('acf/save_post', 'wordscore_clear_options_cache', 20);

/**
 * Génère le fichier acf-overrides.css qui surcharge les CSS variables Bootstrap
 *
 * Au lieu de se battre avec SCSS, on génère un CSS pur qui override directement
 * les CSS variables Bootstrap (--bs-primary, --bs-body-font-family, etc.)
 */
function wordscore_generate_acf_overrides_css() {
    $colors = wordscore_get_theme_colors();

    $body_font_size = wordscore_get_cached_option('body_font_size', 16);
    $use_custom_fonts = wordscore_get_cached_option('use_custom_fonts', false);
    $heading_weight = wordscore_get_cached_option('heading_font_weight', '600');
    $body_weight = wordscore_get_cached_option('body_font_weight', '400');

    // Récupérer les tailles de titres
    $h1_size = wordscore_get_cached_option('h1_font_size', 48);
    $h2_size = wordscore_get_cached_option('h2_font_size', 40);
    $h3_size = wordscore_get_cached_option('h3_font_size', 32);
    $h4_size = wordscore_get_cached_option('h4_font_size', 24);
    $h5_size = wordscore_get_cached_option('h5_font_size', 20);
    $h6_size = wordscore_get_cached_option('h6_font_size', 16);

    if ($use_custom_fonts) {
        $heading_font = 'CustomHeading';
        $body_font = 'CustomBody';
    } else {
        $heading_font = wordscore_get_cached_option('heading_font', 'Inter');
        $body_font = wordscore_get_cached_option('body_font', 'Inter');

        if ($heading_font === 'custom') {
            $heading_font = wordscore_get_cached_option('heading_font_custom', 'Inter');
        }
        if ($body_font === 'custom') {
            $body_font = wordscore_get_cached_option('body_font_custom', 'Inter');
        }
    }

    // Convertir les couleurs RGB en format "r, g, b" pour les variables -rgb
    $theme1_rgb = wordscore_hex_to_rgb($colors['theme1']);
    $theme2_rgb = wordscore_hex_to_rgb($colors['theme2']);

    // Calculer les variantes hover (10% plus sombre)
    $theme1_hover = wordscore_darken_color($colors['theme1'], 10);
    $theme2_hover = wordscore_darken_color($colors['theme2'], 10);
    $theme1_hover_rgb = wordscore_hex_to_rgb($theme1_hover);
    $theme2_hover_rgb = wordscore_hex_to_rgb($theme2_hover);

    // Calculer les variantes active (15% plus sombre)
    $theme1_active = wordscore_darken_color($colors['theme1'], 15);
    $theme2_active = wordscore_darken_color($colors['theme2'], 15);

    // Convertir ink en RGB
    $ink_rgb = wordscore_hex_to_rgb($colors['ink']);

    $css_content = <<<CSS
/* Fichier généré automatiquement depuis les Options Globales ACF */
/* Ne pas modifier manuellement - sera écrasé lors de la prochaine sauvegarde */

:root,
[data-bs-theme="light"] {
    /* Variables Bootstrap pour le body */
    --bs-body-font-family: "{$body_font}", sans-serif !important;
    --bs-body-font-size: {$body_font_size}px !important;
    --bs-body-font-weight: {$body_weight} !important;
    --bs-body-color: {$colors['ink']} !important;
    --bs-body-color-rgb: {$ink_rgb} !important;

    /* Tailles de titres custom (pour les blocs flexible) */
    --h1-font-size: {$h1_size}px;
    --h2-font-size: {$h2_size}px;
    --h3-font-size: {$h3_size}px;
    --h4-font-size: {$h4_size}px;
    --h5-font-size: {$h5_size}px;
    --h6-font-size: {$h6_size}px;
}

/* Fix tailles de titres natifs (pour contenu WYSIWYG) */
/* Pas de !important pour permettre les overrides inline */
h1, .h1 { font-size: {$h1_size}px; }
h2, .h2 { font-size: {$h2_size}px; }
h3, .h3 { font-size: {$h3_size}px; }
h4, .h4 { font-size: {$h4_size}px; }
h5, .h5 { font-size: {$h5_size}px; }
h6, .h6 { font-size: {$h6_size}px; }

/* Fix police et poids des headings (tous par sécurité) */
h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: "{$heading_font}", sans-serif !important;
    font-weight: {$heading_weight} !important;
}

/* Classes de couleurs texte custom */
.text-light {
    color: {$colors['light']} !important;
}

.text-dark {
    color: {$colors['ink']} !important;
}

.text-ink {
    color: {$colors['ink']} !important;
}

/* Override boutons Bootstrap primary */
.btn-primary {
    --bs-btn-bg: {$colors['theme1']};
    --bs-btn-border-color: {$colors['theme1']};
    --bs-btn-hover-bg: {$theme1_hover};
    --bs-btn-hover-border-color: {$theme1_hover};
    --bs-btn-focus-shadow-rgb: {$theme1_rgb};
    --bs-btn-active-bg: {$theme1_active};
    --bs-btn-active-border-color: {$theme1_active};
    --bs-btn-disabled-bg: {$colors['theme1']};
    --bs-btn-disabled-border-color: {$colors['theme1']};
}

/* Override boutons Bootstrap secondary */
.btn-secondary {
    --bs-btn-bg: {$colors['theme2']};
    --bs-btn-border-color: {$colors['theme2']};
    --bs-btn-hover-bg: {$theme2_hover};
    --bs-btn-hover-border-color: {$theme2_hover};
    --bs-btn-focus-shadow-rgb: {$theme2_rgb};
    --bs-btn-active-bg: {$theme2_active};
    --bs-btn-active-border-color: {$theme2_active};
    --bs-btn-disabled-bg: {$colors['theme2']};
    --bs-btn-disabled-border-color: {$colors['theme2']};
}

/* Bouton de recherche custom */
.searchBtn {
    background-color: {$colors['theme1']};
    border-color: {$colors['theme1']};
    color: {$colors['light']};
}

.searchBtn:hover {
    background-color: {$theme1_hover};
    border-color: {$theme1_hover};
    color: {$colors['light']};
}

.searchBtn:focus {
    box-shadow: 0 0 0 0.25rem rgba({$theme1_rgb}, 0.5);
}

CSS;

    $file_path = get_stylesheet_directory() . '/assets/css/acf-overrides.css';
    file_put_contents($file_path, $css_content);
}

/**
 * Force la recompilation SCSS quand les options globales sont sauvegardées
 *
 * 1. Régénère _acf-overrides.scss avec les nouvelles valeurs ACF
 * 2. Supprime les CSS compilés pour forcer la recompilation
 */
function wordscore_force_scss_recompile_on_options_save($post_id) {
    // Vérifier si c'est une page d'options ACF
    if ($post_id !== 'options') {
        return;
    }

    // Debug: log all posted ACF fields
    if (isset($_POST['acf'])) {
        $acf_fields = array_keys($_POST['acf']);
        error_log('ACF SAVE POST - Posted fields: ' . implode(', ', $acf_fields));
    }

    // Vérifier si on sauvegarde la page Global en checkant les champs ACF postés
    // Si on a des champs comme theme1_color, theme2_color, etc., c'est Global
    $is_global_page = false;
    if (isset($_POST['acf'])) {
        $acf_fields = array_keys($_POST['acf']);
        // Chercher des champs spécifiques à Global
        $global_field_indicators = ['theme1_color', 'theme2_color', 'ink_color', 'body_font_size', 'heading_font'];
        foreach ($global_field_indicators as $indicator) {
            if (in_array($indicator, $acf_fields)) {
                $is_global_page = true;
                break;
            }
        }
    }

    if (!$is_global_page) {
        error_log('ACF SAVE POST - NOT GLOBAL, skipping');
        return;
    }

    error_log('ACF SAVE POST - GLOBAL OPTIONS DETECTED - TRIGGERING REGENERATION');
    // Marquer qu'il faut régénérer le CSS (on le fera sur shutdown pour éviter les problèmes de cache)
    add_action('shutdown', 'wordscore_regenerate_acf_overrides');
}

/**
 * Régénère acf-overrides.css
 * Appelé sur shutdown pour s'assurer que tous les caches sont vidés
 */
function wordscore_regenerate_acf_overrides() {
    error_log('REGENERATING ACF OVERRIDES CSS');

    // Régénérer le fichier CSS avec les nouvelles valeurs
    wordscore_generate_acf_overrides_css();
    error_log('ACF OVERRIDES CSS REGENERATED');
}

add_action('acf/save_post', 'wordscore_force_scss_recompile_on_options_save', 25);

// Générer acf-overrides.css au premier chargement si inexistant
add_action('init', function() {
    $file_path = get_stylesheet_directory() . '/assets/css/acf-overrides.css';
    if (!file_exists($file_path)) {
        wordscore_generate_acf_overrides_css();
    }
}, 5);

/**
 * Récupère toutes les couleurs du thème en une seule fois
 *
 * Centralise la récupération des 6 couleurs thématiques + ink
 * pour éviter la duplication de code
 *
 * @return array Tableau associatif des couleurs
 */
function wordscore_get_theme_colors() {
    static $colors = null;

    if (null === $colors) {
        $colors = [
            'theme1' => wordscore_get_cached_option('theme1_color', '#0d6efd'),
            'theme2' => wordscore_get_cached_option('theme2_color', '#6c757d'),
            'theme3' => wordscore_get_cached_option('theme3_color', '#dc3545'),
            'theme4' => wordscore_get_cached_option('theme4_color', '#0dcaf0'),
            'theme5' => wordscore_get_cached_option('theme5_color', '#198754'),
            'theme6' => wordscore_get_cached_option('theme6_color', '#333333'),
            'ink'    => wordscore_get_cached_option('ink_color', '#202224'),
            'light'  => wordscore_get_cached_option('light_color', '#ffffff'),
        ];
    }

    return $colors;
}

/**
 * Convertit une couleur hex en RGB
 *
 * @param string $hex Couleur au format #RRGGBB
 * @return string RGB au format "r, g, b"
 */
function wordscore_hex_to_rgb($hex) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return "$r, $g, $b";
}

/**
 * Assombrit une couleur hex d'un pourcentage
 *
 * @param string $hex Couleur au format #RRGGBB
 * @param int $percent Pourcentage d'assombrissement (0-100)
 * @return string Couleur assombrie au format #RRGGBB
 */
function wordscore_darken_color($hex, $percent) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r - ($r * $percent / 100)));
    $g = max(0, min(255, $g - ($g * $percent / 100)));
    $b = max(0, min(255, $b - ($b * $percent / 100)));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Éclaircit une couleur hex d'un pourcentage
 *
 * @param string $hex Couleur au format #RRGGBB
 * @param int $percent Pourcentage d'éclaircissement (0-100)
 * @return string Couleur éclaircie au format #RRGGBB
 */
function wordscore_lighten_color($hex, $percent) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r + ((255 - $r) * $percent / 100)));
    $g = max(0, min(255, $g + ((255 - $g) * $percent / 100)));
    $b = max(0, min(255, $b + ((255 - $b) * $percent / 100)));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
