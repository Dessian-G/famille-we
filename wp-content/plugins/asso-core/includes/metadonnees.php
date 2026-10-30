<?php
/**
 * Métadonnées des activités et des albums, exposées à l'éditeur (REST).
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enregistre les champs des activités et des albums.
 */
function asso_core_enregistrer_metadonnees() {
	$commun = array(
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => static function ( $autorise, $cle, $id_contenu ) {
			return current_user_can( 'edit_post', $id_contenu );
		},
	);

	$champs_activite = array(
		'asso_debut'   => array( __( 'Début (AAAA-MM-JJTHH:MM, heure du Centre)', 'asso-core' ), 'asso_core_assainir_date_heure' ),
		'asso_fin'     => array( __( 'Fin, facultative (AAAA-MM-JJTHH:MM, heure du Centre)', 'asso-core' ), 'asso_core_assainir_date_heure' ),
		'asso_lieu'    => array( __( 'Nom du lieu', 'asso-core' ), 'sanitize_text_field' ),
		'asso_adresse' => array( __( 'Adresse postale (sert au lien Google Maps)', 'asso-core' ), 'sanitize_text_field' ),
		'asso_tarif'   => array( __( 'Tarif en dollars US (0 = gratuit, vide = à préciser)', 'asso-core' ), 'asso_core_assainir_tarif' ),
		'asso_lien'    => array( __( 'Lien d’inscription ou de paiement, facultatif', 'asso-core' ), 'esc_url_raw' ),
	);
	foreach ( $champs_activite as $cle => $champ ) {
		register_post_meta(
			'asso_event',
			$cle,
			$commun + array(
				'type'              => 'string',
				'description'       => $champ[0],
				'sanitize_callback' => $champ[1],
				'default'           => '',
			)
		);
	}

	register_post_meta(
		'asso_album',
		'asso_date',
		$commun + array(
			'type'              => 'string',
			'description'       => __( 'Date de l’album (AAAA-MM-JJ)', 'asso-core' ),
			'sanitize_callback' => 'asso_core_assainir_date',
			'default'           => '',
		)
	);
	register_post_meta(
		'asso_album',
		'asso_activite',
		$commun + array(
			'type'              => 'integer',
			'description'       => __( 'Activité liée (identifiant), facultative', 'asso-core' ),
			'sanitize_callback' => 'absint',
			'default'           => 0,
		)
	);
}
add_action( 'init', 'asso_core_enregistrer_metadonnees' );

/**
 * Garde une date et heure au format AAAA-MM-JJTHH:MM (champ « datetime-local »), sinon vide.
 *
 * @param mixed $valeur Valeur saisie.
 * @return string
 */
function asso_core_assainir_date_heure( $valeur ) {
	$valeur = substr( trim( (string) $valeur ), 0, 16 );
	$date   = DateTimeImmutable::createFromFormat( '!Y-m-d\TH:i', $valeur, wp_timezone() );
	return ( $date && $date->format( 'Y-m-d\TH:i' ) === $valeur ) ? $valeur : '';
}

/**
 * Garde une date au format AAAA-MM-JJ, sinon vide.
 *
 * @param mixed $valeur Valeur saisie.
 * @return string
 */
function asso_core_assainir_date( $valeur ) {
	$valeur = substr( trim( (string) $valeur ), 0, 10 );
	$date   = DateTimeImmutable::createFromFormat( '!Y-m-d', $valeur, wp_timezone() );
	return ( $date && $date->format( 'Y-m-d' ) === $valeur ) ? $valeur : '';
}

/**
 * Garde un montant positif à deux décimales au plus (« 0 » = gratuit), sinon vide (= à préciser).
 *
 * @param mixed $valeur Valeur saisie.
 * @return string
 */
function asso_core_assainir_tarif( $valeur ) {
	$valeur = str_replace( ',', '.', trim( (string) $valeur ) );
	if ( '' === $valeur || ! is_numeric( $valeur ) || (float) $valeur < 0 ) {
		return '';
	}
	return (string) round( (float) $valeur, 2 );
}
