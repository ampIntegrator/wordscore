<?php
/**
 * Flexible Content Builder - Helper Functions
 *
 * Fonctions réutilisables pour optimiser les blocs flexibles, le header et le footer
 *
 * TABLE DES MATIÈRES:
 * 1. Flexible Content - Fonctions générales
 * 2. Header - Fonctions de gestion du header
 * 3. Footer - Fonctions de gestion du footer
 * 4. Bannières - Fonctions de gestion des bannières
 *
 * @package Bootscore_Child
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// =============================================================================
// 1. FLEXIBLE CONTENT - FONCTIONS GÉNÉRALES
// =============================================================================

/**
 * Récupère et valide le padding avec valeurs par défaut
 *
 * @param array $bloc Données du bloc ACF
 * @param string $key Clé du champ (padding_top ou padding_bottom)
 * @param int $default Valeur par défaut
 * @return int Valeur du padding validée (0-100)
 */
function flexible_get_padding($bloc, $key, $default = 0) {
    $value = isset($bloc[$key]) ? intval($bloc[$key]) : $default;
    return max(0, min(100, $value)); // Limite entre 0 et 100
}

/**
 * Génère le style inline pour les paddings de section
 *
 * @param int $top Padding top
 * @param int $bottom Padding bottom
 * @param string $extra_style Styles supplémentaires
 * @return string Attribut style complet
 */
function flexible_get_padding_style($top, $bottom, $extra_style = '') {
    $styles = [];

    if ($extra_style) {
        $styles[] = rtrim($extra_style, ';');
    }

    $styles[] = "padding-top: {$top}px";
    $styles[] = "padding-bottom: {$bottom}px";

    return ' style="' . implode('; ', $styles) . ';"';
}

/**
 * Gère le background d'une section (couleur thème, custom ou image)
 *
 * @param array $bloc Données du bloc ACF
 * @param bool $allow_image Autoriser les images de fond
 * @return array ['style' => string, 'class' => string]
 */
function flexible_get_background($bloc, $allow_image = true) {
    $bg_style = '';
    $bg_class = '';

    if ($allow_image) {
        $bg_type = $bloc['background_type'] ?? 'color';

        if ($bg_type === 'image' && !empty($bloc['background_image'])) {
            $bg_image = flexible_get_image_url($bloc['background_image']);
            if ($bg_image) {
                $bg_style = "background-image: url('" . esc_url($bg_image) . "');";
            }
            return ['style' => $bg_style, 'class' => $bg_class];
        }
    }

    // Gestion couleur
    $color_choice = $bloc['color_choice'] ?? 'theme1';

    if ($color_choice === 'custom' && !empty($bloc['custom_color'])) {
        $bg_style = 'background-color: ' . esc_attr($bloc['custom_color']) . ';';
    } else {
        $bg_class = 'bg-' . esc_attr($color_choice);
    }

    return ['style' => $bg_style, 'class' => $bg_class];
}

/**
 * Extrait l'URL d'un champ image ACF
 *
 * @param mixed $image_field Champ image (array ou string)
 * @return string|false URL de l'image ou false
 */
function flexible_get_image_url($image_field) {
    if (empty($image_field)) {
        return false;
    }

    if (is_array($image_field) && isset($image_field['url'])) {
        return $image_field['url'];
    }

    if (is_string($image_field)) {
        return $image_field;
    }

    return false;
}

/**
 * Retourne la classe Bootstrap pour une largeur de colonne
 *
 * @param string $width Largeur en pourcentage ('33', '50', '66', '75', '100')
 * @param string $default Classe par défaut
 * @return string Classe Bootstrap
 */
function flexible_get_col_class($width, $default = 'col-12') {
    $map = [
        '33' => 'col-md-4',
        '50' => 'col-md-6',
        '66' => 'col-md-8',
        '75' => 'col-md-9',
        '100' => 'col-12'
    ];

    return $map[$width] ?? $default;
}

/**
 * Retourne la classe Bootstrap pour la colonne image complémentaire
 *
 * @param string $main_width Largeur de la colonne principale
 * @return string Classe Bootstrap pour l'image
 */
