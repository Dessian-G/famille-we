<?php
/**
 * Crée les formulaires Fluent Forms « Adhésion » et « Contact » (étape 5) :
 * champs en français, consentement obligatoire, aucune donnée sensible,
 * pot de miel anti-spam, redirection vers la page Merci, notification à l'association.
 *
 * Usage (depuis la racine du site) :
 *   studio.bat wp eval-file scripts/creer-formulaires.php
 *
 * Sans danger à relancer : un formulaire déjà présent (même titre) n'est pas modifié.
 * Les identifiants sont gardés dans l'option « asso_formulaires », lue par les motifs
 * du thème asso-theme/formulaire-adhesion et asso-theme/formulaire-contact.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

use FluentForm\App\Models\Form;
use FluentForm\App\Models\FormMeta;

if ( ! class_exists( Form::class ) ) {
	WP_CLI::error( 'Activez d’abord l’extension Fluent Forms.' );
}

$asso_admins = get_users(
	array(
		'role'   => 'administrator',
		'number' => 1,
		'fields' => 'ID',
	)
);
wp_set_current_user( (int) $asso_admins[0] );

$asso_courriel    = 'famillewededallas@gmail.com';
$asso_obligatoire = __( 'Ce champ est obligatoire.', 'asso-core' );
$asso_merci       = get_page_by_path( 'merci', OBJECT, 'page' );
$asso_merci_id    = $asso_merci ? (int) $asso_merci->ID : 0;
$asso_confid      = esc_url( home_url( '/confidentialite/' ) );

// Structures officielles des champs, fournies par Fluent Forms.
$asso_defauts = include WP_PLUGIN_DIR . '/fluentform/app/Services/FormBuilder/DefaultElements.php';
$asso_types   = array_merge( $asso_defauts['general'], $asso_defauts['advanced'] );

/**
 * Champ Fluent Forms à partir de sa structure par défaut.
 */
$asso_champ = static function ( $type, $nom, $libelle, $obligatoire = false, $options = null, $aide = '' ) use ( $asso_types, $asso_obligatoire ) {
	$champ                           = $asso_types[ $type ];
	$champ['attributes']['name']     = $nom;
	$champ['settings']['label']      = $libelle;
	$champ['settings']['help_message'] = $aide;
	if ( isset( $champ['attributes']['placeholder'] ) ) {
		$champ['attributes']['placeholder'] = '';
	}
	$champ['settings']['validation_rules']['required'] = array(
		'value'   => $obligatoire,
		'message' => $asso_obligatoire,
		'global'  => false,
	);
	if ( null !== $options ) {
		$champ['settings']['advanced_options'] = array();
		foreach ( $options as $option ) {
			$champ['settings']['advanced_options'][] = array(
				'label'      => $option,
				'value'      => $option,
				'calc_value' => '',
				'image'      => '',
			);
		}
	}
	$champ['uniqElKey'] = 'el_' . $nom;
	return $champ;
};

// Prénom et nom, obligatoires.
$asso_nom = $asso_types['input_name'];
foreach ( array( 'first_name' => __( 'Prénom', 'asso-core' ), 'last_name' => __( 'Nom', 'asso-core' ) ) as $asso_cle => $asso_libelle ) {
	$asso_nom['fields'][ $asso_cle ]['settings']['label']                        = $asso_libelle;
	$asso_nom['fields'][ $asso_cle ]['settings']['visible']                      = true;
	$asso_nom['fields'][ $asso_cle ]['attributes']['placeholder']                = '';
	$asso_nom['fields'][ $asso_cle ]['settings']['validation_rules']['required'] = array(
		'value'   => true,
		'message' => $asso_obligatoire,
		'global'  => false,
	);
}
$asso_nom['fields']['middle_name']['settings']['visible'] = false;
$asso_nom['settings']['admin_field_label']                = __( 'Nom', 'asso-core' );
$asso_nom['uniqElKey']                                    = 'el_names';

