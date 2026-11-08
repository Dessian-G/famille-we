<?php
/**
 * Fiche d'un membre dans WP Admin : coordonnées, choix pour l'annuaire et cotisations.
 *
 * Le site ne sait pas si un paiement Zelle est arrivé : le trésorier enregistre chaque
 * cotisation reçue, à la main (période, date de réception, montant). Rien n'est automatique.
 *
 * Métadonnées du compte :
 * - asso_telephone, asso_section (dallas|oklahoma) ;
 * - asso_annuaire, asso_annuaire_email, asso_annuaire_telephone ('1' ou absent) ;
 * - asso_cotisations : liste de { periode, date (AAAA-MM-JJ), montant, note }.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sections proposées aux membres (identifiant => nom).
 *
 * @return array
 */
function asso_core_sections_membres() {
	return array(
		'dallas'   => __( 'Dallas', 'asso-core' ),
		'oklahoma' => __( 'Oklahoma', 'asso-core' ),
	);
}

/**
 * Cotisations enregistrées d'un membre, de la plus récente à la plus ancienne.
 *
 * @param int $user_id Compte.
 * @return array[]
 */
function asso_core_cotisations( $user_id ) {
	$liste = get_user_meta( $user_id, 'asso_cotisations', true );
	$liste = is_array( $liste ) ? array_values( $liste ) : array();
	usort(
		$liste,
		static function ( $a, $b ) {
			return strcmp( (string) $b['date'], (string) $a['date'] );
		}
	);
	return $liste;
}

/**
 * Montant d'une cotisation en texte (« 25 $ US »), vide si non renseigné.
 *
 * @param string $montant Montant enregistré.
 * @return string Texte échappé.
 */
function asso_core_texte_montant( $montant ) {
	if ( '' === (string) $montant ) {
		return '';
	}
	$valeur    = (float) $montant;
	$decimales = floor( $valeur ) === $valeur ? 0 : 2;
	return esc_html( number_format_i18n( $valeur, $decimales ) ) . '&nbsp;$&nbsp;US';
}

/**
 * Date de réception en toutes lettres (« 12 mars 2026 »).
 *
 * @param string $date Date AAAA-MM-JJ.
 * @return string
 */
function asso_core_texte_date_cotisation( $date ) {
	$objet = asso_core_date( $date );
	return $objet ? asso_core_formater( $objet, 'j F Y' ) : '';
}

/**
 * Section « La Famille Wê » de la fiche d'un compte dans WP Admin.
 *
 * @param WP_User $utilisateur Compte affiché.
 */
