<?php
/**
 * Paiement par Zelle : réglages (Réglages > Zelle) et source de liaison « asso-core/zelle ».
 * Le destinataire et la cotisation sont saisis une seule fois dans WP Admin, sans toucher au code.
 * Le site ne traite aucun paiement et n'affiche jamais de coordonnées bancaires.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Réglages Zelle enregistrés, complétés par des valeurs vides.
 *
 * @return array{destinataire:string,titulaire:string,cotisation:string,periode:string}
 */
function asso_core_zelle_reglages() {
	return wp_parse_args(
		(array) get_option( 'asso_zelle', array() ),
		array(
			'destinataire' => '',
			'titulaire'    => '',
			'cotisation'   => '',
			'periode'      => '',
		)
	);
}

/**
 * Déclare l'option et ses champs (Settings API).
 */
function asso_core_enregistrer_reglages_zelle() {
	register_setting(
		'asso_zelle',
		'asso_zelle',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'asso_core_assainir_zelle',
			'default'           => array(),
		)
	);
	add_settings_section( 'asso_zelle_principal', '', '__return_false', 'asso-zelle' );

	$champs = array(
		'destinataire' => array( __( 'Destinataire Zelle', 'asso-core' ), __( 'E-mail ou numéro de téléphone enregistré chez Zelle.', 'asso-core' ) ),
		'titulaire'    => array( __( 'Nom du titulaire', 'asso-core' ), __( 'Tel qu’il s’affiche dans Zelle : les membres le vérifient avant d’envoyer.', 'asso-core' ) ),
		'cotisation'   => array( __( 'Montant de la cotisation ($ US)', 'asso-core' ), __( 'Par exemple 25 ou 12,50. Vide = à préciser.', 'asso-core' ) ),
		'periode'      => array( __( 'Période de la cotisation', 'asso-core' ), __( 'Par exemple « an » ou « mois ».', 'asso-core' ) ),
	);
	foreach ( $champs as $cle => $champ ) {
		add_settings_field(
			'asso_zelle_' . $cle,
			$champ[0],
			'asso_core_afficher_champ_zelle',
			'asso-zelle',
			'asso_zelle_principal',
			array(
				'cle'       => $cle,
				'aide'      => $champ[1],
				'label_for' => 'asso_zelle_' . $cle,
			)
		);
	}
}
add_action( 'admin_init', 'asso_core_enregistrer_reglages_zelle' );

/**
 * Affiche un champ de la page de réglages.
 *
 * @param array $args Clé, aide et identifiant du champ.
 */
function asso_core_afficher_champ_zelle( $args ) {
	$reglages = asso_core_zelle_reglages();
	printf(
		'<input type="text" class="regular-text" id="%1$s" name="asso_zelle[%2$s]" value="%3$s" aria-describedby="%1$s-aide"><p class="description" id="%1$s-aide">%4$s</p>',
		esc_attr( $args['label_for'] ),
		esc_attr( $args['cle'] ),
		esc_attr( $reglages[ $args['cle'] ] ),
		esc_html( $args['aide'] )
	);
}

/**
 * Assainit les réglages saisis.
 *
 * @param mixed $valeurs Valeurs envoyées par le formulaire.
 * @return array
 */
function asso_core_assainir_zelle( $valeurs ) {
	$valeurs = (array) $valeurs;
	return array(
		'destinataire' => sanitize_text_field( isset( $valeurs['destinataire'] ) ? $valeurs['destinataire'] : '' ),
		'titulaire'    => sanitize_text_field( isset( $valeurs['titulaire'] ) ? $valeurs['titulaire'] : '' ),
		'cotisation'   => asso_core_assainir_tarif( isset( $valeurs['cotisation'] ) ? $valeurs['cotisation'] : '' ),
		'periode'      => sanitize_text_field( isset( $valeurs['periode'] ) ? $valeurs['periode'] : '' ),
	);
}

