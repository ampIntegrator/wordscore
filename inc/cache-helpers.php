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