function flexible_get_image_col_class($main_width) {
    $map = [
        '50' => 'col-md-6',
        '66' => 'col-md-4',
        '75' => 'col-md-3'
    ];

    return $map[$main_width] ?? '';
}

/**
 * Génère la classe d'inversion de colonnes
 *
 * @param bool $inverser Inverser ou non
 * @return string Classe Bootstrap
 */
function flexible_get_order_class($inverser) {
    return $inverser ? 'flex-row-reverse' : '';
}

/**
 * Génère la classe d'alignement de texte
 *
 * @param string $align left, center ou right
 * @return string Classe Bootstrap
 */
function flexible_get_text_align_class($align) {
    $align = in_array($align, ['left', 'center', 'right']) ? $align : 'left';
    return 'text-' . $align;
}

/**
 * Génère la classe de couleur de texte
 *
 * @param string $color light ou dark
 * @return string Classe (text-light ou text-ink)
 */
function flexible_get_text_color_class($color) {
    return $color === 'light' ? 'text-light' : 'text-ink';
}

/**
 * Génère la classe de background pour content-wrapper
 *
 * @param string $bg_choice Choix de background (white, theme4, theme5, etc.)
 * @return string Classe Bootstrap
 */
function flexible_get_content_bg_class($bg_choice) {
    return $bg_choice === 'white' ? 'bg-white' : 'bg-' . esc_attr($bg_choice);
}

/**
 * Génère un ID unique pour les composants (accordion, tabs, etc.)
 *
 * @param string $prefix Préfixe de l'ID
 * @return string ID unique
 */
function flexible_get_unique_id($prefix = 'flexible') {
    return $prefix . '-' . uniqid();
}

/**
 * Valide et retourne une balise heading
 *
 * @param string $tag Balise demandée
 * @param string $default Balise par défaut
 * @return string Balise validée (h1-h6)
 */
function flexible_get_heading_tag($tag, $default = 'h2') {
    $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
    return in_array($tag, $allowed) ? $tag : $default;
}

/**
 * Génère la classe de bouton Bootstrap
 *
 * @param string $type Type de bouton (primary, secondary, etc.)
 * @param bool $outline Bouton outline ou non
 * @return string Classe Bootstrap complète
 */
function flexible_get_btn_class($type = 'primary', $outline = false) {
    $allowed_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
    $type = in_array($type, $allowed_types) ? $type : 'primary';
    return $outline ? 'btn-outline-' . $type : 'btn-' . $type;
}

/**
 * Retourne les choix de types de boutons Bootstrap
 * Utilisé pour standardiser les sélecteurs de type de bouton dans ACF
 *
 * @return array Tableau associatif des types de boutons
 */
function flexible_get_btn_type_choices() {
    return [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'success' => 'Success',
        'danger' => 'Danger',
        'warning' => 'Warning',
        'info' => 'Info',
        'light' => 'Light',
        'dark' => 'Dark'
    ];
}

/**
 * Génère le HTML d'un bouton à partir des données ACF
 * Standardise le rendu des boutons dans tout le flexible content
 *
 * @param array $link Tableau ACF link field (url, title, target)
 * @param string $type Type de bouton (primary, secondary, etc.)
 * @param bool $outline Bouton outline ou non
 * @param string $size Classe de taille (btn-lg, btn-sm, ou vide)
 * @param string $text_color Couleur du texte (light, dark, ou vide)
 * @return string HTML du bouton ou chaîne vide si pas de lien
 */
function flexible_render_button($link, $type = 'primary', $outline = false, $size = '', $text_color = '') {
    if (!$link || empty($link['url'])) {
        return '';
    }

    $btn_class = flexible_get_btn_class($type, $outline);
    $size_class = $size ? ' ' . esc_attr($size) : '';
    $text_class = $text_color ? ' ' . flexible_get_text_color_class($text_color) : '';
    $target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
    $title = esc_html($link['title'] ?? 'En savoir plus');

    return sprintf(
        '<a href="%s" class="btn %s%s%s"%s>%s</a>',
        esc_url($link['url']),
        $btn_class,
        $size_class,
        $text_class,
        $target,
        $title
    );
}

