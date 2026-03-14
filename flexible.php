<?php
/**
 * Flexible Theme Builder
 *
 * @package Bootscore_Child
 */

// Récupérer le gap global
$gap_x = get_field('gap_x') ?? 20;

// Récupérer les blocs flexibles
$flexilder = get_field('flexilder');

if ( $flexilder ) : ?>
    <div id="flexilder" style="--gap-x: <?php echo intval($gap_x); ?>px;">
    <?php
    foreach ( $flexilder as $bloc ) :
        $layout = $bloc['acf_fc_layout'];

        // Mapping des layouts vers les fichiers de template
        $template_map = [
            'image_texte' => 'bloc-image-texte.php',
            'hero' => 'bloc-hero.php',
            'titre' => 'bloc-titre.php',
            'buttons' => 'bloc-buttons.php',
            'accordion' => 'bloc-accordion.php',
            'tabs' => 'bloc-tabs.php',
            'colonnes_multiples' => 'bloc-colonnes-multiples.php',
            'contact_form' => 'bloc-contact-form.php',
            'colonnes_composer' => 'bloc-colonnes-composer.php',
            'price_cards' => 'bloc-price-cards.php',
            'carousel' => 'bloc-carousel.php',
        ];

        // Charger le template correspondant si il existe
        if ( isset($template_map[$layout]) ) {
            $template_file = get_stylesheet_directory() . '/template-parts/flexible-blocks/' . $template_map[$layout];
            if ( file_exists($template_file) ) {
                include $template_file;
            }
        }
    endforeach;
    ?>
    </div>
<?php endif;
