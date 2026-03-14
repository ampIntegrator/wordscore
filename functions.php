<?php

/**
 * @package Wordscore
 *
 * @version 1.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Load theme textdomain for translations
 */
add_action('after_setup_theme', 'wordscore_load_textdomain');
function wordscore_load_textdomain() {
    load_theme_textdomain('wordscore', get_stylesheet_directory() . '/languages');
}

// Include Flexible Content Builder helpers
require_once get_stylesheet_directory() . '/inc/flexible-helpers.php';

// Include Cache helpers
require_once get_stylesheet_directory() . '/inc/cache-helpers.php';

// Include TGM Plugin Activation
require_once get_stylesheet_directory() . '/inc/tgmpa/tgmpa-config.php';

// Include Block customizations
require_once get_stylesheet_directory() . '/inc/blocks/block-widget-search.php';

/**
 * Enregistrer le menu de bannière
 */
add_action('after_setup_theme', 'bootscore_child_register_menus');
function bootscore_child_register_menus() {
    register_nav_menu('banniere-menu', __('Bannière Menu', 'wordscore'));
}

/**
 * Retirer les zones de widget inutilisées
 */
add_action('widgets_init', 'bootscore_child_unregister_sidebars', 11);
function bootscore_child_unregister_sidebars() {
    unregister_sidebar('footer-1');
    unregister_sidebar('footer-info');
    unregister_sidebar('top-bar');
    unregister_sidebar('top-nav');
}

/**
 * Enregistrer la page d'options ACF unique
 * Tous les groupes de champs (Global, Logos, Socials, Header, Footer, Banniere)
 * sont affichés sur cette page unique
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Options Globales',
        'menu_title' => 'Options Globales',
        'menu_slug'  => 'global',
        'capability' => 'edit_theme_options',
        'parent_slug' => 'themes.php',
        'redirect'   => false
    ));
}


/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles', 20);
function bootscore_child_enqueue_styles() {

  // Retirer Font Awesome du parent
  wp_dequeue_style('fontawesome');
  wp_deregister_style('fontawesome');

  // Retirer le main.css du parent
  wp_dequeue_style('main');
  wp_deregister_style('main');

  // Ajouter Bootstrap Icons
  wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3');

  // Compiled main.css du child (remplace celui du parent)
  $modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/main.css'));
  wp_enqueue_style('main-child', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), $modified_bootscoreChildCss);

  // ACF Overrides CSS - surcharge les variables Bootstrap avec les valeurs ACF
  $acf_overrides_file = get_stylesheet_directory() . '/assets/css/acf-overrides.css';
  if (file_exists($acf_overrides_file)) {
    $modified_acfOverrides = date('YmdHi', filemtime($acf_overrides_file));
    wp_enqueue_style('acf-overrides', get_stylesheet_directory_uri() . '/assets/css/acf-overrides.css', array('main-child'), $modified_acfOverrides);
  }

  // custom.js
  $modificated_CustomJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/custom.js'));
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $modificated_CustomJS, false, true);
}

/**
 * Enqueue admin-colors.css dans l'admin UNIQUEMENT sur les pages ACF
 * Pour afficher correctement la palette de couleurs sans charger tout Bootstrap
 */
add_action('admin_enqueue_scripts', 'bootscore_child_admin_enqueue_styles');
function bootscore_child_admin_enqueue_styles() {
    $screen = get_current_screen();

    if (!$screen) {
        return;
    }

    // Charger admin-colors.css sur la page Global Options et pages d'édition
    $is_options_page = strpos($screen->id, 'global') !== false;

    $is_post_edit = in_array($screen->base, array('post', 'page'));

    if (!$is_options_page && !$is_post_edit) {
        return;
    }

    $admin_colors_file = get_stylesheet_directory() . '/assets/css/admin-colors.css';
    $modified_adminColors = file_exists($admin_colors_file) ? date('YmdHi', filemtime($admin_colors_file)) : time();
    wp_enqueue_style('admin-colors', get_stylesheet_directory_uri() . '/assets/css/admin-colors.css', array(), $modified_adminColors);
}

