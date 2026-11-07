<?php
/**
 * Remplit l'extrait des pages, qui sert de méta-description (extension asso-core).
 *
 * Usage (depuis la racine du site) :
 *   studio.bat wp eval-file scripts/ajouter-descriptions.php
 *
 * Sans danger à relancer : un extrait déjà rempli (par exemple modifié dans
 * WP Admin) n'est jamais remplacé. Textes provisoires marqués [À VALIDER] :
 * retirer la mention dans le champ « Extrait » de la page une fois le texte validé.
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

$asso_nbsp = "\u{00A0}";

// Adresse de la page => description (160 caractères au plus, mention comprise).
$asso_descriptions = array(
	'accueil'          => 'La Famille Wê de Dallas & Oklahoma rassemble la diaspora ivoirienne du Texas et de l’Oklahoma. Ensemble allons plus loin' . $asso_nbsp . '!',
	'qui-sommes-nous'  => 'Notre histoire, notre mission et les valeurs qui rassemblent La Famille Wê de Dallas & Oklahoma.',
	'le-bureau'        => 'Les membres du bureau de La Famille Wê de Dallas & Oklahoma, l’équipe qui fait vivre l’association.',
	'nos-sections'     => 'Dallas et Oklahoma' . $asso_nbsp . ': les deux sections de La Famille Wê, leurs responsables et leurs rencontres.',
	'partenaires'      => 'Les partenaires qui soutiennent La Famille Wê de Dallas & Oklahoma et ses actions.',
	'actualites'       => 'Les nouvelles de La Famille Wê de Dallas & Oklahoma' . $asso_nbsp . ': vie de l’association, annonces et temps forts.',
	'adhesion'         => 'Rejoignez La Famille Wê de Dallas & Oklahoma' . $asso_nbsp . ': conditions d’adhésion, cotisation et paiement par Zelle.',
	'faire-un-don'     => 'Soutenez les actions de La Famille Wê de Dallas & Oklahoma par un don en dollars US, payé par Zelle.',
	'contact'          => 'Une question, une idée, l’envie de nous rejoindre' . $asso_nbsp . '? Écrivez à La Famille Wê de Dallas & Oklahoma.',
	'faq'              => 'Adhésion, cotisation, activités' . $asso_nbsp . ': les réponses aux questions fréquentes sur La Famille Wê de Dallas & Oklahoma.',
	'mentions-legales' => 'Mentions légales du site de La Famille Wê de Dallas & Oklahoma' . $asso_nbsp . ': éditeur, publication et hébergement.',
	'confidentialite'  => 'Vos données personnelles sur le site de La Famille Wê de Dallas & Oklahoma' . $asso_nbsp . ': formulaires, Zelle, photos et droits.',
);

foreach ( $asso_descriptions as $asso_adresse => $asso_texte ) {
	$asso_page = get_page_by_path( $asso_adresse, OBJECT, 'page' );
	if ( ! $asso_page ) {
		WP_CLI::warning( sprintf( 'page introuvable : /%s/', $asso_adresse ) );
		continue;
	}
	if ( '' !== trim( $asso_page->post_excerpt ) ) {
		WP_CLI::log( sprintf( 'déjà rempli  /%s/, non modifié', $asso_adresse ) );
		continue;
	}
	$asso_resultat = wp_update_post(
		wp_slash(
			array(
				'ID'           => $asso_page->ID,
				'post_excerpt' => $asso_texte . ' [À VALIDER]',
			)
		),
		true
	);
	if ( is_wp_error( $asso_resultat ) ) {
		WP_CLI::warning( sprintf( '/%s/ : %s', $asso_adresse, $asso_resultat->get_error_message() ) );
		continue;
	}
	WP_CLI::log( sprintf( 'ajouté       /%s/ (%d caractères)', $asso_adresse, mb_strlen( $asso_texte ) + 13 ) );
}
