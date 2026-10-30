<?php
/**
 * Référencement : la page de confirmation « Merci » n'est pas indexée par les moteurs de recherche.
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