/**
 * Injecte les variables CSS et classes de couleurs dans l'admin
 * Pour que la palette et les sélecteurs de couleurs fonctionnent correctement
 */
add_action('admin_head', 'bootscore_child_inject_admin_colors');
function bootscore_child_inject_admin_colors() {
    $screen = get_current_screen();

    if (!$screen) {
        return;
    }

    // Afficher sur la page Global Options et pages d'édition
    $is_options_page = strpos($screen->id, 'global') !== false;

    $is_post_edit = in_array($screen->base, array('post', 'page'));

    if (!$is_options_page && !$is_post_edit) {
        return;
    }

    // Récupérer les couleurs
    $colors = wordscore_get_theme_colors();

    // Calculer les variantes Bootstrap
    $primary_rgb = wordscore_hex_to_rgb($colors['theme1']);
    $secondary_rgb = wordscore_hex_to_rgb($colors['theme2']);

    echo '<style id="admin-theme-colors">';
    echo ':root {';
    echo '--colorTheme1: ' . esc_attr($colors['theme1']) . ';';
    echo '--colorTheme2: ' . esc_attr($colors['theme2']) . ';';
    echo '--colorTheme3: ' . esc_attr($colors['theme3']) . ';';
    echo '--colorTheme4: ' . esc_attr($colors['theme4']) . ';';
    echo '--colorTheme5: ' . esc_attr($colors['theme5']) . ';';
    echo '--colorTheme6: ' . esc_attr($colors['theme6']) . ';';
    echo '--bs-primary: ' . esc_attr($colors['theme1']) . ';';
    echo '--bs-secondary: ' . esc_attr($colors['theme2']) . ';';
    echo '--bs-primary-rgb: ' . esc_attr($primary_rgb) . ';';
    echo '--bs-secondary-rgb: ' . esc_attr($secondary_rgb) . ';';
    echo '}';
    echo '.bg-theme1 { background-color: var(--colorTheme1) !important; }';
    echo '.bg-theme2 { background-color: var(--colorTheme2) !important; }';
    echo '.bg-theme3 { background-color: var(--colorTheme3) !important; }';
    echo '.bg-theme4 { background-color: var(--colorTheme4) !important; }';
    echo '.bg-theme5 { background-color: var(--colorTheme5) !important; }';
    echo '.bg-theme6 { background-color: var(--colorTheme6) !important; }';
    echo '</style>';
}

// SUPPRIMÉ: L'injection des variables SCSS se fait maintenant via _acf-overrides.scss
// Le fichier est généré automatiquement dans inc/cache-helpers.php et importé dans main.scss

/**
 * Remplace les classes des boutons de recherche
 */
add_filter('bootscore/class/widget/search/button', function($classes) {
    return 'searchBtn';
});

/**
 * Bouton manuel pour régénérer le CSS ACF depuis Options Globales
 */
add_action('admin_notices', function() {
    $screen = get_current_screen();
    if ($screen && strpos($screen->id, 'global') !== false) {

        // Traiter la régénération si demandée
        if (isset($_GET['regenerate_acf_css']) && $_GET['regenerate_acf_css'] === '1') {
            require_once get_stylesheet_directory() . '/inc/cache-helpers.php';
            wordscore_clear_options_cache();
            wordscore_generate_acf_overrides_css();

            echo '<div class="notice notice-success is-dismissible"><p><strong>CSS ACF régénéré avec succès !</strong> Rechargez la page pour voir les changements.</p></div>';
        }

        // Afficher le bouton
        echo '<div class="notice notice-info"><p>';
        echo '<strong>Options Globales</strong> - Après avoir modifié les couleurs ou polices, cliquez ici : ';
        echo '<a href="?page=global&regenerate_acf_css=1" class="button button-primary">Régénérer CSS</a>';
        echo '</p></div>';
    }
});

/**
 * Compile les SCSS du child theme avec vérification des sous-dossiers
 * S'exécute APRÈS la compilation du parent
 */