// Adresse e-mail, obligatoire et valide.
$asso_email = $asso_champ( 'input_email', 'email', __( 'Adresse e-mail', 'asso-core' ), true );
$asso_email['settings']['validation_rules']['email'] = array(
	'value'   => true,
	'message' => __( 'Veuillez saisir une adresse e-mail valide.', 'asso-core' ),
	'global'  => false,
);

// Consentement obligatoire (RGPD).
$asso_consentement = static function ( $usage ) use ( $asso_types, $asso_obligatoire, $asso_confid ) {
	$champ                                  = $asso_types['gdpr_agreement'];
	$champ['settings']['label']             = __( 'Consentement', 'asso-core' );
	$champ['settings']['admin_field_label'] = __( 'Consentement', 'asso-core' );
	$champ['settings']['tnc_html']          = sprintf(
		/* translators: 1: usage des données, 2: adresse de la politique de confidentialité. */
		__( 'J’accepte que La Famille Wê utilise ces informations %1$s. Voir la <a href="%2$s">politique de confidentialité</a>.', 'asso-core' ),
		$usage,
		$asso_confid
	);
	$champ['settings']['validation_rules']['required'] = array(
		'value'   => true,
		'message' => __( 'Merci de cocher cette case pour envoyer le formulaire.', 'asso-core' ),
		'global'  => false,
	);
	$champ['uniqElKey'] = 'el_consentement';
	return $champ;
};

/**
 * Bouton d'envoi aux couleurs du site (orange, texte encre).
 */
$asso_bouton = static function ( $texte ) {
	return array(
		'uniqElKey'      => 'el_envoyer',
		'element'        => 'button',
		'attributes'     => array(
			'type'  => 'submit',
			'class' => 'asso-envoyer',
		),
		'settings'       => array(
			'align'            => 'left',
			'button_style'     => '',
			'container_class'  => '',
			'help_message'     => '',
			'background_color' => '#F77F00',
			'button_size'      => 'md',
			'color'            => '#1F2A24',
			'button_ui'        => array(
				'type'    => 'default',
				'text'    => $texte,
				'img_url' => '',
			),
		),
		'editor_options' => array( 'title' => 'Submit Button' ),
	);
};

$asso_formulaires = array(
	'adhesion' => array(
		'titre'  => 'Adhésion — La Famille Wê',
		'sujet'  => __( 'Nouvelle demande d’adhésion : {inputs.names}', 'asso-core' ),
		'champs' => array(
			$asso_nom,
			$asso_email,
			$asso_champ( 'input_text', 'telephone', __( 'Téléphone (facultatif)', 'asso-core' ), false, null, __( 'Pour vous joindre au sujet de votre adhésion.', 'asso-core' ) ),
			$asso_champ( 'input_text', 'ville', __( 'Ville', 'asso-core' ), true ),
			$asso_champ( 'input_radio', 'section', __( 'Section', 'asso-core' ), true, array( __( 'Dallas', 'asso-core' ), __( 'Oklahoma', 'asso-core' ) ) ),
			$asso_champ( 'textarea', 'remarques', __( 'Remarques (facultatif)', 'asso-core' ) ),
			$asso_consentement( __( 'pour gérer mon adhésion', 'asso-core' ) ),
		),
		'bouton' => __( 'Envoyer ma demande d’adhésion', 'asso-core' ),
	),
	'contact'  => array(
		'titre'  => 'Contact — La Famille Wê',
		'sujet'  => __( 'Nouveau message : {inputs.names}', 'asso-core' ),
		'champs' => array(
			$asso_nom,
			$asso_email,
			$asso_champ( 'select', 'section', __( 'Section (facultatif)', 'asso-core' ), false, array( __( 'Dallas', 'asso-core' ), __( 'Oklahoma', 'asso-core' ), __( 'Je ne sais pas encore', 'asso-core' ) ) ),
			$asso_champ( 'textarea', 'message', __( 'Votre message', 'asso-core' ), true ),
			$asso_consentement( __( 'pour me répondre', 'asso-core' ) ),
		),
		'bouton' => __( 'Envoyer le message', 'asso-core' ),
	),
);

