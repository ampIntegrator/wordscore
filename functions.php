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
 * Enregistrer les pages d'options ACF
 */
if (function_exists('acf_add_options_page')) {
    // Page Options Globales (sous Apparence)
    acf_add_options_page(array(
        'page_title' => 'Options Globales',
        'menu_title' => 'Options Globales',
        'menu_slug'  => 'global',
        'capability' => 'edit_theme_options',
        'parent_slug' => 'themes.php',
        'redirect'   => false
    ));

    // Page Header (sous Apparence)
    acf_add_options_page(array(
        'page_title' => 'Header',
        'menu_title' => 'Header',
        'menu_slug'  => 'header',
        'capability' => 'edit_theme_options',
        'parent_slug' => 'themes.php',
        'redirect'   => false
    ));

    // Page Footer (sous Apparence)
    acf_add_options_page(array(
        'page_title' => 'Footer',
        'menu_title' => 'Footer',
        'menu_slug'  => 'footer',
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

    // Charger admin-colors.css sur les pages d'options ACF et pages d'édition
    $is_options_page = (
        strpos($screen->id, 'header') !== false ||
        strpos($screen->id, 'footer') !== false ||
        strpos($screen->id, 'socials') !== false ||
        strpos($screen->id, 'banniere') !== false ||
        strpos($screen->id, 'logos') !== false
    );

    $is_post_edit = in_array($screen->base, array('post', 'page'));

    if (!$is_options_page && !$is_post_edit) {
        return;
    }

    $modified_adminColors = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/admin-colors.css'));
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

    // Afficher sur les pages d'options ACF et d'édition
    $is_options_page = (
        strpos($screen->id, 'header') !== false ||
        strpos($screen->id, 'footer') !== false ||
        strpos($screen->id, 'socials') !== false ||
        strpos($screen->id, 'banniere') !== false ||
        strpos($screen->id, 'logos') !== false ||
        strpos($screen->id, 'global') !== false
    );

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

/**
 * Injecte les couleurs et typographie ACF dans le compilateur SCSS
 * Permet d'utiliser les valeurs dynamiques dans les variables SCSS
 */
add_filter('bootscore/scss/compiler', 'bootscore_child_inject_acf_to_scss');
function bootscore_child_inject_acf_to_scss($compiler) {
    $colors = wordscore_get_theme_colors();

    // Récupérer les options de typographie
    $body_font_size = wordscore_get_cached_option('body_font_size', 16);

    // Injecter les variables SCSS avec les valeurs ACF
    $compiler->setVariables([
        // Couleurs
        'primary'   => $colors['theme1'],
        'secondary' => $colors['theme2'],
        'theme1'    => $colors['theme1'],
        'theme2'    => $colors['theme2'],
        'theme3'    => $colors['theme3'],
        'theme4'    => $colors['theme4'],
        'theme5'    => $colors['theme5'],
        'theme6'    => $colors['theme6'],
        'ink'       => $colors['ink'],

        // Typographie (convertir en rem pour Bootstrap)
        'font-size-base' => ($body_font_size / 16) . 'rem',
    ]);

    return $compiler;
}

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

    // Compile editor.scss
    $scss_compiler_editor = new BootscoreScssCompiler();
    $scss_compiler_editor->scssFile('/assets/scss/editor.scss')
                         ->cssFile('/assets/css/editor.css')
                         ->addModifiedSelf()
                         ->addModifiedCheck(get_template_directory() . '/assets/scss/bootstrap/bootstrap.scss', false)
                         ->addModifiedCheck('/assets/scss/_bootscore-variables.scss')
                         ->skipEnvironmentCheck()
                         ->compile();

    // Compile admin-colors.scss
    $scss_compiler_admin = new BootscoreScssCompiler();
    $scss_compiler_admin->scssFile('/assets/scss/admin-colors.scss')
                        ->cssFile('/assets/css/admin-colors.css')
                        ->addModifiedSelf()
                        ->addModifiedCheck('/assets/scss/_bootscore-variables.scss')
                        ->compile();
}

/**
 * Injecte les variables CSS pour les font-sizes des headings
 */
add_action('wp_head', 'bootscore_child_inject_heading_sizes', 5);
function bootscore_child_inject_heading_sizes() {
    $h1_size = wordscore_get_cached_option('h1_font_size', 48);
    $h2_size = wordscore_get_cached_option('h2_font_size', 36);
    $h3_size = wordscore_get_cached_option('h3_font_size', 28);
    $h4_size = wordscore_get_cached_option('h4_font_size', 24);
    $h5_size = wordscore_get_cached_option('h5_font_size', 20);
    $h6_size = wordscore_get_cached_option('h6_font_size', 16);

    echo '<style id="heading-sizes">';
    echo ':root {';
    echo '--h1-font-size: ' . intval($h1_size) . 'px;';
    echo '--h2-font-size: ' . intval($h2_size) . 'px;';
    echo '--h3-font-size: ' . intval($h3_size) . 'px;';
    echo '--h4-font-size: ' . intval($h4_size) . 'px;';
    echo '--h5-font-size: ' . intval($h5_size) . 'px;';
    echo '--h6-font-size: ' . intval($h6_size) . 'px;';
    echo '}';
    echo '</style>';
}

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

    // Créer un tableau unique des polices à charger
    $fonts = array_unique([$heading_font, $body_font]);

    // Construire l'URL Google Fonts
    $fonts_param = [];
    foreach ($fonts as $font) {
        // Charger les poids 300, 400, 500, 600, 700, 800, 900
        $fonts_param[] = str_replace(' ', '+', $font) . ':wght@300;400;500;600;700;800;900';
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

/**
 * Injecte les variables CSS globales (polices, couleurs, etc.)
 */
add_action('wp_head', 'bootscore_child_inject_global_css', 6);
function bootscore_child_inject_global_css() {
    // Vérifier si polices personnalisées activées
    $use_custom_fonts = wordscore_get_cached_option('use_custom_fonts', false);

    // Font weights (utilisés pour Google Fonts ET custom fonts)
    $heading_weight = wordscore_get_cached_option('heading_font_weight', '600');
    $body_weight = wordscore_get_cached_option('body_font_weight', '400');

    if ($use_custom_fonts) {
        // Utiliser les noms de polices custom
        $heading_font = 'CustomHeading';
        $body_font = 'CustomBody';
    } else {
        // Utiliser les Google Fonts
        $heading_font = wordscore_get_cached_option('heading_font', 'Inter');
        $body_font = wordscore_get_cached_option('body_font', 'Inter');

        if ($heading_font === 'custom') {
            $heading_font = wordscore_get_cached_option('heading_font_custom', 'Inter');
        }
        if ($body_font === 'custom') {
            $body_font = wordscore_get_cached_option('body_font_custom', 'Inter');
        }
    }

    // Font size body
    $body_size = wordscore_get_cached_option('body_font_size', 16);

    // Couleurs du thème (centralisées)
    $colors = wordscore_get_theme_colors();

    // Border-radius boutons
    $btn_border_radius = wordscore_get_cached_option('btn_border_radius', '0');
    $btn_border_radius_value = ($btn_border_radius === 'pill') ? '50rem' : intval($btn_border_radius) . 'px';

    // Border-radius content-wrapper (0-40px)
    $content_wrapper_border_radius = wordscore_get_cached_option('content_wrapper_border_radius', 8);
    $content_wrapper_border_radius_value = intval($content_wrapper_border_radius) . 'px';

    // Border-radius image-wrapper (0-40px)
    $image_wrapper_border_radius = wordscore_get_cached_option('image_wrapper_border_radius', 8);
    $image_wrapper_border_radius_value = intval($image_wrapper_border_radius) . 'px';

    // Calculer les variantes Bootstrap pour primary et secondary
    $primary_rgb = wordscore_hex_to_rgb($colors['theme1']);
    $secondary_rgb = wordscore_hex_to_rgb($colors['theme2']);
    $primary_text_emphasis = wordscore_darken_color($colors['theme1'], 60);
    $secondary_text_emphasis = wordscore_darken_color($colors['theme2'], 60);
    $primary_bg_subtle = wordscore_lighten_color($colors['theme1'], 80);
    $secondary_bg_subtle = wordscore_lighten_color($colors['theme2'], 80);
    $primary_border_subtle = wordscore_lighten_color($colors['theme1'], 60);
    $secondary_border_subtle = wordscore_lighten_color($colors['theme2'], 60);

    // RGB pour ink color
    $ink_rgb = wordscore_hex_to_rgb($colors['ink']);

    echo '<style id="global-settings">';
    echo ':root {';

    // Variables personnalisées (legacy - à conserver pour compatibilité)
    echo '--font-heading: "' . esc_attr($heading_font) . '", sans-serif;';
    echo '--font-body: "' . esc_attr($body_font) . '", sans-serif;';
    echo '--font-weight-heading: ' . intval($heading_weight) . ';';
    echo '--font-weight-body: ' . intval($body_weight) . ';';
    echo '--font-size-body: ' . intval($body_size) . 'px;';

    // Variables Bootstrap pour le body
    echo '--bs-body-font-family: "' . esc_attr($body_font) . '", sans-serif;';
    echo '--bs-body-font-size: ' . intval($body_size) . 'px;';
    echo '--bs-body-font-weight: ' . intval($body_weight) . ';';
    echo '--bs-body-line-height: 1.5;';
    echo '--bs-body-color: ' . esc_attr($colors['ink']) . ';';
    echo '--bs-body-color-rgb: ' . esc_attr($ink_rgb) . ';';

    // Couleurs du thème
    echo '--colorTheme1: ' . esc_attr($colors['theme1']) . ';';
    echo '--colorTheme2: ' . esc_attr($colors['theme2']) . ';';
    echo '--colorTheme3: ' . esc_attr($colors['theme3']) . ';';
    echo '--colorTheme4: ' . esc_attr($colors['theme4']) . ';';
    echo '--colorTheme5: ' . esc_attr($colors['theme5']) . ';';
    echo '--colorTheme6: ' . esc_attr($colors['theme6']) . ';';
    echo '--colorText: ' . esc_attr($colors['ink']) . ';';

    // Variables Bootstrap Primary (complètes)
    echo '--bs-primary: ' . esc_attr($colors['theme1']) . ';';
    echo '--bs-primary-rgb: ' . esc_attr($primary_rgb) . ';';
    echo '--bs-primary-text-emphasis: ' . esc_attr($primary_text_emphasis) . ';';
    echo '--bs-primary-bg-subtle: ' . esc_attr($primary_bg_subtle) . ';';
    echo '--bs-primary-border-subtle: ' . esc_attr($primary_border_subtle) . ';';

    // Variables Bootstrap Secondary (complètes)
    echo '--bs-secondary: ' . esc_attr($colors['theme2']) . ';';
    echo '--bs-secondary-rgb: ' . esc_attr($secondary_rgb) . ';';
    echo '--bs-secondary-text-emphasis: ' . esc_attr($secondary_text_emphasis) . ';';
    echo '--bs-secondary-bg-subtle: ' . esc_attr($secondary_bg_subtle) . ';';
    echo '--bs-secondary-border-subtle: ' . esc_attr($secondary_border_subtle) . ';';

    // Border-radius
    echo '--btn-border-radius: ' . esc_attr($btn_border_radius_value) . ';';
    echo '--content-wrapper-border-radius: ' . esc_attr($content_wrapper_border_radius_value) . ';';
    echo '--image-wrapper-border-radius: ' . esc_attr($image_wrapper_border_radius_value) . ';';
    echo '}';
    echo 'body { font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); font-size: var(--bs-body-font-size); color: var(--colorText); }';
    echo 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 { font-family: var(--font-heading); font-weight: var(--font-weight-heading) !important; }';

    // Classes background pour les 6 couleurs du thème
    echo '.bg-theme1 { background-color: var(--colorTheme1) !important; }';
    echo '.bg-theme2 { background-color: var(--colorTheme2) !important; }';
    echo '.bg-theme3 { background-color: var(--colorTheme3) !important; }';
    echo '.bg-theme4 { background-color: var(--colorTheme4) !important; }';
    echo '.bg-theme5 { background-color: var(--colorTheme5) !important; }';
    echo '.bg-theme6 { background-color: var(--colorTheme6) !important; }';

    // Border-radius
    echo '.btn { border-radius: var(--btn-border-radius) !important; }';
    echo '.content-wrapper { border-radius: var(--content-wrapper-border-radius); }';
    echo '.image-wrapper { border-radius: var(--image-wrapper-border-radius); }';
    echo '</style>';
}

/**
 * Affiche la palette de couleurs dans le wp-admin
 */
add_action('admin_footer', 'display_color_palette_admin');
function display_color_palette_admin() {
    $screen = get_current_screen();

    // Afficher sur les pages d'options ACF (header, footer, socials)
    $show_on_options = ($screen && (
        strpos($screen->id, 'header') !== false ||
        strpos($screen->id, 'footer') !== false ||
        strpos($screen->id, 'socials') !== false
    ));

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
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme1']); ?>;"></span><span class="color-name">1</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme2']); ?>;"></span><span class="color-name">2</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme3']); ?>;"></span><span class="color-name">3</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme4']); ?>;"></span><span class="color-name">4</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme5']); ?>;"></span><span class="color-name">5</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($colors['theme6']); ?>;"></span><span class="color-name">6</span></li>
        </ul>
    </div>
    <?php
}


