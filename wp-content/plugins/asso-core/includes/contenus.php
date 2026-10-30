<?php
/**
 * Types de contenu (Activités, Galerie) et taxonomie des sections.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enregistre les activités, les albums de la galerie et les sections.
 */
function asso_core_enregistrer_contenus() {
	register_post_type(
		'asso_event',
		array(
			'labels'        => array(
				'name'               => __( 'Activités', 'asso-core' ),
				'singular_name'      => __( 'Activité', 'asso-core' ),
				'add_new'            => __( 'Ajouter', 'asso-core' ),
				'add_new_item'       => __( 'Ajouter une activité', 'asso-core' ),
				'edit_item'          => __( 'Modifier l’activité', 'asso-core' ),
				'new_item'           => __( 'Nouvelle activité', 'asso-core' ),
				'view_item'          => __( 'Voir l’activité', 'asso-core' ),
				'view_items'         => __( 'Voir les activités', 'asso-core' ),
				'search_items'       => __( 'Rechercher une activité', 'asso-core' ),
				'not_found'          => __( 'Aucune activité', 'asso-core' ),
				'not_found_in_trash' => __( 'Aucune activité dans la corbeille', 'asso-core' ),
				'all_items'          => __( 'Toutes les activités', 'asso-core' ),
				'menu_name'          => __( 'Activités', 'asso-core' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => 'activites',
			'rewrite'       => array(
				'slug'       => 'activites',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
		)
	);

	register_post_type(
		'asso_album',
		array(
			'labels'        => array(
				'name'               => __( 'Galerie', 'asso-core' ),
				'singular_name'      => __( 'Album', 'asso-core' ),
				'add_new'            => __( 'Ajouter', 'asso-core' ),
				'add_new_item'       => __( 'Ajouter un album', 'asso-core' ),
				'edit_item'          => __( 'Modifier l’album', 'asso-core' ),
				'new_item'           => __( 'Nouvel album', 'asso-core' ),
				'view_item'          => __( 'Voir l’album', 'asso-core' ),
				'view_items'         => __( 'Voir les albums', 'asso-core' ),
				'search_items'       => __( 'Rechercher un album', 'asso-core' ),
				'not_found'          => __( 'Aucun album', 'asso-core' ),
				'not_found_in_trash' => __( 'Aucun album dans la corbeille', 'asso-core' ),
				'all_items'          => __( 'Tous les albums', 'asso-core' ),
				'menu_name'          => __( 'Galerie', 'asso-core' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => 'galerie',
			'rewrite'       => array(
				'slug'       => 'galerie',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-format-gallery',
			'menu_position' => 6,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
			// Un nouvel album commence par une galerie (photos agrandies au clic via le thème).
			'template'      => array( array( 'core/gallery', array( 'columns' => 3 ) ) ),
		)
	);

	register_taxonomy(
		'asso_section',
		array( 'asso_event', 'asso_album' ),
		array(
			'labels'            => array(
				'name'          => __( 'Sections', 'asso-core' ),
				'singular_name' => __( 'Section', 'asso-core' ),
				'all_items'     => __( 'Toutes les sections', 'asso-core' ),
				'edit_item'     => __( 'Modifier la section', 'asso-core' ),
				'add_new_item'  => __( 'Ajouter une section', 'asso-core' ),
				'menu_name'     => __( 'Sections', 'asso-core' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'section',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'asso_core_enregistrer_contenus' );

/**
 * Crée les trois sections si elles n'existent pas encore.
 */
function asso_core_creer_sections() {
	$sections = array(
		'dallas'            => __( 'Dallas', 'asso-core' ),
		'oklahoma'          => __( 'Oklahoma', 'asso-core' ),
		'les-deux-sections' => __( 'Les deux sections', 'asso-core' ),
	);
	foreach ( $sections as $slug => $nom ) {
		if ( ! term_exists( $slug, 'asso_section' ) ) {
			wp_insert_term( $nom, 'asso_section', array( 'slug' => $slug ) );
		}
	}
}
