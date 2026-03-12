<?php
/**
 * TGM Plugin Activation Configuration
 *
 * Recommande l'installation de Secure Custom Fields (SCF)
 *
 * @package Wordscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

require_once get_stylesheet_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'wordscore_register_required_plugins');

/**
 * Enregistrer les plugins requis et recommandés
 */
function wordscore_register_required_plugins() {
    $plugins = array(
        // Secure Custom Fields (SCF) - REQUIS
        array(
            'name'               => 'Secure Custom Fields',
            'slug'               => 'secure-custom-fields',
            'required'           => true,
            'version'            => '6.0.0',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        // Contact Form 7 - RECOMMANDÉ
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'wordscore',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'page_title'                      => __('Installer les plugins requis', 'wordscore'),
            'menu_title'                      => __('Installer les plugins', 'wordscore'),
            'installing'                      => __('Installation du plugin: %s', 'wordscore'),
            'updating'                        => __('Mise à jour du plugin: %s', 'wordscore'),
            'oops'                            => __('Une erreur s\'est produite avec l\'API du plugin.', 'wordscore'),
            'notice_can_install_required'     => _n_noop(
                'Ce thème requiert le plugin suivant: %1$s.',
                'Ce thème requiert les plugins suivants: %1$s.',
                'wordscore'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'Ce thème recommande le plugin suivant: %1$s.',
                'Ce thème recommande les plugins suivants: %1$s.',
                'wordscore'
            ),
            'notice_ask_to_update'            => _n_noop(
                'Le plugin suivant doit être mis à jour: %1$s.',
                'Les plugins suivants doivent être mis à jour: %1$s.',
                'wordscore'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'Une mise à jour est disponible pour: %1$s.',
                'Des mises à jour sont disponibles pour: %1$s.',
                'wordscore'
            ),
            'notice_can_activate_required'    => _n_noop(
                'Le plugin requis suivant est inactif: %1$s.',
                'Les plugins requis suivants sont inactifs: %1$s.',
                'wordscore'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'Le plugin recommandé suivant est inactif: %1$s.',
                'Les plugins recommandés suivants sont inactifs: %1$s.',
                'wordscore'
            ),
            'install_link'                    => _n_noop(
                'Installer le plugin',
                'Installer les plugins',
                'wordscore'
            ),
            'update_link'                     => _n_noop(
                'Mettre à jour le plugin',
                'Mettre à jour les plugins',
                'wordscore'
            ),
            'activate_link'                   => _n_noop(
                'Activer le plugin',
                'Activer les plugins',
                'wordscore'
            ),
            'return'                          => __('Retour à l\'installation des plugins requis', 'wordscore'),
            'plugin_activated'                => __('Plugin activé avec succès.', 'wordscore'),
            'activated_successfully'          => __('Le plugin suivant a été activé: %1$s.', 'wordscore'),
            'plugin_already_active'           => __('Aucune action effectuée. Le plugin %1$s était déjà actif.', 'wordscore'),
            'plugin_needs_higher_version'     => __('Le plugin %s nécessite une version plus récente de WordPress.', 'wordscore'),
            'complete'                        => __('Tous les plugins ont été installés et activés avec succès. %1$s', 'wordscore'),
            'dismiss'                         => __('Masquer cette notification', 'wordscore'),
            'notice_cannot_install_activate'  => __('Il y a un ou plusieurs plugins requis à installer, mettre à jour ou activer.', 'wordscore'),
            'contact_admin'                   => __('Contactez l\'administrateur de ce site.', 'wordscore'),
            'nag_type'                        => 'updated',
        ),
    );

    tgmpa($plugins, $config);
}
