<?php
/**
 * Dates des activités : lecture et mise en forme, au fuseau du site (America/Chicago).
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Convertit une valeur enregistrée (AAAA-MM-JJ ou AAAA-MM-JJTHH:MM) en date du fuseau du site.
 *
 * @param string $valeur Valeur de la métadonnée.
 * @return DateTimeImmutable|null
 */
function asso_core_date( $valeur ) {
	$valeur = (string) $valeur;
	if ( '' === $valeur ) {
		return null;
	}
	$format = 10 === strlen( $valeur ) ? '!Y-m-d' : '!Y-m-d\TH:i';
	$date   = DateTimeImmutable::createFromFormat( $format, $valeur, wp_timezone() );
	return $date ? $date : null;
}

/**
 * Début de la journée en cours (fuseau du site), au format des métadonnées :
 * une activité du jour reste « à venir » jusqu'à minuit.
 *
 * @return string
 */
function asso_core_debut_du_jour() {
	return ( new DateTimeImmutable( 'now', wp_timezone() ) )->format( 'Y-m-d\T00:00' );
}

/**
 * Met en forme une date en français, au fuseau du site.
 *
 * @param DateTimeImmutable $date   Date.
 * @param string            $format Format de date PHP.
 * @return string
 */
function asso_core_formater( DateTimeImmutable $date, $format ) {
	return wp_date( $format, $date->getTimestamp(), wp_timezone() );
}

/**
 * Mois abrégé à la française (« nov. », « sept. ») : les abréviations de la traduction
 * de WordPress ne sont pas fiables, elles sont donc définies ici.
 *
 * @param DateTimeImmutable $date Date.
 * @return string
 */
function asso_core_mois_abrege( DateTimeImmutable $date ) {
	$mois = array(
		1  => _x( 'janv.', 'mois abrégé', 'asso-core' ),
		2  => _x( 'févr.', 'mois abrégé', 'asso-core' ),
		3  => _x( 'mars', 'mois abrégé', 'asso-core' ),
		4  => _x( 'avr.', 'mois abrégé', 'asso-core' ),
		5  => _x( 'mai', 'mois abrégé', 'asso-core' ),
		6  => _x( 'juin', 'mois abrégé', 'asso-core' ),
		7  => _x( 'juil.', 'mois abrégé', 'asso-core' ),
		8  => _x( 'août', 'mois abrégé', 'asso-core' ),
		9  => _x( 'sept.', 'mois abrégé', 'asso-core' ),
		10 => _x( 'oct.', 'mois abrégé', 'asso-core' ),
		11 => _x( 'nov.', 'mois abrégé', 'asso-core' ),
		12 => _x( 'déc.', 'mois abrégé', 'asso-core' ),
	);
	return $mois[ (int) $date->format( 'n' ) ];
}

/**
 * Heure à la française : « 18 h 30 », ou « 18 h » pile.
 *
 * @param DateTimeImmutable $date Date.
 * @return string
 */
function asso_core_heure( DateTimeImmutable $date ) {
	return '00' === $date->format( 'i' ) ? asso_core_formater( $date, 'G \h' ) : asso_core_formater( $date, 'G \h i' );
}

/**
 * Phrase complète du moment d'une activité, selon qu'elle a une fin le même jour, un autre jour ou pas de fin.
 *
 * @param DateTimeImmutable|null $debut Début.
 * @param DateTimeImmutable|null $fin   Fin, facultative.
 * @return string
 */
function asso_core_texte_quand( $debut, $fin ) {
	if ( ! $debut ) {
		return __( 'à préciser', 'asso-core' );
	}
	$jour_debut = asso_core_formater( $debut, 'l j F Y' );
	if ( ! $fin || $fin <= $debut ) {
		/* translators: 1: date, 2: heure. */
		return sprintf( __( '%1$s à %2$s', 'asso-core' ), $jour_debut, asso_core_heure( $debut ) );
	}
	if ( $fin->format( 'Y-m-d' ) === $debut->format( 'Y-m-d' ) ) {
		/* translators: 1: date, 2: heure de début, 3: heure de fin. */
		return sprintf( __( '%1$s, de %2$s à %3$s', 'asso-core' ), $jour_debut, asso_core_heure( $debut ), asso_core_heure( $fin ) );
	}
	/* translators: 1: date de début, 2: heure de début, 3: date de fin, 4: heure de fin. */
	return sprintf( __( 'du %1$s, %2$s, au %3$s, %4$s', 'asso-core' ), $jour_debut, asso_core_heure( $debut ), asso_core_formater( $fin, 'l j F Y' ), asso_core_heure( $fin ) );
}
