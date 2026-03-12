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
}

// Vider le cache quand une option ACF est sauvegardée
add_action('acf/save_post', 'wordscore_clear_options_cache', 20);

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
