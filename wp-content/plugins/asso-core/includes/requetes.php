<?php
/**
 * Boucles de requête natives : tri « à venir / passées » et filtre par section.
 *
 * Une boucle de requête (core/query) s'en sert en ajoutant à son attribut « query » :
 * - "assoPeriode": "a-venir" ou "passees" (activités, triées par date de début) ;
 * - "assoFiltreSection": true (applique le filtre ?section=dallas|oklahoma de l'adresse).
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Section demandée dans l'adresse (?section=…), si elle existe.
 *
 * @return string Identifiant de la section, ou chaîne vide.
 */
function asso_core_section_demandee() {
	// Simple filtre d'affichage en lecture seule : pas de nonce nécessaire.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$section = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
	return ( $section && term_exists( $section, 'asso_section' ) ) ? $section : '';
}

/**
 * Ajuste les boucles de requête qui portent les clés « assoPeriode » ou « assoFiltreSection ».
 *
 * @param array    $requete Arguments de WP_Query.
 * @param WP_Block $bloc    Bloc core/post-template.
 * @return array
 */
function asso_core_filtrer_boucles( $requete, $bloc ) {
	$options = isset( $bloc->context['query'] ) ? (array) $bloc->context['query'] : array();

	if ( ! empty( $options['assoPeriode'] ) ) {
		$a_venir = 'a-venir' === $options['assoPeriode'];
		// phpcs:disable WordPress.DB.SlowDBQuery -- peu d'activités ; tri indispensable.
		$requete['meta_key']   = 'asso_debut';
		$requete['orderby']    = 'meta_value';
		$requete['order']      = $a_venir ? 'ASC' : 'DESC';
		$requete['meta_query'] = array(
			array(
				'key'     => 'asso_debut',
				'value'   => asso_core_debut_du_jour(),
				'compare' => $a_venir ? '>=' : '<',
			),
		);
		// phpcs:enable WordPress.DB.SlowDBQuery
	}

	if ( ! empty( $options['assoAvecCouverture'] ) ) {
		// Seulement les contenus qui ont une image mise en avant (mosaïque de l'accueil).
		$requete['meta_query']   = isset( $requete['meta_query'] ) ? (array) $requete['meta_query'] : array(); // phpcs:ignore WordPress.DB.SlowDBQuery
		$requete['meta_query'][] = array(
			'key'     => '_thumbnail_id',
			'compare' => 'EXISTS',
		);
	}

	if ( ! empty( $options['assoFiltreSection'] ) ) {
		$section = asso_core_section_demandee();
		if ( $section ) {
			// Une activité « des deux sections » concerne aussi Dallas et l'Oklahoma.
			$requete['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'asso_section',
					'field'    => 'slug',
					'terms'    => array( $section, 'les-deux-sections' ),
				),
			);
		}
	}

	return $requete;
}
add_filter( 'query_loop_block_query_vars', 'asso_core_filtrer_boucles', 10, 2 );