add_action('wp_enqueue_scripts', 'bootscore_child_compile_scss', 15);
add_action('admin_enqueue_scripts', 'bootscore_child_compile_scss', 15);
function bootscore_child_compile_scss() {
    if (!class_exists('BootscoreScssCompiler')) {
        require_once get_template_directory() . '/inc/scss-compiler.php';
    }

    $theme_dir = get_stylesheet_directory();

    // Compile main.scss avec vérification des sous-dossiers
    try {
        $scss_compiler_main = new BootscoreScssCompiler();
        $scss_compiler_main->scssFile('/assets/scss/main.scss')
                           ->cssFile('/assets/css/main.css')
                           ->addModifiedCheckTheme();

        // Ajouter tous les fichiers SCSS des sous-dossiers components/ et blocks/
        $scss_subdirs = ['/assets/scss/components', '/assets/scss/blocks'];
        foreach ($scss_subdirs as $dir) {
            $full_dir = $theme_dir . $dir;
            if (is_dir($full_dir)) {
                $scss_compiler_main->addModifiedCheckDir($dir, true);
            }
        }

        $scss_compiler_main->addModifiedCheck(get_template_directory() . '/assets/scss/bootstrap/bootstrap.scss', false)
                           ->compile();
    } catch (Exception $e) {
        wp_die('<b>SCSS Compilation Error (main.css):</b><br><br>' . $e->getMessage());
    }

    // Compile editor.scss
    try {
        $scss_compiler_editor = new BootscoreScssCompiler();
        $scss_compiler_editor->scssFile('/assets/scss/editor.scss')
                             ->cssFile('/assets/css/editor.css')
                             ->addModifiedSelf()
                             ->addModifiedCheck(get_template_directory() . '/assets/scss/bootstrap/bootstrap.scss', false)
                             ->addModifiedCheck('/assets/scss/_bootscore-variables.scss')
                             ->skipEnvironmentCheck()
                             ->compile();
    } catch (Exception $e) {
        wp_die('<b>SCSS Compilation Error (editor.css):</b><br><br>' . $e->getMessage());
    }

    // Compile admin-colors.scss
    try {
        $scss_compiler_admin = new BootscoreScssCompiler();
        $scss_compiler_admin->scssFile('/assets/scss/admin-colors.scss')
                            ->cssFile('/assets/css/admin-colors.css')
                            ->addModifiedSelf()
                            ->addModifiedCheck('/assets/scss/_bootscore-variables.scss')
                            ->compile();
    } catch (Exception $e) {
        wp_die('<b>SCSS Compilation Error (admin-colors.css):</b><br><br>' . $e->getMessage());
    }
}

// SUPPRIMÉ: Heading sizes maintenant dans acf-overrides.css (généré automatiquement)

/**
 * Charge les Google Fonts OU génère les @font-face pour les polices personnalisées
 */
add_action('wp_head', 'bootscore_child_load_google_fonts', 1);
function bootscore_child_load_google_fonts() {
    // Vérifier si l'utilisateur utilise des polices personnalisées
    $use_custom_fonts = wordscore_get_cached_option('use_custom_fonts', false);

    if ($use_custom_fonts) {
        // Générer automatiquement les @font-face pour les polices uploadées
        bootscore_child_generate_custom_font_faces();
        return;
    }

    $heading_font = wordscore_get_cached_option('heading_font', 'Inter');
    $body_font = wordscore_get_cached_option('body_font', 'Inter');

    // Si custom, récupérer le nom de la police personnalisée
    if ($heading_font === 'custom') {
        $heading_font = wordscore_get_cached_option('heading_font_custom', 'Inter');
    }
    if ($body_font === 'custom') {
        $body_font = wordscore_get_cached_option('body_font_custom', 'Inter');
    }

    // Récupérer les poids configurés dans ACF
    $heading_weight = wordscore_get_cached_option('heading_font_weight', '600');
    $body_weight = wordscore_get_cached_option('body_font_weight', '400');

    // Créer un tableau unique des polices à charger
    $fonts = array_unique([$heading_font, $body_font]);

    // Créer un array unique des poids à charger (seulement ceux configurés)
    $weights_to_load = array_unique([$heading_weight, $body_weight]);
    sort($weights_to_load); // Google Fonts préfère l'ordre croissant
    $weights_string = implode(';', $weights_to_load);

    // Construire l'URL Google Fonts
    $fonts_param = [];
    foreach ($fonts as $font) {
        // Charger UNIQUEMENT les poids configurés dans ACF au lieu de tous (300-900)
        $fonts_param[] = str_replace(' ', '+', $font) . ':wght@' . $weights_string;
    }

    if (!empty($fonts_param)) {
        $google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts_param) . '&display=swap';
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        echo '<link href="' . esc_url($google_fonts_url) . '" rel="stylesheet">';
    }
}

