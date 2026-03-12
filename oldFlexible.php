<?php
/**
 * Template pour afficher le flexible content
 *
 * @package FlairClub_Child
 */

// Récupérer les blocs
$formation_blocs = get_field('formation_blocs');

if ( $formation_blocs ) :
    foreach ( $formation_blocs as $bloc ) :
        $layout = $bloc['acf_fc_layout'];

        switch ( $layout ) :

            // Bloc Image 1/3 + Texte 2/3
            case 'image_texte_1_3':
                $image = $bloc['image'];
                $contenu = $bloc['contenu'];
                $inverser = $bloc['inverser'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $order_class = ( $inverser === 'oui' ) ? 'reverse' : '';
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-image-texte bloc-1-3 <?php echo $order_class; ?>">
                            <div class="row">
                                <div class="col col-image col-33">
                                    <div class="innerCol image-bg" style="background-image: url('<?php echo esc_url($image); ?>');">
                                        XXX
                                    </div>
                                </div>
                                <div class="col col-texte col-66">
                                    <div class="innerCol">
                                        <div class="innerContent">
                                            <?php echo wp_kses_post($contenu); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Image 1/2 + Texte 1/2
            case 'image_texte_1_2':
                $image = $bloc['image'];
                $contenu = $bloc['contenu'];
                $inverser = $bloc['inverser'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $order_class = ( $inverser === 'oui' ) ? 'reverse' : '';
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-image-texte bloc-1-2 <?php echo $order_class; ?>">
                            <div class="row">
                                <div class="col col-image col-50">
                                    <div class="innerCol image-bg" style="background-image: url('<?php echo esc_url($image); ?>');">
                                    </div>
                                </div>
                                <div class="col col-texte col-50">
                                    <div class="innerCol">
                                        <div class="innerContent">
                                            <?php echo wp_kses_post($contenu); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Vidéo simple
            case 'video_simple':
                $type_video = $bloc['type_video'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-video bloc-video-simple">
                            <div class="row">
                                <div class="col col-100">
                                    <div class="innerCol">
                                        <div class="video-wrapper">
                                            <?php if ( $type_video === 'embed' && !empty($bloc['video_url']) ) :
                                                $video_url = $bloc['video_url'];
                                                // Détecter YouTube
                                                if ( strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false ) {
                                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video_url, $matches);
                                                    $video_id = $matches[1] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Détecter Vimeo
                                                elseif ( strpos($video_url, 'vimeo.com') !== false ) {
                                                    preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $video_url, $matches);
                                                    $video_id = $matches[3] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Autre iframe générique
                                                else {
                                                    echo '<iframe src="' . esc_url($video_url) . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            ?>
                                            <?php elseif ( $type_video === 'upload' && !empty($bloc['video_file']) ) :
                                                $video_file = $bloc['video_file'];
                                            ?>
                                                <video controls>
                                                    <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Deux vidéos
            case 'video_double':
                $type_video_1 = $bloc['type_video_1'];
                $type_video_2 = $bloc['type_video_2'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-video bloc-video-double">
                            <div class="row">
                                <div class="col col-50">
                                    <div class="innerCol">
                                        <div class="video-wrapper">
                                            <?php if ( $type_video_1 === 'embed' && !empty($bloc['video_url_1']) ) :
                                                $video_url = $bloc['video_url_1'];
                                                // Détecter YouTube
                                                if ( strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false ) {
                                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video_url, $matches);
                                                    $video_id = $matches[1] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Détecter Vimeo
                                                elseif ( strpos($video_url, 'vimeo.com') !== false ) {
                                                    preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $video_url, $matches);
                                                    $video_id = $matches[3] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Autre iframe générique
                                                else {
                                                    echo '<iframe src="' . esc_url($video_url) . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            ?>
                                            <?php elseif ( $type_video_1 === 'upload' && !empty($bloc['video_file_1']) ) :
                                                $video_file = $bloc['video_file_1'];
                                            ?>
                                                <video controls>
                                                    <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-50">
                                    <div class="innerCol">
                                        <div class="video-wrapper">
                                            <?php if ( $type_video_2 === 'embed' && !empty($bloc['video_url_2']) ) :
                                                $video_url = $bloc['video_url_2'];
                                                // Détecter YouTube
                                                if ( strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false ) {
                                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video_url, $matches);
                                                    $video_id = $matches[1] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Détecter Vimeo
                                                elseif ( strpos($video_url, 'vimeo.com') !== false ) {
                                                    preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $video_url, $matches);
                                                    $video_id = $matches[3] ?? '';
                                                    if ( $video_id ) {
                                                        echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                                    }
                                                }
                                                // Autre iframe générique
                                                else {
                                                    echo '<iframe src="' . esc_url($video_url) . '" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            ?>
                                            <?php elseif ( $type_video_2 === 'upload' && !empty($bloc['video_file_2']) ) :
                                                $video_file = $bloc['video_file_2'];
                                            ?>
                                                <video controls>
                                                    <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Colonnes multiples
            case 'colonnes_multiples':
                $colonnes = $bloc['colonnes'];
                $nb_colonnes = count($colonnes);
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $col_class = '';

                // Définir la largeur des colonnes selon le nombre
                switch ($nb_colonnes) {
                    case 2:
                        $col_class = 'col-50';
                        break;
                    case 3:
                        $col_class = 'col-33';
                        break;
                    case 4:
                        $col_class = 'col-25';
                        break;
                }

                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-colonnes-multiples">
                            <div class="row">
                                <?php foreach ($colonnes as $colonne) :
                                    $niveau_titre = $colonne['niveau_titre'] ?? 'h3';
                                ?>
                                    <div class="col <?php echo $col_class; ?>">
                                        <div class="innerCol">
                                            <div class="innerContent">
                                                <?php
                                                $tag = esc_html($niveau_titre);
                                                echo "<{$tag}>" . esc_html($colonne['titre']) . "</{$tag}>";
                                                ?>
                                                <p><?php echo nl2br(esc_html($colonne['texte'])); ?></p>

                                                <?php
                                                $type_video = $colonne['type_video'] ?? 'embed';
                                                if ($type_video === 'embed' && !empty($colonne['video_url'])) :
                                                    $video_url = $colonne['video_url'];
                                                    echo wp_oembed_get($video_url);
                                                elseif ($type_video === 'upload' && !empty($colonne['video_file'])) :
                                                    $video_file = $colonne['video_file'];
                                                    ?>
                                                    <video controls>
                                                        <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                                    </video>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Accordion + Image
            case 'accordion_image':
                $titre = $bloc['titre'];
                $accordion_items = $bloc['accordion_items'];
                $image = $bloc['image'];
                $inverser = $bloc['inverser'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $order_class = ( $inverser === 'oui' ) ? 'reverse' : '';
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                ?>
                <section class="bloc-section <?php echo $type_class . ' ' . $bg_class . ' ' . $padding_class; ?>">
                    <div class="container">
                        <div class="bloc bloc-accordion-image <?php echo $order_class; ?>">
                            <div class="row">
                                <div class="col col-66">
                                    <div class="innerCol">
                                        <div class="innerContent">
                                            <h2><?php echo esc_html($titre); ?></h2>
                                            <div class="accordion">
                                                <?php foreach ($accordion_items as $index => $item) : ?>
                                                    <div class="accordion-item">
                                                        <button class="accordion-header" aria-expanded="false">
                                                            <h3><?php echo esc_html($item['titre']); ?></h3>
                                                            <span class="accordion-icon">+</span>
                                                        </button>
                                                        <div class="accordion-content">
                                                            <div class="accordion-content-inner">
                                                                <?php echo wp_kses_post($item['contenu']); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-33">
                                    <div class="innerCol image-bg" style="background-image: url('<?php echo esc_url($image); ?>');">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;

            // Bloc Titre
            case 'bloc_titre':
                $texte = $bloc['texte'];
                $niveau_titre = $bloc['niveau_titre'];
                $alignement = $bloc['alignement'];
                $couleur_fond = $bloc['couleur_fond'] ?? '';
                $padding_top = isset($bloc['padding_top']) ? intval($bloc['padding_top']) : 40;
                $padding_bottom = isset($bloc['padding_bottom']) ? intval($bloc['padding_bottom']) : 40;
                $bg_class = $couleur_fond ? 'bg-' . $couleur_fond : '';
                $type_class = 'bloc-type-' . $layout;
                $padding_class = 'padding-top-' . $padding_top . ' padding-bottom-' . $padding_bottom;
                $align_class = 'text-' . $alignement;
                $color_class = ($couleur_fond === 'vert') ? 'text-white' : '';
                ?>
                <section class="bloc-section <?php echo "{$type_class} {$bg_class} {$padding_class}"; ?>">
                    <div class="container">
                        <div class="bloc bloc-titre <?php echo "{$align_class} {$color_class}"; ?>">
                            <?php
                            $tag = esc_html($niveau_titre);
                            echo "<{$tag}>" . esc_html($texte) . "</{$tag}>";
                            ?>
                        </div>
                    </div>
                </section>
                <?php
                break;

        endswitch;
    endforeach;
endif;
