<?php
/**
 * Source de liaison de blocs « asso-core/activite » : les blocs natifs (paragraphe, titre,
 * bouton) affichent les champs d'une activité ou d'un album sans bloc sur mesure.
 *
 * Exemple : <!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"asso-core/activite","args":{"cle":"lieu"}}}}} -->
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enregistre la source de liaison.
 */
function asso_core_enregistrer_liaisons() {
	register_block_bindings_source(
		'asso-core/activite',
		array(
			'label'              => __( 'Activité ou album (asso-core)', 'asso-core' ),
			'get_value_callback' => 'asso_core_valeur_liaison',
			'uses_context'       => array( 'postId', 'postType' ),
		)
	);
}
add_action( 'init', 'asso_core_enregistrer_liaisons' );

/**
 * Valeur d'une clé pour le contenu courant (boucle de requête ou page seule).
 *
 * @param array    $args Arguments de la liaison (« cle »).
 * @param WP_Block $bloc Bloc lié.
 * @return string|null Null : le contenu enregistré du bloc reste affiché.
 */
function asso_core_valeur_liaison( array $args, $bloc ) {
	$cle = isset( $args['cle'] ) ? sanitize_key( $args['cle'] ) : '';
	$id  = ! empty( $bloc->context['postId'] ) ? (int) $bloc->context['postId'] : (int) get_the_ID();
	if ( ! $cle || ! $id ) {
		return null;
	}
	switch ( get_post_type( $id ) ) {
		case 'asso_event':
			return asso_core_valeur_activite( $cle, $id );
		case 'asso_album':
			return asso_core_valeur_album( $cle, $id );
	}
	return null;
}

/**
 * Valeurs d'une activité.
 *
 * @param string $cle Clé demandée.
 * @param int    $id  Identifiant de l'activité.
 * @return string|null
 */
function asso_core_valeur_activite( $cle, $id ) {
	$debut      = asso_core_date( get_post_meta( $id, 'asso_debut', true ) );
	$a_preciser = __( 'à préciser', 'asso-core' );

	switch ( $cle ) {
		case 'jour':
			return $debut ? esc_html( asso_core_formater( $debut, 'j' ) ) : '–';
		case 'mois':
			return $debut ? esc_html( asso_core_mois_abrege( $debut ) ) : '';
		case 'date_longue':
			return $debut
				? esc_html( asso_core_formater( $debut, 'l j F Y' ) . ' · ' . asso_core_heure( $debut ) )
				: esc_html__( 'Date à préciser', 'asso-core' );
		case 'quand':
			$fin = asso_core_date( get_post_meta( $id, 'asso_fin', true ) );
			return asso_core_libelle( __( 'Date', 'asso-core' ), esc_html( asso_core_texte_quand( $debut, $fin ) ) );
		case 'lieu':
			$lieu = (string) get_post_meta( $id, 'asso_lieu', true );
			return asso_core_libelle( __( 'Lieu', 'asso-core' ), esc_html( '' !== $lieu ? $lieu : $a_preciser ) );
		case 'section':
			$sections = asso_core_noms_sections( $id );
			return asso_core_libelle( __( 'Section', 'asso-core' ), esc_html( '' !== $sections ? $sections : $a_preciser ) );
		case 'tarif':
			return asso_core_libelle( __( 'Tarif', 'asso-core' ), asso_core_texte_tarif( get_post_meta( $id, 'asso_tarif', true ) ) );
		case 'resume':
			$morceaux = array_filter(
				array(
					esc_html( (string) get_post_meta( $id, 'asso_lieu', true ) ),
					esc_html( asso_core_noms_sections( $id ) ),
					'' !== (string) get_post_meta( $id, 'asso_tarif', true ) ? asso_core_texte_tarif( get_post_meta( $id, 'asso_tarif', true ) ) : '',
				)
			);
			return $morceaux ? implode( ' · ', $morceaux ) : esc_html__( 'Détails à venir', 'asso-core' );
		case 'lien_url':
			return esc_url( (string) get_post_meta( $id, 'asso_lien', true ) );
		case 'carte_url':
			$adresse = (string) get_post_meta( $id, 'asso_adresse', true );
			return '' !== $adresse ? esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $adresse ) ) : '';
		case 'album_url':
			$albums = get_posts(
				array(
					'post_type'      => 'asso_album',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'no_found_rows'  => true,
					'meta_key'       => 'asso_activite', // phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_value'     => $id, // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			);
			return $albums ? esc_url( get_permalink( $albums[0] ) ) : '';
	}
	return null;
}

