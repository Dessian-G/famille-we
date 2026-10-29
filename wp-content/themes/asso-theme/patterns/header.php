<?php
/**
 * Title: En-tête
 * Slug: asso-theme/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 * Description: Bande tricolore, logotype, menu principal et bouton « Faire un don ».
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

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
		'ariaLabel'             => __( 'Menu principal', 'asso-theme' ),
		'className'             => 'asso-nav',
		'overlayMenu'           => 'mobile',
		'overlayBackgroundColor' => 'blanc',
		'overlayTextColor'      => 'encre',
		'openSubmenusOnClick'   => true,
		'layout'                => array(
			'type'           => 'flex',
			'justifyContent' => 'right',
			'flexWrap'       => 'nowrap',
		),
		'style'                 => array(
			'spacing' => array( 'blockGap' => 'var:preset|spacing|20' ),
		),
	)
);

$asso_sous_menu = wp_json_encode(
	array(
		'label' => __( "L'association", 'asso-theme' ),
		'kind'  => 'custom',
	)
);

// Les sorties JSON ci-dessous sont des attributs de blocs produits par wp_json_encode().
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"align":"full","className":"asso-bande-tricolore","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"dimensions":{"minHeight":"4px"}},"gradient":"tricolore","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull asso-bande-tricolore has-tricolore-gradient-background has-background" style="min-height:4px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"asso-entete","style":{"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem"},"margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull asso-entete" style="margin-top:0;padding-top:0.75rem;padding-bottom:0.75rem"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"className":"asso-logotype","style":{"typography":{"fontSize":"clamp(1.25rem, 1rem + 0.7vw, 1.625rem)","fontWeight":"800","lineHeight":"1.1","letterSpacing":"-0.02em"}},"fontFamily":"bricolage"} -->
<p class="asso-logotype has-bricolage-font-family" style="font-size:clamp(1.25rem, 1rem + 0.7vw, 1.625rem);font-weight:800;letter-spacing:-0.02em;line-height:1.1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="asso-logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-famille-we-256.webp' ) ); ?>" alt="" width="52" height="52"><span class="asso-logotype__texte">La Famille <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-orange-profond-color">WE</mark></span></a></p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:navigation <?php echo $asso_menu; ?> -->
<!-- wp:navigation-link <?php echo $asso_lien( __( 'Accueil', 'asso-theme' ), '/' ); ?> /-->

<!-- wp:navigation-submenu <?php echo $asso_sous_menu; ?> -->
<!-- wp:navigation-link <?php echo $asso_lien( __( 'Qui sommes-nous', 'asso-theme' ), '/qui-sommes-nous/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Le bureau', 'asso-theme' ), '/le-bureau/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Nos sections', 'asso-theme' ), '/nos-sections/' ); ?> /-->
<!-- /wp:navigation-submenu -->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Activités', 'asso-theme' ), '/activites/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Galerie', 'asso-theme' ), '/galerie/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Actualités', 'asso-theme' ), '/actualites/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Adhésion', 'asso-theme' ), '/adhesion/' ); ?> /-->

<!-- wp:navigation-link <?php echo $asso_lien( __( 'Contact', 'asso-theme' ), '/contact/' ); ?> /-->
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"asso-entete-don"} -->
<div class="wp-block-buttons asso-entete-don"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/faire-un-don/' ) ); ?>"><?php echo esc_html__( 'Faire un don', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
