<?php

/**
 * The header for our theme
 * Template Version: 6.3.1
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?= esc_attr(get_bloginfo('charset')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">
  
  <!-- Skip Links -->
  <a class="skip-link visually-hidden-focusable" href="#primary"><?php esc_html_e( 'Skip to content', 'bootscore' ); ?></a>
  <a class="skip-link visually-hidden-focusable" href="#footer"><?php esc_html_e( 'Skip to footer', 'bootscore' ); ?></a>

  <!-- Top Bar Widget -->
  <?php if (is_active_sidebar('top-bar')) : ?>
    <?php dynamic_sidebar('top-bar'); ?>
  <?php endif; ?>
  
  <?php do_action( 'bootscore_before_masthead' ); ?>

  <header id="masthead" class="<?= esc_attr(apply_filters('bootscore/class/header', 'position-fixed w-100 top-0 start-0')); ?> site-header <?= esc_attr(header_get_masthead_background_class()); ?> <?= esc_attr(header_get_menu_text_class()); ?>"<?= header_get_masthead_background_style(); ?>>

    <?php get_template_part('template-parts/header/banniere'); ?>

    <?php do_action( 'bootscore_after_masthead_open' ); ?>
    
    <nav id="nav-main" class="navbar <?= esc_attr(apply_filters('bootscore/class/header/navbar/breakpoint', 'navbar-expand-lg')); ?>">

      <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'header')); ?>">
        
        <?php do_action( 'bootscore_before_navbar_brand' ); ?>
        
        <!-- Navbar Brand -->
        <?php
        // Récupérer les logos depuis la page d'options SCF
        $logo_desktop = get_field('logo_desktop', 'option');
        $logo_desktop_height = get_field('logo_desktop_height', 'option') ?: 40;
        $logo_mobile = get_field('logo_mobile', 'option');
        $logo_mobile_height = get_field('logo_mobile_height', 'option') ?: 30;

        // URL des images
        $logo_desktop_url = is_array($logo_desktop) ? $logo_desktop['url'] : $logo_desktop;
        $logo_mobile_url = is_array($logo_mobile) ? $logo_mobile['url'] : $logo_mobile;
        ?>
        <a class="<?= esc_attr(apply_filters('bootscore/class/header/navbar-brand', 'navbar-brand')); ?>" href="<?= esc_url(home_url()); ?>">
          <?php if ($logo_desktop_url) : ?>
            <img src="<?= esc_url($logo_desktop_url); ?>" alt="<?= esc_attr(get_bloginfo('name')); ?> Logo" class="d-md-block d-none" style="height: <?= intval($logo_desktop_height); ?>px; width: auto;">
          <?php endif; ?>
          <?php if ($logo_mobile_url) : ?>
            <img src="<?= esc_url($logo_mobile_url); ?>" alt="<?= esc_attr(get_bloginfo('name')); ?> Logo" class="d-md-none" style="height: <?= intval($logo_mobile_height); ?>px; width: auto;">
          <?php endif; ?>
        </a>  
        
        <?php do_action( 'bootscore_after_navbar_brand' ); ?>

        
        <div class="header-actions <?= esc_attr(apply_filters('bootscore/class/header-actions', 'd-flex align-items-center')); ?>">
          <!-- Offcanvas Navbar -->
          <div class="offcanvas offcanvas-<?= esc_attr(apply_filters('bootscore/class/header/offcanvas/direction', 'end', 'menu')); ?> <?= esc_attr(header_get_offcanvas_background_class()); ?> <?= esc_attr(header_get_offcanvas_text_class()); ?>" tabindex="-1" id="offcanvas-navbar"<?= header_get_offcanvas_background_style(); ?>>
            <div class="offcanvas-header <?= esc_attr(apply_filters('bootscore/class/offcanvas/header', '', 'menu')); ?>">
              <span class="h5 offcanvas-title <?= esc_attr(header_get_offcanvas_text_class()); ?>"><?= esc_html(apply_filters('bootscore/offcanvas/navbar/title', __('Menu', 'bootscore'))); ?></span>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><?= header_get_offcanvas_menu_close_icon(); ?></button>
            </div>
            <div class="offcanvas-body <?= esc_attr(apply_filters('bootscore/class/offcanvas/body', '', 'menu')); ?>">
  
              <!-- Bootstrap 5 Nav Walker Main Menu -->
              <?php get_template_part('template-parts/header/main-menu'); ?>
  
              <!-- Top Nav 2 Widget -->
              <?php if (is_active_sidebar('top-nav-2')) : ?>
                <?php dynamic_sidebar('top-nav-2'); ?>
              <?php endif; ?>
  
            </div>
          </div>

          <!-- Search CTA -->
          <?php if (function_exists('header_search_should_display') && header_search_should_display()) : ?>
            <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'search-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-search" aria-controls="offcanvas-search" aria-label="<?php esc_attr_e('Search toggler', 'bootscore'); ?>">
              <?= header_get_search_icon(); ?> <span class="visually-hidden-focusable">Search</span>
            </button>
          <?php endif; ?>

          <?php
          if (class_exists('WooCommerce')) :
            get_template_part('template-parts/header/actions', 'woocommerce');
          else :
            get_template_part('template-parts/header/actions');
          endif;
          ?>

          <!-- Navbar Toggler -->
          <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', '', 'nav-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/navbar/toggler/breakpoint', 'd-lg-none')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'nav-toggler')); ?> nav-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar" aria-label="<?php esc_attr_e( 'Toggle main menu', 'bootscore' ); ?>">
            <?= header_get_menu_toggle_icon(); ?> <span class="visually-hidden-focusable">Menu</span>
          </button>
          
          <?php do_action( 'bootscore_after_nav_toggler' ); ?>

        </div><!-- .header-actions -->

      </div><!-- .container -->

    </nav><!-- .navbar -->

    <?php
    if (class_exists('WooCommerce')) :
      get_template_part('template-parts/header/collapse-search', 'woocommerce');
    else :
      get_template_part('template-parts/header/collapse-search');
    endif;
    ?>

    <!-- Offcanvas User and Cart -->
    <?php
    if (class_exists('WooCommerce')) :
      get_template_part('template-parts/header/offcanvas', 'woocommerce');
    endif;
    ?>

    <!-- Offcanvas Search -->
    <?php get_template_part('template-parts/header/offcanvas-search'); ?>

    <?php do_action( 'bootscore_before_masthead_close' ); ?>
    
  </header><!-- #masthead -->
  
  <?php do_action( 'bootscore_after_masthead' ); ?>
