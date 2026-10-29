<?php
/**
 * Fonctions du thème asso-theme.
 *
 * Le style vit dans theme.json ; ce fichier ne fait que le strict nécessaire.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Charge les traductions du thème (dossier languages/), pour une future version anglaise.
 */
function asso_theme_charger_traductions() {
	load_theme_textdomain( 'asso-theme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'asso_theme_charger_traductions' );

/**
 * Charge les effets visuels (en-tête vitré, motif, animations) et leur petit script.
 */
function asso_theme_effets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'asso-theme-effets', get_theme_file_uri( 'assets/css/effets.css' ), array(), $version );
	wp_enqueue_script(
		'asso-theme-effets',
		get_theme_file_uri( 'assets/js/effets.js' ),
		array(),
		$version,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
	wp_localize_script(
		'asso-theme-effets',
		'assoEffets',
		array(
			'pause'          => __( 'Pause', 'asso-theme' ),
			'lecture'        => __( 'Lecture', 'asso-theme' ),
			'pauseLibelle'   => __( 'Mettre en pause les animations', 'asso-theme' ),
			'lectureLibelle' => __( 'Reprendre les animations', 'asso-theme' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'asso_theme_effets' );

/**
 * Applique les mêmes effets dans l'éditeur, pour un rendu fidèle.
 */
function asso_theme_styles_editeur() {
	add_editor_style( 'assets/css/effets.css' );
}
add_action( 'after_setup_theme', 'asso_theme_styles_editeur' );

/**
 * Regroupe les motifs du thème dans une catégorie « La Famille WE » de l'éditeur.
 */
function asso_theme_categorie_motifs() {
	register_block_pattern_category(
		'asso-theme',
		array( 'label' => __( 'La Famille WE', 'asso-theme' ) )
	);
}
add_action( 'init', 'asso_theme_categorie_motifs' );
