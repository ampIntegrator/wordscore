<?php
/**
 * Template part pour les bannières du header
 *
 * @package Bootscore_Child
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Vérifier si au moins une bannière doit être affichée
$texte_actif = banniere_texte_should_display();
$socials_actif = banniere_socials_should_display();

if (!$texte_actif && !$socials_actif) {
    return;
}
?>

<div id="bannieres">

    <?php if ($texte_actif) : ?>
        <?php
        // Bannière Texte/Alerte
        $texte = get_field('banniere_texte_contenu', 'option');
        $lien_url = get_field('banniere_texte_lien_url', 'option');
        $lien_texte = get_field('banniere_texte_lien_texte', 'option');

        $bg_class = banniere_texte_get_bg_class();
        $bg_style = banniere_texte_get_bg_style();
        $text_class = banniere_texte_get_text_class();
        $mobile_class = banniere_texte_get_mobile_class();
        ?>
        <div class="banniere banniere-texte <?= esc_attr($bg_class); ?> <?= esc_attr($text_class); ?> <?= esc_attr($mobile_class); ?>"<?= $bg_style; ?>>
            <div class="container">
                <div class="text-center">
                    <?php if ($texte) : ?>
                        <span class="banniere-texte-content"><?= esc_html($texte); ?></span>
                    <?php endif; ?>

                    <?php if ($lien_url && $lien_texte) : ?>
                        <a href="<?= esc_url($lien_url); ?>" class="banniere-lien"><?= esc_html($lien_texte); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($socials_actif) : ?>
        <?php
        // Bannière Socials + Menu
        $ordre = get_field('banniere_socials_ordre', 'option') ?: 'socials_menu';

        $bg_class = banniere_socials_get_bg_class();
        $bg_style = banniere_socials_get_bg_style();
        $text_class = banniere_socials_get_text_class();
        $mobile_class = banniere_socials_get_mobile_class();
        ?>
        <div class="banniere banniere-socials <?= esc_attr($bg_class); ?> <?= esc_attr($text_class); ?> <?= esc_attr($mobile_class); ?>"<?= $bg_style; ?>>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">

                    <?php if ($ordre === 'socials_menu') : ?>
                        <!-- Socials à gauche, Menu à droite -->
                        <div class="banniere-socials-wrapper">
                            <?php display_social_icons('banniere-social-list'); ?>
                        </div>

                        <div class="banniere-menu-wrapper">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'banniere-menu',
                                'container'      => false,
                                'menu_class'     => 'nav banniere-nav',
                                'fallback_cb'    => '__return_false',
                                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                                'depth'          => 1,
                                'walker'         => new bootstrap_5_wp_nav_menu_walker()
                            ));
                            ?>
                        </div>

                    <?php else : ?>
                        <!-- Menu à gauche, Socials à droite -->
                        <div class="banniere-menu-wrapper">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'banniere-menu',
                                'container'      => false,
                                'menu_class'     => 'nav banniere-nav',
                                'fallback_cb'    => '__return_false',
                                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                                'depth'          => 1,
                                'walker'         => new bootstrap_5_wp_nav_menu_walker()
                            ));
                            ?>
                        </div>

                        <div class="banniere-socials-wrapper">
                            <?php display_social_icons('banniere-social-list'); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
