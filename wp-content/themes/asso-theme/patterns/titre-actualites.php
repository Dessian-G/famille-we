<?php
/**
 * Title: Titre de la page des actualités
 * Slug: asso-theme/titre-actualites
 * Inserter: no
 * Description: H1 de la page des articles, repris du titre de la page choisie dans Réglages > Lecture.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_page_articles = (int) get_option( 'page_for_posts' );
$asso_titre         = $asso_page_articles ? get_the_title( $asso_page_articles ) : __( 'Actualités', 'asso-theme' );
?>
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo esc_html( $asso_titre ); ?></h1>
<!-- /wp:heading -->
