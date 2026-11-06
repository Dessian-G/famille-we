<?php
/**
 * Référencement : méta-description de chaque page et page « Merci » non indexée.
 *
 * La méta-description vient de l'extrait du contenu (champ « Extrait » de l'éditeur),
 * modifiable dans WP Admin sans toucher au code.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajoute « noindex » à la page Merci (adresse /merci/).
 *
 * @param array $robots Directives robots.
 * @return array
 */
function asso_core_merci_non_indexee( $robots ) {
	if ( is_page( 'merci' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'asso_core_merci_non_indexee' );

/**
 * Donne un champ « Extrait » aux pages, qui sert de méta-description.
 */
function asso_core_extrait_des_pages() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'asso_core_extrait_des_pages' );

/**
 * Texte de la méta-description de la page affichée.
 *
 * @return string Texte brut, vide s'il n'y en a pas.
 */
function asso_core_meta_description() {
	$texte = '';

	if ( is_singular() ) {
		$texte = get_the_excerpt( get_queried_object_id() );
	} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
		$texte = get_the_excerpt( (int) get_option( 'page_for_posts' ) );
	} elseif ( is_post_type_archive( 'asso_event' ) ) {
		// Textes des listes automatiques [À VALIDER].
		$texte = __( 'Les prochaines activités de La Famille Wê de Dallas & Oklahoma et le souvenir des précédentes, à Dallas et en Oklahoma.', 'asso-core' );
	} elseif ( is_post_type_archive( 'asso_album' ) ) {
		$texte = __( 'Les albums photos des activités de La Famille Wê de Dallas & Oklahoma.', 'asso-core' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$texte = term_description();
	}

	// Texte brut : balises retirées, entités décodées (&nbsp; devient une espace insécable, conservée).
	$texte = html_entity_decode( wp_strip_all_tags( (string) $texte ), ENT_QUOTES, 'UTF-8' );
	$texte = trim( preg_replace( '/[ \t\r\n]+/', ' ', $texte ) );
	return wp_html_excerpt( $texte, 160, '…' );
}

/**
 * Affiche la balise méta-description dans l'en-tête HTML.
 * Rien n'est ajouté si une extension SEO s'en charge déjà.
 */
function asso_core_afficher_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}
	$texte = asso_core_meta_description();
	if ( '' !== $texte ) {
		echo '<meta name="description" content="' . esc_attr( $texte ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'asso_core_afficher_meta_description', 1 );