function asso_core_fiche_membre( $utilisateur ) {
	$gestion = current_user_can( 'asso_gerer_membres' );
	if ( ! $gestion && get_current_user_id() !== $utilisateur->ID ) {
		return;
	}
	wp_nonce_field( 'asso_fiche_membre_' . $utilisateur->ID, 'asso_fiche_membre_nonce' );
	$section = get_user_meta( $utilisateur->ID, 'asso_section', true );
	?>
	<h2><?php esc_html_e( 'La Famille Wê : espace membres', 'asso-core' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="asso_telephone"><?php esc_html_e( 'Téléphone', 'asso-core' ); ?></label></th>
			<td><input type="tel" class="regular-text" id="asso_telephone" name="asso_telephone" value="<?php echo esc_attr( get_user_meta( $utilisateur->ID, 'asso_telephone', true ) ); ?>"></td>
		</tr>
		<tr>
			<th><label for="asso_section"><?php esc_html_e( 'Section', 'asso-core' ); ?></label></th>
			<td><select id="asso_section" name="asso_section">
				<option value=""><?php esc_html_e( '— Non précisée —', 'asso-core' ); ?></option>
				<?php foreach ( asso_core_sections_membres() as $cle => $nom ) : ?>
					<option value="<?php echo esc_attr( $cle ); ?>" <?php selected( $section, $cle ); ?>><?php echo esc_html( $nom ); ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Annuaire des membres', 'asso-core' ); ?></th>
			<td>
				<p class="description"><?php esc_html_e( 'Ces choix appartiennent au membre, qui les règle lui-même dans « Mon profil ». Ne les modifier qu’à sa demande.', 'asso-core' ); ?></p>
				<?php
				foreach (
					array(
						'asso_annuaire'           => __( 'Apparaît dans l’annuaire (nom et section)', 'asso-core' ),
						'asso_annuaire_email'     => __( 'Montre son e-mail dans l’annuaire', 'asso-core' ),
						'asso_annuaire_telephone' => __( 'Montre son téléphone dans l’annuaire', 'asso-core' ),
					) as $cle => $libelle
				) {
					printf(
						'<label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label><br>',
						esc_attr( $cle ),
						checked( '1', get_user_meta( $utilisateur->ID, $cle, true ), false ),
						esc_html( $libelle )
					);
				}
				?>
			</td>
		</tr>
	</table>
	<?php
	if ( ! $gestion ) {
		return;
	}
	$cotisations = asso_core_cotisations( $utilisateur->ID );
	?>
	<h3><?php esc_html_e( 'Cotisations reçues (saisie du trésorier)', 'asso-core' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Enregistrez une cotisation seulement après avoir vérifié sa réception sur le compte Zelle de l’association. Le membre voit ces lignes dans son espace.', 'asso-core' ); ?></p>
	<?php if ( $cotisations ) : ?>
		<table class="widefat striped" style="max-width:48rem;margin:1em 0">
			<thead><tr>
				<th><?php esc_html_e( 'Période', 'asso-core' ); ?></th>
				<th><?php esc_html_e( 'Reçue le', 'asso-core' ); ?></th>
				<th><?php esc_html_e( 'Montant', 'asso-core' ); ?></th>
				<th><?php esc_html_e( 'Note', 'asso-core' ); ?></th>
				<th><?php esc_html_e( 'Supprimer', 'asso-core' ); ?></th>
			</tr></thead>
			<tbody>
			<?php foreach ( $cotisations as $i => $cotisation ) : ?>
				<tr>
					<td><?php echo esc_html( $cotisation['periode'] ); ?></td>
					<td><?php echo esc_html( asso_core_texte_date_cotisation( $cotisation['date'] ) ); ?></td>
					<td><?php echo asso_core_texte_montant( $cotisation['montant'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé par la fonction. ?></td>
					<td><?php echo esc_html( $cotisation['note'] ); ?></td>
					<td><label><input type="checkbox" name="asso_cotisation_supprimer[]" value="<?php echo esc_attr( $cotisation['id'] ); ?>"> <span class="screen-reader-text"><?php esc_html_e( 'Supprimer cette ligne', 'asso-core' ); ?></span></label></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><em><?php esc_html_e( 'Aucune cotisation enregistrée.', 'asso-core' ); ?></em></p>
	<?php endif; ?>
	<table class="form-table" role="presentation">
		<tr>
			<th><?php esc_html_e( 'Ajouter une cotisation reçue', 'asso-core' ); ?></th>
			<td>
				<label for="asso_cotisation_periode"><?php esc_html_e( 'Période (ex. 2026)', 'asso-core' ); ?></label><br>
				<input type="text" id="asso_cotisation_periode" name="asso_cotisation_periode" class="regular-text" style="max-width:12rem"><br>
				<label for="asso_cotisation_date"><?php esc_html_e( 'Date de réception', 'asso-core' ); ?></label><br>
				<input type="date" id="asso_cotisation_date" name="asso_cotisation_date"><br>
				<label for="asso_cotisation_montant"><?php esc_html_e( 'Montant ($ US)', 'asso-core' ); ?></label><br>
				<input type="text" inputmode="decimal" id="asso_cotisation_montant" name="asso_cotisation_montant" style="max-width:8rem"><br>
				<label for="asso_cotisation_note"><?php esc_html_e( 'Note (facultative, visible par le membre)', 'asso-core' ); ?></label><br>
				<input type="text" id="asso_cotisation_note" name="asso_cotisation_note" class="regular-text">
				<p class="description"><?php esc_html_e( 'La période et la date de réception sont obligatoires pour enregistrer une ligne.', 'asso-core' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'asso_core_fiche_membre' );
add_action( 'edit_user_profile', 'asso_core_fiche_membre' );

/**
 * Enregistre la section « La Famille Wê » de la fiche.
 *
 * @param int $user_id Compte modifié.
 */
function asso_core_enregistrer_fiche_membre( $user_id ) {
	if ( ! isset( $_POST['asso_fiche_membre_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['asso_fiche_membre_nonce'] ) ), 'asso_fiche_membre_' . $user_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	update_user_meta( $user_id, 'asso_telephone', isset( $_POST['asso_telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['asso_telephone'] ) ) : '' );
	$section = isset( $_POST['asso_section'] ) ? sanitize_key( wp_unslash( $_POST['asso_section'] ) ) : '';
	update_user_meta( $user_id, 'asso_section', array_key_exists( $section, asso_core_sections_membres() ) ? $section : '' );
	foreach ( array( 'asso_annuaire', 'asso_annuaire_email', 'asso_annuaire_telephone' ) as $cle ) {
		if ( ! empty( $_POST[ $cle ] ) ) {
			update_user_meta( $user_id, $cle, '1' );
		} else {
			delete_user_meta( $user_id, $cle );
		}
	}

	if ( ! current_user_can( 'asso_gerer_membres' ) ) {
		return;
	}
	$cotisations = get_user_meta( $user_id, 'asso_cotisations', true );
	$cotisations = is_array( $cotisations ) ? $cotisations : array();

	$a_supprimer = isset( $_POST['asso_cotisation_supprimer'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['asso_cotisation_supprimer'] ) ) : array();
	$cotisations = array_values(
		array_filter(
			$cotisations,
			static function ( $cotisation ) use ( $a_supprimer ) {
				return ! in_array( (string) $cotisation['id'], $a_supprimer, true );
			}
		)
	);

	$periode = isset( $_POST['asso_cotisation_periode'] ) ? sanitize_text_field( wp_unslash( $_POST['asso_cotisation_periode'] ) ) : '';
	$date    = isset( $_POST['asso_cotisation_date'] ) ? asso_core_assainir_date( wp_unslash( $_POST['asso_cotisation_date'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- assaini par asso_core_assainir_date().
	if ( '' !== $periode && '' !== $date ) {
		$cotisations[] = array(
			'id'      => strtolower( wp_generate_password( 8, false ) ),
			'periode' => $periode,
			'date'    => $date,
			'montant' => isset( $_POST['asso_cotisation_montant'] ) ? asso_core_assainir_tarif( wp_unslash( $_POST['asso_cotisation_montant'] ) ) : '', // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- assaini par asso_core_assainir_tarif().
			'note'    => isset( $_POST['asso_cotisation_note'] ) ? sanitize_text_field( wp_unslash( $_POST['asso_cotisation_note'] ) ) : '',
		);
	}
	update_user_meta( $user_id, 'asso_cotisations', $cotisations );
}
add_action( 'personal_options_update', 'asso_core_enregistrer_fiche_membre' );
add_action( 'edit_user_profile_update', 'asso_core_enregistrer_fiche_membre' );

/**
 * Colonnes « Section » et « Dernière cotisation » dans la liste des comptes.
 *
 * @param array $colonnes Colonnes.
 * @return array
 */
function asso_core_colonnes_comptes( $colonnes ) {
	if ( current_user_can( 'asso_gerer_membres' ) ) {
		$colonnes['asso_section']    = __( 'Section', 'asso-core' );
		$colonnes['asso_cotisation'] = __( 'Dernière cotisation', 'asso-core' );
	}
	return $colonnes;
}
add_filter( 'manage_users_columns', 'asso_core_colonnes_comptes' );

/**
 * Contenu des colonnes ajoutées.
 *
 * @param string $valeur  Valeur par défaut.
 * @param string $colonne Colonne.
 * @param int    $user_id Compte.
 * @return string
 */
function asso_core_colonne_compte( $valeur, $colonne, $user_id ) {
	if ( 'asso_section' === $colonne ) {
		$sections = asso_core_sections_membres();
		$section  = get_user_meta( $user_id, 'asso_section', true );
		return isset( $sections[ $section ] ) ? esc_html( $sections[ $section ] ) : '—';
	}
	if ( 'asso_cotisation' === $colonne ) {
		$cotisations = asso_core_cotisations( $user_id );
		if ( ! $cotisations ) {
			return '—';
		}
		/* translators: 1: période, 2: date de réception. */
		return esc_html( sprintf( __( '%1$s (reçue le %2$s)', 'asso-core' ), $cotisations[0]['periode'], asso_core_texte_date_cotisation( $cotisations[0]['date'] ) ) );
	}
	return $valeur;
}
add_filter( 'manage_users_custom_column', 'asso_core_colonne_compte', 10, 3 );
