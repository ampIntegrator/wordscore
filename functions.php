<?php

/**
 * @package Bootscore Child
 *
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

// Include Flexible Content Builder helpers
require_once get_stylesheet_directory() . '/inc/flexible-helpers.php';

/**
 * Enregistrer le menu de bannière
 */
add_action('after_setup_theme', 'bootscore_child_register_menus');
function bootscore_child_register_menus() {
    register_nav_menu('banniere-menu', 'Bannière Menu');
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
    // Page Logos (reste dans le menu principal)
    acf_add_options_page(array(
        'page_title' => 'Logos',
        'menu_title' => 'Logos',
        'menu_slug'  => 'logos',
        'capability' => 'edit_theme_options',
        'icon_url'   => 'dashicons-format-image',
        'position'   => 60,
        'redirect'   => false
    ));

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
    $h1_size = get_field('h1_font_size', 'option') ?: 48;
    $h2_size = get_field('h2_font_size', 'option') ?: 36;
    $h3_size = get_field('h3_font_size', 'option') ?: 28;
    $h4_size = get_field('h4_font_size', 'option') ?: 24;
    $h5_size = get_field('h5_font_size', 'option') ?: 20;
    $h6_size = get_field('h6_font_size', 'option') ?: 16;

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
    $use_custom_fonts = get_field('use_custom_fonts', 'option');

    if ($use_custom_fonts) {
        // Générer automatiquement les @font-face pour les polices uploadées
        bootscore_child_generate_custom_font_faces();
        return;
    }

    $heading_font = get_field('heading_font', 'option') ?: 'Inter';
    $body_font = get_field('body_font', 'option') ?: 'Inter';

    // Si custom, récupérer le nom de la police personnalisée
    if ($heading_font === 'custom') {
        $heading_font = get_field('heading_font_custom', 'option') ?: 'Inter';
    }
    if ($body_font === 'custom') {
        $body_font = get_field('body_font_custom', 'option') ?: 'Inter';
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

    // Générer @font-face pour Heading Font
    $heading_font_file = get_field('heading_custom_font_file', 'option');
    $heading_font_weight = get_field('heading_custom_font_weight', 'option') ?: '700';

    if ($heading_font_file) {
        $extension = pathinfo($heading_font_file, PATHINFO_EXTENSION);
        $format = ($extension === 'woff2') ? 'woff2' : (($extension === 'woff') ? 'woff' : 'truetype');

        echo '@font-face {';
        echo 'font-family: "CustomHeading";';
        echo 'src: url("' . esc_url($heading_font_file) . '") format("' . $format . '");';
        echo 'font-weight: ' . intval($heading_font_weight) . ';';
        echo 'font-style: normal;';
        echo 'font-display: swap;';
        echo '}';
    }

    // Générer @font-face pour Body Font
    $body_font_file = get_field('body_custom_font_file', 'option');
    $body_font_weight = get_field('body_custom_font_weight', 'option') ?: '400';

    if ($body_font_file) {
        $extension = pathinfo($body_font_file, PATHINFO_EXTENSION);
        $format = ($extension === 'woff2') ? 'woff2' : (($extension === 'woff') ? 'woff' : 'truetype');

        echo '@font-face {';
        echo 'font-family: "CustomBody";';
        echo 'src: url("' . esc_url($body_font_file) . '") format("' . $format . '");';
        echo 'font-weight: ' . intval($body_font_weight) . ';';
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
    $use_custom_fonts = get_field('use_custom_fonts', 'option');

    if ($use_custom_fonts) {
        // Utiliser les noms et weights de polices custom
        $heading_font = 'CustomHeading';
        $body_font = 'CustomBody';
        $heading_weight = get_field('heading_custom_font_weight', 'option') ?: '700';
        $body_weight = get_field('body_custom_font_weight', 'option') ?: '400';
    } else {
        // Utiliser les Google Fonts
        $heading_font = get_field('heading_font', 'option') ?: 'Inter';
        $body_font = get_field('body_font', 'option') ?: 'Inter';

        if ($heading_font === 'custom') {
            $heading_font = get_field('heading_font_custom', 'option') ?: 'Inter';
        }
        if ($body_font === 'custom') {
            $body_font = get_field('body_font_custom', 'option') ?: 'Inter';
        }

        // Font weights (Google Fonts uniquement)
        $heading_weight = get_field('heading_font_weight', 'option') ?: '600';
        $body_weight = get_field('body_font_weight', 'option') ?: '400';
    }

    // Font size body
    $body_size = get_field('body_font_size', 'option') ?: 16;

    // Couleurs du thème
    $theme1 = get_field('theme1_color', 'option') ?: '#0d6efd';
    $theme2 = get_field('theme2_color', 'option') ?: '#6c757d';
    $theme3 = get_field('theme3_color', 'option') ?: '#dc3545';
    $theme4 = get_field('theme4_color', 'option') ?: '#0dcaf0';
    $theme5 = get_field('theme5_color', 'option') ?: '#198754';
    $theme6 = get_field('theme6_color', 'option') ?: '#333333';
    $ink = get_field('ink_color', 'option') ?: '#202224';

    // Border-radius boutons
    $btn_border_radius = get_field('btn_border_radius', 'option') ?: '0';
    $btn_border_radius_value = ($btn_border_radius === 'pill') ? '50rem' : intval($btn_border_radius) . 'px';

    // Border-radius content-wrapper (0-40px)
    $content_wrapper_border_radius = get_field('content_wrapper_border_radius', 'option') ?: 8;
    $content_wrapper_border_radius_value = intval($content_wrapper_border_radius) . 'px';

    // Border-radius image-wrapper (0-40px)
    $image_wrapper_border_radius = get_field('image_wrapper_border_radius', 'option') ?: 8;
    $image_wrapper_border_radius_value = intval($image_wrapper_border_radius) . 'px';

    echo '<style id="global-settings">';
    echo ':root {';
    echo '--font-heading: "' . esc_attr($heading_font) . '", sans-serif;';
    echo '--font-body: "' . esc_attr($body_font) . '", sans-serif;';
    echo '--font-weight-heading: ' . intval($heading_weight) . ';';
    echo '--font-weight-body: ' . intval($body_weight) . ';';
    echo '--font-size-body: ' . intval($body_size) . 'px;';
    echo '--colorTheme1: ' . esc_attr($theme1) . ';';
    echo '--colorTheme2: ' . esc_attr($theme2) . ';';
    echo '--colorTheme3: ' . esc_attr($theme3) . ';';
    echo '--colorTheme4: ' . esc_attr($theme4) . ';';
    echo '--colorTheme5: ' . esc_attr($theme5) . ';';
    echo '--colorTheme6: ' . esc_attr($theme6) . ';';
    echo '--colorText: ' . esc_attr($ink) . ';';
    echo '--btn-border-radius: ' . esc_attr($btn_border_radius_value) . ';';
    echo '--content-wrapper-border-radius: ' . esc_attr($content_wrapper_border_radius_value) . ';';
    echo '--image-wrapper-border-radius: ' . esc_attr($image_wrapper_border_radius_value) . ';';
    echo '}';
    echo 'body { font-family: var(--font-body); font-weight: var(--font-weight-body); font-size: var(--font-size-body); color: var(--colorText); }';
    echo 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 { font-family: var(--font-heading); font-weight: var(--font-weight-heading) !important; }';
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

    // Récupérer les couleurs dynamiquement
    $theme1 = get_field('theme1_color', 'option') ?: '#0d6efd';
    $theme2 = get_field('theme2_color', 'option') ?: '#6c757d';
    $theme3 = get_field('theme3_color', 'option') ?: '#dc3545';
    $theme4 = get_field('theme4_color', 'option') ?: '#0dcaf0';
    $theme5 = get_field('theme5_color', 'option') ?: '#198754';
    $theme6 = get_field('theme6_color', 'option') ?: '#333333';

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
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme1); ?>;"></span><span class="color-name">1</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme2); ?>;"></span><span class="color-name">2</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme3); ?>;"></span><span class="color-name">3</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme4); ?>;"></span><span class="color-name">4</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme5); ?>;"></span><span class="color-name">5</span></li>
            <li><span class="color-box" style="background-color: <?php echo esc_attr($theme6); ?>;"></span><span class="color-name">6</span></li>
        </ul>
    </div>
    <?php
}


