<?php
/**
 * Crée les pages de l'espace membres (extension asso-core) :
 * Connexion, Espace membres et ses sous-pages Mon profil et Annuaire.
 *
 * Usage (depuis la racine du site) :
 *   studio.bat wp eval-file scripts/creer-espace-membres.php
 *
 * Sans danger à relancer : une page dont l'adresse existe déjà n'est jamais modifiée.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

// Administrateur courant : sans lui, WordPress filtre le balisage des blocs en enregistrant.
$asso_admins = get_users(
	array(
		'role'   => 'administrator',
		'number' => 1,
		'fields' => 'ID',
	)
);
if ( ! $asso_admins ) {
	WP_CLI::error( 'Aucun administrateur trouvé.' );
}
wp_set_current_user( (int) $asso_admins[0] );

$asso_bloc = static function ( $code_court, $intro = '' ) {
	$html = '';
	if ( $intro ) {
		$html .= "<!-- wp:paragraph -->\n<p>" . $intro . "</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return $html . "<!-- wp:shortcode -->\n[" . $code_court . "]\n<!-- /wp:shortcode -->";
};

// Adresse => titre, contenu, page parente (adresse).
$asso_pages = array(
	'connexion'      => array( 'Connexion', $asso_bloc( 'asso_connexion', 'Cet espace est réservé aux membres de La Famille Wê. Votre compte est créé par le bureau après votre adhésion&nbsp;: vous recevez alors un e-mail pour choisir votre mot de passe. [À VALIDER]' ), '' ),
	'espace-membres' => array( 'Espace membres', $asso_bloc( 'asso_espace_membres' ), '' ),
	'mon-profil'     => array( 'Mon profil', $asso_bloc( 'asso_profil' ), 'espace-membres' ),
	'annuaire'       => array( 'Annuaire des membres', $asso_bloc( 'asso_annuaire' ), 'espace-membres' ),
);

foreach ( $asso_pages as $asso_adresse => $asso_page ) {
	list( $asso_titre, $asso_contenu, $asso_parent ) = $asso_page;
	$asso_parent_id = 0;
	if ( $asso_parent ) {
		$asso_parent_page = get_page_by_path( $asso_parent );
		if ( ! $asso_parent_page ) {
			WP_CLI::warning( sprintf( 'page parente introuvable : /%s/', $asso_parent ) );
			continue;
		}
		$asso_parent_id = $asso_parent_page->ID;
	}
	$asso_chemin = $asso_parent ? $asso_parent . '/' . $asso_adresse : $asso_adresse;
	$asso_existe = get_page_by_path( $asso_chemin );
	if ( $asso_existe ) {
		WP_CLI::log( sprintf( 'déjà là  /%s/ (ID %d), non modifiée', $asso_chemin, $asso_existe->ID ) );
		continue;
	}
	$asso_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $asso_titre,
				'post_name'      => $asso_adresse,
				'post_parent'    => $asso_parent_id,
				'post_content'   => $asso_contenu,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			)
		),
		true
	);
	if ( is_wp_error( $asso_id ) ) {
		WP_CLI::warning( sprintf( '/%s/ : %s', $asso_chemin, $asso_id->get_error_message() ) );
		continue;
	}
	WP_CLI::log( sprintf( 'créée    /%s/ (ID %d)', $asso_chemin, $asso_id ) );
}