// Notification type de Fluent Forms, adaptée pour l'association.
$asso_modele       = Form::findPredefinedForm( array( 'predefined' => 'basic_contact_form' ) );
$asso_modele       = json_decode( $asso_modele['json'], true );
$asso_notification = $asso_modele[0]['notifications'];

$asso_ids = (array) get_option( 'asso_formulaires', array() );
foreach ( $asso_formulaires as $asso_cle => $asso_def ) {
	$asso_existant = Form::where( 'title', $asso_def['titre'] )->first();
	if ( $asso_existant ) {
		$asso_ids[ $asso_cle ] = (int) $asso_existant->id;
		WP_CLI::log( sprintf( 'déjà là  %s (#%d), non modifié', $asso_def['titre'], $asso_existant->id ) );
		continue;
	}

	// Ordre des champs pour Fluent Forms.
	foreach ( $asso_def['champs'] as $asso_index => &$asso_c ) {
		$asso_c['index'] = $asso_index;
	}
	unset( $asso_c );

	$asso_form = Form::create(
		Form::prepare(
			array(
				'title'       => $asso_def['titre'],
				'status'      => 'published',
				'type'        => 'form',
				'form_fields' => wp_json_encode(
					array(
						'fields'       => $asso_def['champs'],
						'submitButton' => $asso_bouton( $asso_def['bouton'] ),
					)
				),
			)
		)
	);

	$asso_reglages                 = Form::getFormsDefaultSettings();
	$asso_reglages['confirmation'] = array(
		'redirectTo'           => $asso_merci_id ? 'customPage' : 'samePage',
		'customPage'           => $asso_merci_id ? $asso_merci_id : null,
		'messageToShow'        => __( 'Merci, votre message a bien été envoyé.', 'asso-core' ),
		'samePageFormBehavior' => 'hide_form',
		'customUrl'            => null,
	);

	$asso_avis              = $asso_notification;
	$asso_avis['name']      = __( 'Avis à l’association', 'asso-core' );
	$asso_avis['enabled']   = true;
	$asso_avis['sendTo']    = array_merge( $asso_avis['sendTo'], array( 'type' => 'email', 'email' => $asso_courriel ) );
	$asso_avis['replyTo']   = '{inputs.email}';
	$asso_avis['subject']   = $asso_def['sujet'];
	$asso_avis['message']   = '<p>{all_data}</p>';

	FormMeta::store(
		$asso_form,
		array(
			array(
				'meta_key' => 'formSettings',
				'value'    => wp_json_encode( $asso_reglages ),
			),
			array(
				'meta_key' => 'notifications',
				'value'    => wp_json_encode( $asso_avis ),
			),
		)
	);

	$asso_ids[ $asso_cle ] = (int) $asso_form->id;
	WP_CLI::log( sprintf( 'créé     %s (#%d)', $asso_def['titre'], $asso_form->id ) );
}
update_option( 'asso_formulaires', $asso_ids, false );

// Pot de miel anti-spam : réglage global de Fluent Forms.
$asso_global = (array) get_option( '_fluentform_global_form_settings', array() );
if ( empty( $asso_global['misc']['honeypotStatus'] ) || 'yes' !== $asso_global['misc']['honeypotStatus'] ) {
	$asso_global['misc']['honeypotStatus'] = 'yes';
	update_option( '_fluentform_global_form_settings', $asso_global );
	WP_CLI::log( 'réglage  pot de miel anti-spam activé' );
}

WP_CLI::success( sprintf( 'Formulaires prêts : %s', wp_json_encode( $asso_ids ) ) );