/**
 * Parse une URL vidéo YouTube et retourne l'embed HTML
 *
 * @param string $url URL YouTube
 * @return string|false HTML iframe ou false
 */
function flexible_get_youtube_embed($url) {
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
        $video_id = $matches[1];
        return '<div class="video-background"><iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=1&mute=1&loop=1&playlist=' . esc_attr($video_id) . '&controls=0&showinfo=0&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>';
    }
    return false;
}

/**
 * Parse une URL vidéo Vimeo et retourne l'embed HTML
 *
 * @param string $url URL Vimeo
 * @return string|false HTML iframe ou false
 */
function flexible_get_vimeo_embed($url) {
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        $video_id = $matches[1];
        return '<div class="video-background"><iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?autoplay=1&muted=1&loop=1&background=1&controls=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>';
    }
    return false;
}

/**
 * Génère le HTML pour un background vidéo
 *
 * @param array $bloc Données du bloc
 * @return string HTML du background vidéo
 */
function flexible_get_video_background($bloc) {
    $bg_type = $bloc['background_type'] ?? '';

    if ($bg_type === 'video_upload' && !empty($bloc['background_video_file'])) {
        $video_file = flexible_get_image_url($bloc['background_video_file']);
        if ($video_file) {
            return '<div class="video-background"><video autoplay muted loop playsinline><source src="' . esc_url($video_file) . '" type="video/mp4"></video></div>';
        }
    } elseif ($bg_type === 'video_url' && !empty($bloc['background_video_url'])) {
        $video_url = $bloc['background_video_url'];

        // Essayer YouTube
        $youtube = flexible_get_youtube_embed($video_url);
        if ($youtube) return $youtube;

        // Essayer Vimeo
        $vimeo = flexible_get_vimeo_embed($video_url);
        if ($vimeo) return $vimeo;
    }

    return '';
}

/**
 * Récupère les données communes d'un bloc image-texte
 *
 * @param array $bloc Données du bloc ACF
 * @return array Données formatées et validées
 */
function flexible_get_image_texte_data($bloc) {
    $background = flexible_get_background($bloc, true);

    return [
        'contenu' => $bloc['contenu'] ?? '',
        'inverser' => $bloc['inverser'] ?? false,
        'padding_top' => flexible_get_padding($bloc, 'padding_top', 0),
        'padding_bottom' => flexible_get_padding($bloc, 'padding_bottom', 0),
        'bg_style' => $background['style'],
        'bg_class' => $background['class'],
        'order_class' => flexible_get_order_class($bloc['inverser'] ?? false),
        'image_min_height' => isset($bloc['image_min_height']) ? max(200, min(600, intval($bloc['image_min_height']))) : 300,
        'image_url' => flexible_get_image_url($bloc['image_col'] ?? ''),
        'content_padding' => flexible_get_padding($bloc, 'content_padding', 20),
        'content_bg_class' => flexible_get_content_bg_class($bloc['content_bg'] ?? 'white'),
        'content_text_class' => flexible_get_text_color_class($bloc['content_text'] ?? 'dark'),
    ];
}

// =============================================================================
// 2. HEADER - FONCTIONS DE GESTION DU HEADER
// =============================================================================

/**
 * Génère le style inline pour le background du header (#masthead)
 *
 * @return string Style inline ou chaîne vide
 */
function header_get_masthead_background_style() {
    $color_choice = get_field('header_background_color', 'option') ?: 'theme5';

    if ($color_choice === 'custom') {
        $custom_color = get_field('header_background_custom_color', 'option');
        if ($custom_color) {
            return ' style="background-color: ' . esc_attr($custom_color) . ';"';
        }
    }

    if ($color_choice === 'gradient') {
        $gradient = get_field('header_background_gradient', 'option');
        if ($gradient) {
            return ' style="background-image: ' . esc_attr($gradient) . ';"';
        }
    }

    return '';
}

/**
 * Génère la classe CSS pour le background du header (#masthead)
 *
 * @return string Classe CSS
 */
