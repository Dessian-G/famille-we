<?php
/**
 * Panneau « Détails » dans l'éditeur des activités et des albums.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Charge le panneau uniquement pour les activités et les albums.
 */
function asso_core_panneau_editeur() {
	$ecran = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $ecran || ! in_array( $ecran->post_type, array( 'asso_event', 'asso_album' ), true ) ) {
		return;
	}
	wp_enqueue_script(
		'asso-core-panneau',
		ASSO_CORE_URL . 'assets/js/panneau.js',
		array( 'wp-plugins', 'wp-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-element', 'wp-i18n', 'wp-html-entities' ),
		ASSO_CORE_VERSION,
		true
	);
	wp_set_script_translations( 'asso-core-panneau', 'asso-core', ASSO_CORE_DOSSIER . 'languages' );
}
add_action( 'enqueue_block_editor_assets', 'asso_core_panneau_editeur' );
