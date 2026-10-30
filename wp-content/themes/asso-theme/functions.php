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
			'copie'          => __( 'Copié !', 'asso-theme' ),
			'copieAnnonce'   => __( 'Destinataire Zelle copié dans le presse-papiers.', 'asso-theme' ),
			'copieEchec'     => __( 'La copie a échoué : sélectionnez le destinataire à la main.', 'asso-theme' ),
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
 * Regroupe les motifs du thème dans une catégorie « La Famille Wê » de l'éditeur.
 */
function asso_theme_categorie_motifs() {
	register_block_pattern_category(
		'asso-theme',
		array( 'label' => __( 'La Famille Wê', 'asso-theme' ) )
	);
}
add_action( 'init', 'asso_theme_categorie_motifs' );

/**
 * Attribut de bloc qui lie le contenu d'un paragraphe à un champ de l'extension asso-core.
 *
 * @param string $cle Clé de la source « asso-core/activite » (jour, mois, date_longue, resume…).
 * @return string Fragment JSON pour le commentaire de bloc.
 */
function asso_theme_liaison( $cle ) {
	return '"metadata":{"bindings":{"content":{"source":"asso-core/activite","args":{"cle":"' . sanitize_key( $cle ) . '"}}}}';
}

/**
 * Carte d'une activité pour les boucles de requête (accueil, page Activités) :
 * tuile de date, date complète, titre, lieu · section · tarif.
 *
 * @param bool $passee Activité passée : tuile foncée et bouton « Voir l'album photo ».
 * @return string Balisage de blocs.
 */
function asso_theme_carte_activite( $passee = false ) {
	$tuile = $passee ? 'encre' : 'vert';
	// Balisage de blocs fixe ; les textes variables sont échappés.
	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	ob_start();
	?>
<!-- wp:group {"className":"asso-carte asso-carte-activite","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.25rem","bottom":"1.25rem","left":"1.25rem","right":"1.25rem"},"blockGap":"1rem"}},"backgroundColor":"blanc","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group asso-carte asso-carte-activite has-blanc-background-color has-background" style="border-radius:24px;padding-top:1.25rem;padding-right:1.25rem;padding-bottom:1.25rem;padding-left:1.25rem"><!-- wp:group {"className":"asso-tuile-date","backgroundColor":"<?php echo $tuile; ?>","textColor":"blanc","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group asso-tuile-date has-blanc-color has-<?php echo $tuile; ?>-background-color has-text-color has-background"><!-- wp:paragraph {<?php echo asso_theme_liaison( 'jour' ); ?>,"className":"asso-tuile-date__jour"} -->
<p class="asso-tuile-date__jour">–</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'mois' ); ?>,"className":"asso-tuile-date__mois"} -->
<p class="asso-tuile-date__mois"><?php echo esc_html__( 'mois', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.4rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {<?php echo asso_theme_liaison( 'date_longue' ); ?>,"style":{"typography":{"fontWeight":"600"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:600"><?php echo esc_html__( 'Date de l’activité', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'resume' ); ?>,"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Lieu · section · tarif', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
<?php if ( $passee ) : ?>

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline asso-si-lien","metadata":{"bindings":{"url":{"source":"asso-core/activite","args":{"cle":"album_url"}}}}} -->
<div class="wp-block-button is-style-outline asso-si-lien"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Voir l’album photo', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<?php endif; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
	<?php
	// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	return ob_get_clean();
}

/**
 * Filtre par section (Toutes, Dallas, Oklahoma) : simples liens « ?section=… »,
 * le bouton actif est signalé par effets.js (aria-current).
 *
 * @param string $chemin Adresse de la liste filtrée (ex. « /activites/ »).
 * @return string Balisage de blocs.
 */
function asso_theme_filtre_sections( $chemin ) {
	$base     = home_url( $chemin );
	$sections = array(
		''         => __( 'Toutes', 'asso-theme' ),
		'dallas'   => __( 'Dallas', 'asso-theme' ),
		'oklahoma' => __( 'Oklahoma', 'asso-theme' ),
	);
	ob_start();
	?>
<!-- wp:group {"className":"asso-filtre","style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group asso-filtre"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-weight:600"><?php echo esc_html__( 'Filtrer par section&nbsp;:', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"asso-filtre-sections"} -->
<div class="wp-block-buttons asso-filtre-sections">
	<?php foreach ( $sections as $slug => $nom ) : ?>
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $slug ? add_query_arg( 'section', $slug, $base ) : $base ); ?>"><?php echo esc_html( $nom ); ?></a></div>
<!-- /wp:button -->
	<?php endforeach; ?>
</div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
	<?php
	return ob_get_clean();
}
