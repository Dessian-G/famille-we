<?php
/**
 * Title: Pied de page
 * Slug: asso-theme/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Logotype et slogan, liens rapides, contact, puis ligne du bas (©, mentions, confidentialité).
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_courriel = 'famillewededallas@gmail.com';

/*
 * Attributs JSON d'un lien de menu : libellé traduisible, adresse construite avec home_url().
 */
$asso_lien = static function ( $libelle, $chemin ) {
	return wp_json_encode(
		array(
			'label' => $libelle,
			'url'   => home_url( $chemin ),
			'kind'  => 'custom',
		)
	);
};

$asso_menu = wp_json_encode(
	array(
		'ariaLabel'   => __( 'Liens rapides', 'asso-theme' ),
		'overlayMenu' => 'never',
		'layout'      => array(
			'type'        => 'flex',
			'orientation' => 'horizontal',
			'flexWrap'    => 'wrap',
		),
		'style'       => array(
			'spacing' => array( 'blockGap' => '0 1.25rem' ),
		),
	)
);

/*
 * Icônes des réseaux sociaux, toujours affichées (place réservée, demande de Max du 07/11/2026).
 * Adresse saisie dans Réglages > Réseaux sociaux (asso-core) : vrai lien.
 * Pas encore d'adresse : icône pâle, non cliquable, annoncée « bientôt disponible ». Jamais de lien mort.
 */
$asso_adresses_reseaux = function_exists( 'asso_core_reseaux' ) ? asso_core_reseaux() : array();
$asso_libelles_reseaux = array(
	'facebook'  => __( 'Page Facebook de La Famille Wê', 'asso-theme' ),
	'instagram' => __( 'Compte Instagram de La Famille Wê', 'asso-theme' ),
);
$asso_icones_reseaux = '';
foreach ( $asso_libelles_reseaux as $asso_service => $asso_libelle ) {
	$asso_icone = function_exists( 'block_core_social_link_get_icon' ) ? block_core_social_link_get_icon( $asso_service ) : '';
	$asso_url   = isset( $asso_adresses_reseaux[ $asso_service ] ) ? $asso_adresses_reseaux[ $asso_service ] : '';
	if ( $asso_url ) {
		$asso_icones_reseaux .= sprintf(
			'<li><a href="%1$s">%2$s<span class="screen-reader-text">%3$s</span></a></li>',
			esc_url( $asso_url ),
			$asso_icone,
			esc_html( $asso_libelle )
		);
	} else {
		$asso_icones_reseaux .= sprintf(
			'<li><span class="est-en-attente">%1$s<span class="screen-reader-text">%2$s</span></span></li>',
			$asso_icone,
			/* translators: %s: nom du réseau (ex. « Page Facebook de La Famille Wê »). */
			esc_html( sprintf( __( '%s : bientôt disponible', 'asso-theme' ), $asso_libelle ) )
		);
	}
}

// Les sorties JSON ci-dessous sont des attributs de blocs produits par wp_json_encode().
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"align":"full","className":"asso-pied","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|20"},"margin":{"top":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|blanc"},":hover":{"color":{"text":"var:preset|color|blanc"}}}}},"backgroundColor":"vert","textColor":"blanc","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull asso-pied has-blanc-color has-vert-background-color has-text-color has-background has-link-color" style="margin-top:0;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"asso-logotype","style":{"typography":{"fontWeight":"800","lineHeight":"1.1","letterSpacing":"-0.02em"}},"fontFamily":"bricolage","fontSize":"large"} -->
<p class="asso-logotype has-bricolage-font-family has-large-font-size" style="font-weight:800;letter-spacing:-0.02em;line-height:1.1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="asso-logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-famille-we-256.webp' ) ); ?>" alt="" width="64" height="64" loading="lazy"><span class="asso-logotype__texte">La Famille Wê</span></a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Ensemble Allons Plus Loin', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"blanc","fontSize":"large"} -->
<h2 class="wp-block-heading has-blanc-color has-text-color has-large-font-size"><?php echo esc_html__( 'Liens rapides', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:navigation <?php echo $asso_menu; ?> -->
<!-- wp:navigation-link <?php echo $asso_lien( __( 'Qui sommes-nous', 'asso-theme' ), '/qui-sommes-nous/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Activités', 'asso-theme' ), '/activites/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Adhésion', 'asso-theme' ), '/adhesion/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Faire un don', 'asso-theme' ), '/faire-un-don/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'FAQ', 'asso-theme' ), '/faq/' ); ?> /-->
<!-- /wp:navigation --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"blanc","fontSize":"large"} -->
<h2 class="wp-block-heading has-blanc-color has-text-color has-large-font-size"><?php echo esc_html__( 'Contact', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( 'mailto:' . antispambot( $asso_courriel ) ); ?>"><?php echo esc_html( antispambot( $asso_courriel ) ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<ul class="asso-reseaux" aria-label="<?php echo esc_attr__( 'Réseaux sociaux', 'asso-theme' ); ?>"><?php echo $asso_icones_reseaux; ?></ul>
<!-- /wp:html --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"},"padding":{"top":"var:preset|spacing|20"}},"border":{"top":{"color":"#ffffff59","style":"solid","width":"1px"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"fontSize":"small"} -->
<div class="wp-block-group alignwide has-small-font-size" style="border-top-color:#ffffff59;border-top-style:solid;border-top-width:1px;margin-top:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> La Famille Wê. <?php echo esc_html__( 'Tous droits réservés.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php echo esc_html__( 'Mentions légales', 'asso-theme' ); ?></a> · <a href="<?php echo esc_url( home_url( '/confidentialite/' ) ); ?>"><?php echo esc_html__( 'Confidentialité', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