function header_get_masthead_background_class() {
    $color_choice = get_field('header_background_color', 'option') ?: 'theme5';

    if ($color_choice !== 'custom' && $color_choice !== 'gradient') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Génère la classe CSS pour la couleur du texte du menu header desktop
 *
 * @return string Classe CSS (menu-text-light ou menu-text-dark)
 */
function header_get_menu_text_class() {
    $text_color = get_field('header_menu_text_color', 'option') ?: 'dark';
    return $text_color === 'light' ? 'menu-text-light' : 'menu-text-dark';
}

/**
 * Récupère l'icône du bouton toggle menu mobile
 *
 * @return string HTML de l'icône ou icône par défaut (bars)
 */
function header_get_menu_toggle_icon() {
    $icon = get_field('menu_toggle_icon', 'option');
    return $icon ? wp_kses_post($icon) : '<i class="bi bi-list"></i>';
}

/**
 * Génère le style inline pour le background de l'offcanvas menu
 *
 * @return string Style inline ou chaîne vide
 */
function header_get_offcanvas_background_style() {
    $color_choice = get_field('offcanvas_background_color', 'option') ?: 'theme5';

    if ($color_choice === 'custom') {
        $custom_color = get_field('offcanvas_background_custom_color', 'option');
        if ($custom_color) {
            return ' style="background-color: ' . esc_attr($custom_color) . ';"';
        }
    }

    return '';
}

/**
 * Génère la classe CSS pour le background de l'offcanvas menu
 *
 * @return string Classe CSS
 */
function header_get_offcanvas_background_class() {
    $color_choice = get_field('offcanvas_background_color', 'option') ?: 'theme5';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Génère la classe CSS pour la couleur du texte de l'offcanvas menu
 *
 * @return string Classe CSS (text-light ou text-dark)
 */
function header_get_offcanvas_text_class() {
    $text_color = get_field('offcanvas_text_color', 'option') ?: 'dark';
    return $text_color === 'light' ? 'text-light' : 'text-dark';
}

/**
 * Récupère l'icône de fermeture du menu offcanvas
 *
 * @return string HTML de l'icône ou icône par défaut (X)
 */
function header_get_offcanvas_menu_close_icon() {
    $icon = get_field('offcanvas_menu_close_icon', 'option');
    return $icon ? wp_kses_post($icon) : '<i class="bi bi-x-lg"></i>';
}

// --- Search Offcanvas ---

/**
 * Vérifie si le CTA Search doit être affiché
 *
 * @return bool
 */
function header_search_should_display() {
    return (bool) get_field('search_activer', 'option');
}

/**
 * Récupère l'icône HTML du CTA Search
 *
 * @return string HTML de l'icône
 */
function header_get_search_icon() {
    $icon = get_field('search_icon_html', 'option');
    return $icon ? wp_kses_post($icon) : '<i class="bi bi-search"></i>';
}

/**
 * Génère la classe de background pour l'offcanvas search
 *
 * @return string Classe CSS
 */
function header_search_get_bg_class() {
    $color_choice = get_field('search_offcanvas_bg_color', 'option') ?: 'theme5';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Génère le style inline pour l'offcanvas search
 *
 * @return string Style inline
 */
function header_search_get_bg_style() {
    $color_choice = get_field('search_offcanvas_bg_color', 'option') ?: 'theme5';

    if ($color_choice === 'custom') {
        $custom_color = get_field('search_offcanvas_bg_custom', 'option');
        if ($custom_color) {
            return ' style="background-color: ' . esc_attr($custom_color) . ';"';
        }
    }

    return '';
}

/**
 * Génère la classe de couleur de texte pour l'offcanvas search
 *
 * @return string Classe CSS
 */
function header_search_get_text_class() {
    $text_color = get_field('search_offcanvas_text_color', 'option') ?: 'dark';
    return 'text-' . esc_attr($text_color);
}

// =============================================================================
// 3. FOOTER - FONCTIONS DE GESTION DU FOOTER
// =============================================================================

// --- Footer Top ---

/**
 * Génère la classe CSS pour le background de Footer Top
 *
 * @return string Classe CSS
 */
function footer_top_get_bg_class() {
    $color_choice = get_field('footer_top_background_color', 'option') ?: 'theme6';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Génère le style inline pour Footer Top (.bootscore-footer-top)
 * Gère background-color, padding et border
 *
 * @return string Style inline
 */
function footer_top_get_style() {
    $styles = [];

    // Background color custom
    $color_choice = get_field('footer_top_background_color', 'option') ?: 'theme6';
    if ($color_choice === 'custom') {
        $custom_color = get_field('footer_top_background_custom_color', 'option');
        if ($custom_color) {
            $styles[] = 'background-color: ' . esc_attr($custom_color);
        }
    }

    // Padding
    $padding = get_field('footer_top_padding', 'option') ?: 40;
    $padding = max(0, min(200, intval($padding)));
    $styles[] = 'padding-top: ' . $padding . 'px';
    $styles[] = 'padding-bottom: ' . $padding . 'px';

    // Border bottom
    $border = get_field('footer_top_border_bottom', 'option');
    if ($border) {
        $styles[] = 'border-bottom: ' . esc_attr($border);
    }

    return !empty($styles) ? ' style="' . implode('; ', $styles) . ';"' : '';
}

// --- Footer Main ---

/**
 * Vérifie si Footer Main doit être affiché
 *
 * @return bool
 */
function footer_main_should_display() {
    return (bool) get_field('footer_main_activer', 'option');
}

/**
 * Retourne la classe de couleur de texte pour footerMain
 *
 * @return string Classe CSS (text-light ou text-dark)
 */
function footer_main_get_text_color_class() {
    $text_color = get_field('footer_main_text_color', 'option') ?: 'dark';
    return 'text-' . esc_attr($text_color);
}

/**
 * Génère le style inline pour le footer principal (.footerMain)
 * Gère background-color et padding
 *
 * @return string Style inline
 */
function footer_get_main_style() {
    $styles = [];

    // Background color
    $color_choice = get_field('footer_background_color', 'option') ?: 'theme4';
    if ($color_choice === 'custom') {
        $custom_color = get_field('footer_background_custom_color', 'option');
        if ($custom_color) {
            $styles[] = 'background-color: ' . esc_attr($custom_color);
        }
    }

    // Padding
    $padding = get_field('footer_padding', 'option') ?: 40;
    $padding = max(0, min(200, intval($padding)));
    $styles[] = 'padding-top: ' . $padding . 'px';
    $styles[] = 'padding-bottom: ' . $padding . 'px';

    return !empty($styles) ? ' style="' . implode('; ', $styles) . ';"' : '';
}

/**
 * Génère la classe CSS pour le background du footer principal
 *
 * @return string Classe CSS
 */
function footer_get_main_background_class() {
    $color_choice = get_field('footer_background_color', 'option') ?: 'theme4';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Affiche le logo du footer avec lien vers homepage
 *
 * @return void
 */
function footer_display_logo() {
    $logo = get_field('footer_logo', 'option');
    $logo_height = get_field('footer_logo_height', 'option') ?: 40;

    if (!$logo) {
        return;
    }

    $logo_url = is_array($logo) ? $logo['url'] : $logo;

    if ($logo_url) {
        echo '<a href="' . esc_url(home_url()) . '" class="logoFooter">';
        echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . ' Logo" style="height: ' . intval($logo_height) . 'px; width: auto;">';
        echo '</a>';
    }
}

/**
 * Affiche les lignes d'adresse du footer
 *
 * @return void
 */
function footer_display_address_lines() {
    $lines = get_field('footer_address_lines', 'option');

    if (!$lines || !is_array($lines)) {
        return;
    }

    foreach ($lines as $line) {
        if (!empty($line['line'])) {
            echo '<p>' . esc_html($line['line']) . '</p>';
        }
    }
}

/**
 * Affiche les icônes des réseaux sociaux depuis le repeater social_links
 *
 * @param string $class_list Classes CSS additionnelles pour la liste <ul>
 * @return void
 */
function footer_display_social_icons($class_list = '') {
    $social_links = get_field('social_links', 'option');

    if (!$social_links || !is_array($social_links)) {
        echo '<!-- Aucun réseau social configuré -->';
        return;
    }

    $has_socials = false;

    echo '<ul class="socials ' . esc_attr($class_list) . '">';

    foreach ($social_links as $social) {
        $url = $social['url'] ?? '';
        $icon_html = $social['icon_html'] ?? '';

        // Afficher uniquement si URL et icône sont remplis
        if ($url && $icon_html) {
            $has_socials = true;
            echo '<li>';
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
            echo wp_kses_post($icon_html);
            echo '</a>';
            echo '</li>';
        }
    }

    echo '</ul>';

    if (!$has_socials) {
        echo '<!-- Aucun réseau social configuré -->';
    }
}

/**
 * Retourne la classe de background pour footer-info
 *
 * @return string Classe CSS
 */
function footer_info_get_background_class() {
    $color_choice = get_field('footer_info_background_color', 'option') ?: 'theme4';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Retourne les styles inline pour footer-info (background custom + padding + border)
 *
 * @return string Style inline
 */
function footer_info_get_style() {
    $styles = [];

    // Background color
    $color_choice = get_field('footer_info_background_color', 'option') ?: 'theme4';
    if ($color_choice === 'custom') {
        $custom_color = get_field('footer_info_background_custom_color', 'option');
        if ($custom_color) {
            $styles[] = 'background-color: ' . esc_attr($custom_color);
        }
    }

    // Padding
    $padding = get_field('footer_info_padding', 'option') ?: 20;
    $padding = max(0, min(200, intval($padding)));
    $styles[] = 'padding-top: ' . $padding . 'px';
    $styles[] = 'padding-bottom: ' . $padding . 'px';

    // Border top
    $border = get_field('footer_info_border_top', 'option');
    if ($border) {
        $styles[] = 'border-top: ' . esc_attr($border);
    }

    return !empty($styles) ? ' style="' . implode('; ', $styles) . ';"' : '';
}

/**
 * Retourne la classe de couleur de texte pour footer-info
 *
 * @return string Classe CSS (text-light ou text-dark)
 */
function footer_info_get_text_class() {
    $text_color = get_field('footer_info_text_color', 'option') ?: 'dark';
    return 'text-' . esc_attr($text_color);
}

// =============================================================================
// 4. BANNIÈRES - FONCTIONS DE GESTION DES BANNIÈRES
// =============================================================================

// BANNIÈRE TEXTE / ALERTE
// ========================

/**
 * Vérifie si la bannière texte doit être affichée
 * Prend en compte l'activation et les dates de programmation
 *
 * @return bool True si la bannière texte doit être affichée
 */
function banniere_texte_should_display() {
    $activer = get_field('banniere_texte_activer', 'option');
    if (!$activer) {
        return false;
    }

    // Vérifier les dates de programmation
    $date_debut = get_field('banniere_texte_date_debut', 'option');
    $date_fin = get_field('banniere_texte_date_fin', 'option');
    $now = current_time('Y-m-d H:i:s');

    if ($date_debut && $now < $date_debut) {
        return false;
    }

    if ($date_fin && $now > $date_fin) {
        return false;
    }

    return true;
}

/**
 * Retourne la classe de background pour la bannière texte
 *
 * @return string Classe CSS
 */
function banniere_texte_get_bg_class() {
    $color_choice = get_field('banniere_texte_bg_color', 'option') ?: 'theme1';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Retourne le style inline pour le background de la bannière texte
 *
 * @return string Style inline ou chaîne vide
 */
function banniere_texte_get_bg_style() {
    $color_choice = get_field('banniere_texte_bg_color', 'option') ?: 'theme1';

    if ($color_choice === 'custom') {
        $custom_color = get_field('banniere_texte_bg_custom', 'option');
        if ($custom_color) {
            return ' style="background-color: ' . esc_attr($custom_color) . ';"';
        }
    }

    return '';
}

/**
 * Retourne la classe de couleur de texte pour la bannière texte
 *
 * @return string Classe CSS (text-light ou text-dark)
 */
function banniere_texte_get_text_class() {
    $text_color = get_field('banniere_texte_text_color', 'option') ?: 'light';
    return 'text-' . esc_attr($text_color);
}

/**
 * Retourne la classe pour masquer la bannière texte sur mobile
 *
 * @return string Classe CSS (d-none d-md-block ou vide)
 */
function banniere_texte_get_mobile_class() {
    $masquer_mobile = get_field('banniere_texte_masquer_mobile', 'option');
    return $masquer_mobile ? 'd-none d-md-block' : '';
}

// BANNIÈRE SOCIALS + MENU
// ========================

/**
 * Vérifie si la bannière socials doit être affichée
 *
 * @return bool True si la bannière socials doit être affichée
 */
function banniere_socials_should_display() {
    $activer = get_field('banniere_socials_activer', 'option');
    return (bool) $activer;
}

/**
 * Retourne la classe de background pour la bannière socials
 *
 * @return string Classe CSS
 */
function banniere_socials_get_bg_class() {
    $color_choice = get_field('banniere_socials_bg_color', 'option') ?: 'theme6';

    if ($color_choice !== 'custom') {
        return 'bg-' . esc_attr($color_choice);
    }

    return '';
}

/**
 * Retourne le style inline pour le background de la bannière socials
 *
 * @return string Style inline ou chaîne vide
 */
function banniere_socials_get_bg_style() {
    $color_choice = get_field('banniere_socials_bg_color', 'option') ?: 'theme6';

    if ($color_choice === 'custom') {
        $custom_color = get_field('banniere_socials_bg_custom', 'option');
        if ($custom_color) {
            return ' style="background-color: ' . esc_attr($custom_color) . ';"';
        }
    }

    return '';
}

/**
 * Retourne la classe de couleur de texte pour la bannière socials
 *
 * @return string Classe CSS (text-light ou text-dark)
 */
function banniere_socials_get_text_class() {
    $text_color = get_field('banniere_socials_text_color', 'option') ?: 'light';
    return 'text-' . esc_attr($text_color);
}

/**
 * Retourne la classe pour masquer la bannière socials sur mobile
 *
 * @return string Classe CSS (d-none d-md-block ou vide)
 */
function banniere_socials_get_mobile_class() {
    $masquer_mobile = get_field('banniere_socials_masquer_mobile', 'option');
    return $masquer_mobile ? 'd-none d-md-block' : '';
}

/**
 * Affiche les icônes des réseaux sociaux depuis le repeater social_links
 * Utilisable pour bannière et footer
 *
 * @param string $class_list Classes CSS additionnelles pour la liste <ul>
 * @return void
 */
function display_social_icons($class_list = '') {
    $social_links = get_field('social_links', 'option');

    if (!$social_links || !is_array($social_links)) {
        echo '<!-- Aucun réseau social configuré -->';
        return;
    }

    $has_socials = false;

    echo '<ul class="socials ' . esc_attr($class_list) . '">';

    foreach ($social_links as $social) {
        $url = $social['url'] ?? '';
        $icon_html = $social['icon_html'] ?? '';

        // Afficher uniquement si URL et icône sont remplis
        if ($url && $icon_html) {
            $has_socials = true;
            echo '<li>';
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
            echo wp_kses_post($icon_html);
            echo '</a>';
            echo '</li>';
        }
    }

    echo '</ul>';

    if (!$has_socials) {
        echo '<!-- Aucun réseau social configuré -->';
    }
}
/**
 * Génère le style inline pour un heading en fonction de l'override et du tag
 *
 * @param string $heading_tag La balise heading (h1, h2, h3, etc.)
 * @param int $override Valeur d'override (0 = utilise la variable CSS globale)
 * @return string Attribut style complet ou vide
 */
function flexible_get_heading_style($heading_tag, $override = 0) {
    $override = intval($override);

    if ($override > 0) {
        return ' style="font-size: ' . $override . 'px;"';
    }

    // Pas d'override : utiliser la variable CSS globale correspondant au tag
    $tag_lower = strtolower($heading_tag);
    if (in_array($tag_lower, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
        return ' style="font-size: var(--' . $tag_lower . '-font-size);"';
    }

    return '';
}
