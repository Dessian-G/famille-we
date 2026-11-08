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
 * Page Merci : classe « asso-fete », qui déclenche les confettis d'effets.js.
 *
 * @param string[] $classes Classes de la balise body.
 * @return string[]
 */
function asso_theme_classe_fete( $classes ) {
	if ( is_page( 'merci' ) ) {
		$classes[] = 'asso-fete';
	}
	return $classes;
}
add_filter( 'body_class', 'asso_theme_classe_fete' );

/**
 * Écrans de WordPress « Mot de passe oublié » et « Nouveau mot de passe » (wp-login.php),
 * utilisés par les membres : logo, couleurs et polices du site.
 */
function asso_theme_ecran_connexion() {
	$logo = get_theme_file_uri( 'assets/images/logo-famille-we-256.webp' );
	$css  = 'body.login{background:#E3EEF9;font-family:Figtree,system-ui,sans-serif;color:#1F2A24}'
		. '.login h1 a{background-image:url(' . esc_url( $logo ) . ');background-size:96px 96px;width:96px;height:96px}'
		. '.login form{border:1px solid #F6D2B0;border-radius:24px;box-shadow:none}'
		. '.login input[type=text],.login input[type=password],.login input[type=email]{border:1px solid #7b857f;border-radius:12px;min-height:44px}'
		. '.login .button-primary{background:#F77F00;border-color:#F77F00;color:#1F2A24;border-radius:999px;min-height:44px;padding:0 1.25rem;font-weight:600}'
		. '.login .button-primary:hover,.login .button-primary:focus{background:#A64E00;border-color:#A64E00;color:#fff}'
		. '.login #nav a,.login #backtoblog a{color:#1F5AA6}'
		. '.login .message,.login .notice{border-left-color:#00754A;border-radius:8px}';
	wp_add_inline_style( 'login', $css );
}
add_action( 'login_enqueue_scripts', 'asso_theme_ecran_connexion' );

/**
 * Le logo de l'écran de connexion renvoie vers le site, avec son nom.
 *
 * @return string
 */
function asso_theme_lien_logo_connexion() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'asso_theme_lien_logo_connexion' );

/**
 * Texte du lien du logo de l'écran de connexion.
 *
 * @return string
 */
function asso_theme_texte_logo_connexion() {
	return __( 'La Famille Wê de Dallas & Oklahoma', 'asso-theme' );
}
add_filter( 'login_headertext', 'asso_theme_texte_logo_connexion' );

// Site en français uniquement : pas de choix de langue sur l'écran de connexion.
add_filter( 'login_display_language_dropdown', '__return_false' );

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

/**
 * Bloc Image d'un média de la médiathèque (sans visionneuse).
 *
 * @param int    $id     Identifiant du média.
 * @param string $classe Classe CSS du bloc (facultative).
 * @param string $taille Taille d'image WordPress.
 * @param string $lien   Adresse vers laquelle la photo renvoie (facultative).
 * @param bool   $differe Chargement différé, pour une photo loin du haut de page.
 * @return string Balisage de blocs, vide si le média n'existe pas.
 */
function asso_theme_bloc_image( $id, $classe = '', $taille = 'large', $lien = '', $differe = false ) {
	$url = wp_get_attachment_image_url( $id, $taille );
	if ( ! $url ) {
		return '';
	}
	$attributs = array(
		'id'              => (int) $id,
		'sizeSlug'        => $taille,
		'linkDestination' => $lien ? 'custom' : 'none',
		'lightbox'        => array( 'enabled' => false ),
	);
	if ( $classe ) {
		$attributs['className'] = $classe;
	}
	$image = sprintf(
		'<img src="%1$s" alt="%2$s" class="wp-image-%3$d"%4$s/>',
		esc_url( $url ),
		esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) ),
		(int) $id,
		$differe ? ' loading="lazy"' : ''
	);
	if ( $lien ) {
		$image = '<a href="' . esc_url( $lien ) . '">' . $image . '</a>';
	}
	$figure = '<figure class="' . esc_attr( trim( 'wp-block-image size-' . $taille . ' ' . $classe ) ) . '">' . $image . '</figure>';
	return get_comment_delimited_block_content( 'core/image', $attributs, $figure ) . "\n";
}

/**
 * Photo fixe de l'accueil, retrouvée par l'identifiant d'adresse (slug) du média.
 *
 * @param string $nom    Slug du média (ex. « famille-we-souvenir-01 »).
 * @param string $classe Classe CSS du bloc.
 * @param string $taille Taille d'image WordPress.
 * @return string Balisage de blocs, vide si le média n'existe pas.
 */
function asso_theme_photo( $nom, $classe, $taille = 'large' ) {
	$ids = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'name'        => $nom,
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	return $ids ? asso_theme_bloc_image( $ids[0], $classe, $taille ) : '';
}

/**
 * Identifiants des images d'un contenu (blocs Image, y compris dans une Galerie).
 *
 * @param array $blocs Blocs analysés par parse_blocks().
 * @return int[]
 */
function asso_theme_images_du_contenu( $blocs ) {
	$ids = array();
	foreach ( $blocs as $bloc ) {
		if ( 'core/image' === $bloc['blockName'] && ! empty( $bloc['attrs']['id'] ) ) {
			$ids[] = (int) $bloc['attrs']['id'];
		}
		if ( ! empty( $bloc['innerBlocks'] ) ) {
			$ids = array_merge( $ids, asso_theme_images_du_contenu( $bloc['innerBlocks'] ) );
		}
	}
	return $ids;
}

/**
 * Mosaïque « Souvenirs » de l'accueil : les dernières photos des albums publiés,
 * chacune renvoyant vers son album.
 *
 * @param int $nombre Nombre de photos.
 * @return string Balisage de blocs, vide s'il n'y a aucune photo.
 */
function asso_theme_souvenirs( $nombre = 6 ) {
	$albums = get_posts(
		array(
			'post_type'   => 'asso_album',
			'numberposts' => 10,
		)
	);
	$html   = '';
	$total  = 0;
	foreach ( $albums as $album ) {
		foreach ( asso_theme_images_du_contenu( parse_blocks( $album->post_content ) ) as $id ) {
			$html .= asso_theme_bloc_image( $id, '', 'large', get_permalink( $album ), true );
			if ( ++$total >= $nombre ) {
				return $html;
			}
		}
	}
	return $html;
}