/**
 * Valeurs d'un album.
 *
 * @param string $cle Clé demandée.
 * @param int    $id  Identifiant de l'album.
 * @return string|null
 */
function asso_core_valeur_album( $cle, $id ) {
	$activite = (int) get_post_meta( $id, 'asso_activite', true );
	$activite = ( $activite && 'publish' === get_post_status( $activite ) ) ? $activite : 0;

	switch ( $cle ) {
		case 'album_resume':
			$date     = asso_core_date( get_post_meta( $id, 'asso_date', true ) );
			$morceaux = array_filter(
				array(
					$date ? esc_html( asso_core_formater( $date, 'j F Y' ) ) : '',
					esc_html( asso_core_noms_sections( $id ) ),
				)
			);
			return $morceaux ? implode( ' · ', $morceaux ) : esc_html__( 'Album photo', 'asso-core' );
		case 'activite_liee_url':
			return $activite ? esc_url( get_permalink( $activite ) ) : '';
		case 'activite_liee_titre':
			/* translators: %s: titre de l'activité. */
			return $activite ? esc_html( sprintf( __( 'Voir l’activité : %s', 'asso-core' ), get_the_title( $activite ) ) ) : '';
	}
	return null;
}

/**
 * Libellé en gras suivi de sa valeur (déjà échappée) : « Lieu : … ».
 *
 * @param string $libelle Libellé.
 * @param string $valeur  Valeur échappée.
 * @return string
 */
function asso_core_libelle( $libelle, $valeur ) {
	return '<strong>' . esc_html( $libelle ) . '&nbsp;:</strong> ' . $valeur;
}

/**
 * Noms des sections d'un contenu, séparés par des virgules.
 *
 * @param int $id Identifiant du contenu.
 * @return string
 */
function asso_core_noms_sections( $id ) {
	$noms = wp_get_post_terms( $id, 'asso_section', array( 'fields' => 'names' ) );
	return is_wp_error( $noms ) ? '' : implode( ', ', $noms );
}

/**
 * Tarif en dollars US à la française : « 25 $ US », « 12,50 $ US », « Gratuit » ou « à préciser ».
 *
 * @param string $tarif Valeur enregistrée.
 * @return string Texte échappé.
 */
function asso_core_texte_tarif( $tarif ) {
	$tarif = (string) $tarif;
	if ( '' === $tarif ) {
		return esc_html__( 'à préciser', 'asso-core' );
	}
	if ( 0.0 === (float) $tarif ) {
		return esc_html__( 'Gratuit', 'asso-core' );
	}
	$decimales = ( floor( (float) $tarif ) === (float) $tarif ) ? 0 : 2;
	return esc_html( number_format_i18n( (float) $tarif, $decimales ) ) . '&nbsp;$&nbsp;US';
}

/**
 * Retire les boutons marqués « asso-si-lien » quand leur lien lié est vide
 * (pas d'inscription, pas d'adresse, pas d'album…).
 *
 * @param string $html Rendu du bouton.
 * @param array  $bloc Bloc analysé.
 * @return string
 */
function asso_core_masquer_boutons_sans_lien( $html, $bloc ) {
	$classes = isset( $bloc['attrs']['className'] ) ? (string) $bloc['attrs']['className'] : '';
	if ( false === strpos( $classes, 'asso-si-lien' ) ) {
		return $html;
	}
	$balises = new WP_HTML_Tag_Processor( $html );
	if ( $balises->next_tag( 'a' ) ) {
		$lien = trim( (string) $balises->get_attribute( 'href' ) );
		if ( '' !== $lien && '#' !== $lien ) {
			return $html;
		}
	}
	return '';
}
add_filter( 'render_block_core/button', 'asso_core_masquer_boutons_sans_lien', 10, 2 );