/**
 * Ajoute la page Réglages > Zelle.
 */
function asso_core_menu_zelle() {
	add_options_page(
		__( 'Paiement par Zelle', 'asso-core' ),
		__( 'Zelle', 'asso-core' ),
		'manage_options',
		'asso-zelle',
		'asso_core_page_zelle'
	);
}
add_action( 'admin_menu', 'asso_core_menu_zelle' );

/**
 * Affiche la page Réglages > Zelle (le nonce est géré par settings_fields()).
 */
function asso_core_page_zelle() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p><?php esc_html_e( 'Ces informations s’affichent dans le bloc « Payer par Zelle » (pages Adhésion et Faire un don), ainsi que le montant de la cotisation sur la page Adhésion et dans la FAQ. Ne saisissez jamais de numéro de compte ni de numéro de routage.', 'asso-core' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'asso_zelle' );
			do_settings_sections( 'asso-zelle' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Enregistre la source de liaison « asso-core/zelle ».
 */
function asso_core_enregistrer_liaison_zelle() {
	register_block_bindings_source(
		'asso-core/zelle',
		array(
			'label'              => __( 'Zelle (asso-core)', 'asso-core' ),
			'get_value_callback' => 'asso_core_valeur_zelle',
		)
	);
}
add_action( 'init', 'asso_core_enregistrer_liaison_zelle' );

/**
 * Valeurs Zelle pour les blocs liés ; les repères [À FOURNIR] restent tant que rien n'est saisi.
 *
 * @param array $args Arguments de la liaison (« cle »).
 * @return string|null
 */
function asso_core_valeur_zelle( array $args ) {
	$reglages = asso_core_zelle_reglages();
	$cle      = isset( $args['cle'] ) ? sanitize_key( $args['cle'] ) : '';

	$montant = '' !== $reglages['cotisation']
		? asso_core_texte_tarif( $reglages['cotisation'] )
		: esc_html__( '[À FOURNIR]', 'asso-core' ) . '&nbsp;$&nbsp;US';
	$periode = '' !== $reglages['periode']
		/* translators: %s: période de la cotisation (an, mois…). */
		? esc_html( sprintf( __( 'par %s', 'asso-core' ), $reglages['periode'] ) )
		: esc_html__( 'par [À FOURNIR : période]', 'asso-core' );

	switch ( $cle ) {
		case 'ligne_destinataire':
			$destinataire = '' !== $reglages['destinataire']
				? '<span class="asso-zelle-destinataire">' . esc_html( $reglages['destinataire'] ) . '</span>'
				: esc_html__( '[À FOURNIR : e-mail ou numéro de téléphone enregistré chez Zelle]', 'asso-core' );
			$titulaire    = '' !== $reglages['titulaire']
				? '<strong>' . esc_html( $reglages['titulaire'] ) . '</strong>'
				: esc_html__( '[À FOURNIR : nom du titulaire tel qu’il s’affiche dans Zelle]', 'asso-core' );
			/* translators: 1: destinataire Zelle, 2: nom du titulaire. */
			return sprintf( __( '<strong>Destinataire&nbsp;:</strong> %1$s, au nom de %2$s.', 'asso-core' ), $destinataire, $titulaire );
		case 'ligne_montant':
			/* translators: 1: montant, 2: période. */
			return sprintf( __( '<strong>Montant&nbsp;:</strong> cotisation de %1$s %2$s, ou le montant de votre choix pour un don.', 'asso-core' ), $montant, $periode );
		case 'ligne_cotisation':
			/* translators: 1: montant, 2: période. */
			return sprintf( __( '<strong>Montant&nbsp;:</strong> %1$s %2$s.', 'asso-core' ), $montant, $periode );
		case 'cotisation_phrase':
			/* translators: 1: montant, 2: période. */
			return sprintf( __( '%1$s %2$s.', 'asso-core' ), $montant, $periode );
	}
	return null;
}
