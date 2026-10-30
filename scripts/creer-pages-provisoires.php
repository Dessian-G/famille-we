<?php
/**
 * Crée les pages provisoires du site (CLAUDE.md §5), règle la lecture
 * et ajoute 3 articles d'exemple pour la page Actualités.
 *
 * Usage (depuis la racine du site) :
 *   studio.bat wp eval-file scripts/creer-pages-provisoires.php
 *
 * Sans danger à relancer : une page ou un article dont l'adresse existe déjà
 * n'est jamais modifié ni supprimé. Le contenu de chaque page vient de
 * scripts/pages/<adresse>.html.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

// Administrateur courant : nécessaire pour enregistrer le balisage des blocs sans filtrage.
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

// Adresse => titre, dans l'ordre du menu.
// /activites/ et /galerie/ ne sont pas des pages : ce sont les listes automatiques de l'extension asso-core.
$asso_pages = array(
	'accueil'          => 'Accueil',
	'qui-sommes-nous'  => 'Qui sommes-nous',
	'le-bureau'        => 'Le bureau',
	'nos-sections'     => 'Nos sections',
	'partenaires'      => 'Partenaires',
	'actualites'       => 'Actualités',
	'adhesion'         => 'Adhésion',
	'faire-un-don'     => 'Faire un don',
	'contact'          => 'Contact',
	'faq'              => 'FAQ',
	'merci'            => 'Merci',
	'mentions-legales' => 'Mentions légales',
	'confidentialite'  => 'Confidentialité',
);

$asso_dossier = ABSPATH . 'scripts/pages/';
$asso_ids     = array();
$asso_ordre   = 0;

foreach ( $asso_pages as $asso_adresse => $asso_titre ) {
	++$asso_ordre;
	$asso_existante = get_page_by_path( $asso_adresse, OBJECT, 'page' );
	if ( $asso_existante ) {
		$asso_ids[ $asso_adresse ] = $asso_existante->ID;
		WP_CLI::log( sprintf( 'déjà là  /%s/ (ID %d), non modifiée', $asso_adresse, $asso_existante->ID ) );
		continue;
	}

	$asso_fichier = $asso_dossier . $asso_adresse . '.html';
	if ( ! is_readable( $asso_fichier ) ) {
		WP_CLI::warning( sprintf( 'fichier manquant : %s', $asso_fichier ) );
		continue;
	}

	$asso_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $asso_titre,
				'post_name'      => $asso_adresse,
				'post_content'   => file_get_contents( $asso_fichier ),
				'menu_order'     => $asso_ordre,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			)
		),
		true
	);
	if ( is_wp_error( $asso_id ) ) {
		WP_CLI::warning( sprintf( 'échec /%s/ : %s', $asso_adresse, $asso_id->get_error_message() ) );
		continue;
	}
	$asso_ids[ $asso_adresse ] = $asso_id;
	WP_CLI::log( sprintf( 'créée    /%s/ (ID %d)', $asso_adresse, $asso_id ) );
}

// Réglages > Lecture : accueil statique et page des articles ; page de confidentialité.
if ( isset( $asso_ids['accueil'], $asso_ids['actualites'] ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $asso_ids['accueil'] );
	update_option( 'page_for_posts', $asso_ids['actualites'] );
	WP_CLI::log( 'réglage  accueil statique + page des actualités' );
}
if ( isset( $asso_ids['confidentialite'] ) ) {
	update_option( 'wp_page_for_privacy_policy', $asso_ids['confidentialite'] );
	WP_CLI::log( 'réglage  page de confidentialité' );
}

// Articles d'exemple, clairement marqués, pour voir la mise en page des actualités.
for ( $asso_n = 1; $asso_n <= 3; $asso_n++ ) {
	$asso_adresse = 'exemple-actualite-' . $asso_n;
	if ( get_page_by_path( $asso_adresse, OBJECT, 'post' ) ) {
		WP_CLI::log( sprintf( 'déjà là  article %s, non modifié', $asso_adresse ) );
		continue;
	}
	$asso_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => sprintf( 'Exemple d’actualité n° %d [À SUPPRIMER]', $asso_n ),
				'post_name'    => $asso_adresse,
				'post_date'    => wp_date( 'Y-m-d H:i:s', time() - $asso_n * DAY_IN_SECONDS ),
				'post_content' => "<!-- wp:paragraph -->\n<p>[EXEMPLE] Cet article sert uniquement à montrer la mise en page des actualités. Supprimez-le dès que les vraies actualités sont publiées.</p>\n<!-- /wp:paragraph -->",
			)
		),
		true
	);
	if ( is_wp_error( $asso_id ) ) {
		WP_CLI::warning( sprintf( 'échec article %s : %s', $asso_adresse, $asso_id->get_error_message() ) );
		continue;
	}
	WP_CLI::log( sprintf( 'créé     article %s (ID %d)', $asso_adresse, $asso_id ) );
}

WP_CLI::success( 'Pages provisoires prêtes.' );
