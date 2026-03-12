<?php
/**
 * Template Name: Flexible Content
 *
 * @package Bootscore_Child
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<div id="content" class="site-content">
  <div id="primary" class="content-area">

    <?php do_action('bootscore_after_primary_open', 'page-flexibleContent'); ?>

    <main id="main" class="site-main">

      <?php
      // Inclusion du fichier flexible.php pour le theme builder ACF
      if (file_exists(get_stylesheet_directory() . '/flexible.php')) {
        include get_stylesheet_directory() . '/flexible.php';
      }
      ?>

    </main>

  </div>
</div>

<?php
get_footer();
