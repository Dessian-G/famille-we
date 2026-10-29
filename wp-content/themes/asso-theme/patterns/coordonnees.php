<?php
/**
 * Title: Coordonnées de l'association
 * Slug: asso-theme/coordonnees
 * Categories: asso-theme, contact
 * Description: E-mail de l'association, protégé contre les robots. Ajouter les réseaux sociaux ici quand ils existeront.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_courriel = 'famillewededallas@gmail.com';
?>
<!-- wp:paragraph -->
<p><strong><?php echo esc_html__( 'E-mail&nbsp;:', 'asso-theme' ); ?></strong> <a href="<?php echo esc_url( 'mailto:' . antispambot( $asso_courriel ) ); ?>"><?php echo esc_html( antispambot( $asso_courriel ) ); ?></a></p>
<!-- /wp:paragraph -->
