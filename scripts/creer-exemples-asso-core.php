<?php
/**
 * Crée des exemples d'activités et d'album, clairement marqués « À SUPPRIMER »,
 * pour voir la mise en page des listes automatiques de l'extension asso-core.
 *
 * Usage (depuis la racine du site) :
 *   studio.bat wp eval-file scripts/creer-exemples-asso-core.php
 *
 * Sans danger à relancer : un exemple dont l'adresse existe déjà n'est ni recréé ni modifié.
 * Les dates sont calculées à partir d'aujourd'hui (une activité à venir, une passée) ;
 * aucun lieu, tarif ni nom réel n'est inventé.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

if ( ! post_type_exists( 'asso_event' ) ) {
	WP_CLI::error( 'Activez d’abord l’extension asso-core.' );
}

$asso_admins = get_users(
	array(
		'role'   => 'administrator',
		'number' => 1,
		'fields' => 'ID',
	)
);
wp_set_current_user( (int) $asso_admins[0] );

$asso_aujourdhui = new DateTimeImmutable( 'now', wp_timezone() );
$asso_a_venir    = $asso_aujourdhui->modify( '+21 days' );
$asso_passee     = $asso_aujourdhui->modify( '-30 days' );
$asso_texte      = "<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->";

$asso_exemples = array(
	array(
		'type'     => 'asso_event',
		'adresse'  => 'exemple-activite-a-venir',
		'titre'    => 'Exemple d’activité à venir [À SUPPRIMER]',
		'texte'    => '[EXEMPLE] Décrivez ici le programme de l’activité. Supprimez cet exemple dès que les vraies activités sont publiées.',
		'meta'     => array(
			'asso_debut' => $asso_a_venir->format( 'Y-m-d' ) . 'T18:30',
			'asso_fin'   => $asso_a_venir->format( 'Y-m-d' ) . 'T22:00',
			'asso_lieu'  => '[À FOURNIR : lieu]',
		),
		'sections' => array( 'dallas' ),
	),
	array(
		'type'     => 'asso_event',
		'adresse'  => 'exemple-activite-passee',
		'titre'    => 'Exemple d’activité passée [À SUPPRIMER]',
		'texte'    => '[EXEMPLE] Une activité passée reste visible, avec un lien vers son album photo.',
		'meta'     => array(
			'asso_debut' => $asso_passee->format( 'Y-m-d' ) . 'T14:00',
			'asso_lieu'  => '[À FOURNIR : lieu]',
			'asso_tarif' => '0',
		),
		'sections' => array( 'les-deux-sections' ),
	),
	array(
		'type'     => 'asso_album',
		'adresse'  => 'exemple-album',
		'titre'    => 'Exemple d’album [À SUPPRIMER]',
		'texte'    => '[EXEMPLE] Ajoutez ici les photos de l’activité avec le bloc Galerie : elles s’agrandissent au clic. Choisissez aussi une image mise en avant, qui sert de couverture.',
		'meta'     => array(
			'asso_date' => $asso_passee->format( 'Y-m-d' ),
		),
		'sections' => array( 'les-deux-sections' ),
	),
);

$asso_ids = array();
foreach ( $asso_exemples as $asso_exemple ) {
	$asso_existant = get_page_by_path( $asso_exemple['adresse'], OBJECT, $asso_exemple['type'] );
	if ( $asso_existant ) {
		$asso_ids[ $asso_exemple['adresse'] ] = $asso_existant->ID;
		WP_CLI::log( sprintf( 'déjà là  %s (ID %d), non modifié', $asso_exemple['adresse'], $asso_existant->ID ) );
		continue;
	}
	$asso_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'    => $asso_exemple['type'],
				'post_status'  => 'publish',
				'post_title'   => $asso_exemple['titre'],
				'post_name'    => $asso_exemple['adresse'],
				'post_content' => sprintf( $asso_texte, $asso_exemple['texte'] ),
				'meta_input'   => $asso_exemple['meta'],
			)
		),
		true
	);
	if ( is_wp_error( $asso_id ) ) {
		WP_CLI::warning( sprintf( 'échec %s : %s', $asso_exemple['adresse'], $asso_id->get_error_message() ) );
		continue;
	}
	wp_set_object_terms( $asso_id, $asso_exemple['sections'], 'asso_section' );
	$asso_ids[ $asso_exemple['adresse'] ] = $asso_id;
	WP_CLI::log( sprintf( 'créé     %s (ID %d)', $asso_exemple['adresse'], $asso_id ) );
}

// L'album d'exemple est lié à l'activité passée d'exemple.
if ( isset( $asso_ids['exemple-album'], $asso_ids['exemple-activite-passee'] ) && ! get_post_meta( $asso_ids['exemple-album'], 'asso_activite', true ) ) {
	update_post_meta( $asso_ids['exemple-album'], 'asso_activite', $asso_ids['exemple-activite-passee'] );
	WP_CLI::log( 'lien     album d’exemple → activité passée d’exemple' );
}

WP_CLI::success( 'Exemples prêts.' );
