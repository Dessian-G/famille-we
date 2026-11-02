<?php
/**
 * Title: Fiche d'une activité
 * Slug: asso-theme/fiche-activite
 * Inserter: no
 * Description: Page d'une activité : date, lieu, section, tarif, boutons d'inscription et de carte, contenu, album.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_lier_bouton = static function ( $cle ) {
	return '"metadata":{"bindings":{"url":{"source":"asso-core/activite","args":{"cle":"' . sanitize_key( $cle ) . '"}}}}';
};

// Balisage de blocs fixe produit par les fonctions du thème.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"className":"asso-entete-page","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|30"},"margin":{"bottom":"var:preset|spacing|30"},"blockGap":"0.75rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group asso-entete-page" style="margin-bottom:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:paragraph {<?php echo asso_theme_liaison( 'date_longue' ); ?>,"style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase"><?php echo esc_html__( 'Date de l’activité', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"level":1} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-fiche-infos","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"},"blockGap":"0.6rem"}},"backgroundColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group asso-fiche-infos has-blanc-background-color has-background" style="border-radius:24px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:paragraph {<?php echo asso_theme_liaison( 'quand' ); ?>} -->
<p><?php echo esc_html__( 'Date&nbsp;: à préciser', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'lieu' ); ?>} -->
<p><?php echo esc_html__( 'Lieu&nbsp;: à préciser', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'section' ); ?>} -->
<p><?php echo esc_html__( 'Section&nbsp;: à préciser', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'tarif' ); ?>} -->
<p><?php echo esc_html__( 'Tarif&nbsp;: à préciser', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--20)"><!-- wp:button {"className":"asso-si-lien",<?php echo $asso_lier_bouton( 'lien_url' ); ?>} -->
<div class="wp-block-button asso-si-lien"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'S’inscrire ou payer', 'asso-theme' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline asso-si-lien",<?php echo $asso_lier_bouton( 'carte_url' ); ?>} -->
<div class="wp-block-button is-style-outline asso-si-lien"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Voir sur la carte', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->

<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline asso-si-lien",<?php echo $asso_lier_bouton( 'album_url' ); ?>} -->
<div class="wp-block-button is-style-outline asso-si-lien"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Voir l’album photo', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/activites/' ) ); ?>"><?php echo esc_html__( 'Toutes les activités', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph -->
