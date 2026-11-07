<?php
/**
 * Réseaux sociaux : adresses des pages Facebook et Instagram (Réglages > Réseaux sociaux).
 * Une icône n'apparaît dans le pied de page que si son adresse est saisie : jamais de lien mort.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Réseaux pris en charge : clé => nom affiché, domaine attendu et exemple d'adresse.
 *
 * @return array<string, array{nom:string, domaine:string, exemple:string}>
 */
function asso_core_reseaux_pris_en_charge() {
	return array(
		'facebook'  => array(
			'nom'     => __( 'Page Facebook', 'asso-core' ),
			'domaine' => 'facebook.com',
			'exemple' => 'https://www.facebook.com/…',
		),
		'instagram' => array(
			'nom'     => __( 'Compte Instagram', 'asso-core' ),
			'domaine' => 'instagram.com',
			'exemple' => 'https://www.instagram.com/…',
		),
	);
}

/**
 * Adresses enregistrées, complétées par des valeurs vides.
 *
 * @return array{facebook:string, instagram:string}
 */
function asso_core_reseaux() {
	return wp_parse_args(
		(array) get_option( 'asso_reseaux', array() ),
		array_fill_keys( array_keys( asso_core_reseaux_pris_en_charge() ), '' )
	);
}

/**
 * Déclare l'option et ses champs (Settings API).
 */
function asso_core_enregistrer_reglages_reseaux() {
	register_setting(
		'asso_reseaux',
		'asso_reseaux',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'asso_core_assainir_reseaux',
			'default'           => array(),
		)
	);
	add_settings_section( 'asso_reseaux_principal', '', '__return_false', 'asso-reseaux' );

	foreach ( asso_core_reseaux_pris_en_charge() as $cle => $reseau ) {
		add_settings_field(
			'asso_reseaux_' . $cle,
			$reseau['nom'],
			'asso_core_afficher_champ_reseau',
			'asso-reseaux',
			'asso_reseaux_principal',
			array(
				'cle'       => $cle,
				'exemple'   => $reseau['exemple'],
				'label_for' => 'asso_reseaux_' . $cle,
			)
		);
	}
}
add_action( 'admin_init', 'asso_core_enregistrer_reglages_reseaux' );

/**
 * Affiche un champ d'adresse.
 *
 * @param array $args Clé, exemple et identifiant du champ.
 */
function asso_core_afficher_champ_reseau( $args ) {
	$reseaux = asso_core_reseaux();
	printf(
		'<input type="url" class="regular-text code" id="%1$s" name="asso_reseaux[%2$s]" value="%3$s" placeholder="%4$s" aria-describedby="%1$s-aide"><p class="description" id="%1$s-aide">%5$s</p>',
		esc_attr( $args['label_for'] ),
		esc_attr( $args['cle'] ),
		esc_attr( $reseaux[ $args['cle'] ] ),
		esc_attr( $args['exemple'] ),
		esc_html__( 'Adresse complète, commençant par https://. Laisser vide pour masquer l’icône.', 'asso-core' )
	);
}

/**
 * Assainit les adresses : seules les adresses https du bon réseau sont gardées.
 *
 * @param mixed $valeurs Valeurs envoyées par le formulaire.
 * @return array
 */
function asso_core_assainir_reseaux( $valeurs ) {
	$valeurs  = (array) $valeurs;
	$resultat = array();
	foreach ( asso_core_reseaux_pris_en_charge() as $cle => $reseau ) {
		$url  = isset( $valeurs[ $cle ] ) ? esc_url_raw( trim( (string) $valeurs[ $cle ] ), array( 'https' ) ) : '';
		$hote = $url ? strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) ) : '';
		if ( $url && $hote !== $reseau['domaine'] && ! str_ends_with( $hote, '.' . $reseau['domaine'] ) ) {
			add_settings_error(
				'asso_reseaux',
				'asso_reseaux_' . $cle,
				/* translators: 1: nom du réseau, 2: domaine attendu. */
				sprintf( __( '%1$s : l’adresse doit commencer par https:// et pointer vers %2$s. Elle n’a pas été enregistrée.', 'asso-core' ), $reseau['nom'], $reseau['domaine'] )
			);
			$url = '';
		}
		$resultat[ $cle ] = $url;
	}
	return $resultat;
}

/**
 * Ajoute la page Réglages > Réseaux sociaux.
 */
function asso_core_menu_reseaux() {
	add_options_page(
		__( 'Réseaux sociaux', 'asso-core' ),
		__( 'Réseaux sociaux', 'asso-core' ),
		'manage_options',
		'asso-reseaux',
		'asso_core_page_reseaux'
	);
}
add_action( 'admin_menu', 'asso_core_menu_reseaux' );

/**
 * Affiche la page Réglages > Réseaux sociaux (le nonce est géré par settings_fields()).
 */
function asso_core_page_reseaux() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p><?php esc_html_e( 'Les icônes Facebook et Instagram s’affichent dans le pied de page du site dès que leur adresse est saisie ici.', 'asso-core' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'asso_reseaux' );
			do_settings_sections( 'asso-reseaux' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