/**
 * Génère automatiquement les @font-face pour les polices personnalisées uploadées
 */
function bootscore_child_generate_custom_font_faces() {
    echo '<style id="custom-fonts">';

    // Font weights (utilisés dans @font-face)
    $heading_weight = wordscore_get_cached_option('heading_font_weight', '600');
    $body_weight = wordscore_get_cached_option('body_font_weight', '400');

    // Générer @font-face pour Heading Font
    $heading_font_file = wordscore_get_cached_option('heading_custom_font_file', false);

    if ($heading_font_file) {
        $extension = pathinfo($heading_font_file, PATHINFO_EXTENSION);
        $format = ($extension === 'woff2') ? 'woff2' : (($extension === 'woff') ? 'woff' : 'truetype');

        echo '@font-face {';
        echo 'font-family: "CustomHeading";';
        echo 'src: url("' . esc_url($heading_font_file) . '") format("' . $format . '");';
        echo 'font-weight: ' . intval($heading_weight) . ';';
        echo 'font-style: normal;';
        echo 'font-display: swap;';
        echo '}';
    }

    // Générer @font-face pour Body Font
    $body_font_file = wordscore_get_cached_option('body_custom_font_file', false);

    if ($body_font_file) {
        $extension = pathinfo($body_font_file, PATHINFO_EXTENSION);
        $format = ($extension === 'woff2') ? 'woff2' : (($extension === 'woff') ? 'woff' : 'truetype');

        echo '@font-face {';
        echo 'font-family: "CustomBody";';
        echo 'src: url("' . esc_url($body_font_file) . '") format("' . $format . '");';
        echo 'font-weight: ' . intval($body_weight) . ';';
        echo 'font-style: normal;';
        echo 'font-display: swap;';
        echo '}';
    }

    echo '</style>';
}

// SUPPRIMÉ: Variables CSS globales maintenant dans acf-overrides.css (généré automatiquement)

/**
 * Affiche la palette de couleurs dans le wp-admin
 */
add_action('admin_footer', 'display_color_palette_admin');
function display_color_palette_admin() {
    $screen = get_current_screen();

    // Afficher sur les pages d'options ACF (header, footer, socials)
    // Afficher sur la page Global Options
    $show_on_options = ($screen && strpos($screen->id, 'global') !== false);

    // Afficher sur les pages d'édition de posts/pages (qui peuvent avoir flexible content)
    $show_on_post_edit = ($screen && in_array($screen->base, array('post', 'page')));

    if (!$show_on_options && !$show_on_post_edit) {
        return;
    }

    // Récupérer les couleurs (centralisées)
    $colors = wordscore_get_theme_colors();

    ?>
    <style>
        .palette {
            position: fixed;
            right: 0;
            top: 100px;
            padding: 10px;
            z-index: 500;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .palette ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .palette li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .palette li span.color-box {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 50%;
        }
        .palette li span.color-name {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
    <div class="palette">
        <ul>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme1']); ?>;"></span><span class="color-name">P</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme2']); ?>;"></span><span class="color-name">S</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme3']); ?>;"></span><span class="color-name">3</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme4']); ?>;"></span><span class="color-name">4</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme5']); ?>;"></span><span class="color-name">5</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme6']); ?>;"></span><span class="color-name">6</span></li>
        </ul>
    </div>
    <?php
}
