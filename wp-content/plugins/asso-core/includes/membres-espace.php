<?php
/**
 * Pages de l'espace membres (codes courts placés dans les pages créées par
 * scripts/creer-espace-membres.php) :
 * - [asso_espace_membres] : accueil (cotisation, annonces, documents) ;
 * - [asso_profil] : coordonnées, choix pour l'annuaire, mot de passe ;
 * - [asso_annuaire] : membres qui ont accepté d'y figurer.
 * L'accès est déjà filtré par asso_core_proteger_pages_membres() ; chaque code court
 * revérifie la capacité « asso_espace_membres ».
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Message pour un compte connecté sans accès à l'espace.
 *
 * @return string
 */
function asso_core_refus_espace() {
	return '<p class="asso-message asso-message--info">' . esc_html__( 'Votre compte n’a pas accès à l’espace membres. Contactez le bureau de l’association.', 'asso-core' ) . '</p>';
}

/**
 * Contenu d'une annonce, mis en forme comme un article (blocs ou texte simple).
 *
 * @param WP_Post $annonce Annonce.
 * @return string
 */
function asso_core_contenu_annonce( WP_Post $annonce ) {
	$contenu = has_blocks( $annonce->post_content ) ? do_blocks( $annonce->post_content ) : wpautop( $annonce->post_content );
	return wp_kses_post( wptexturize( $contenu ) );
}

/**
 * Bloc « Ma cotisation ».
 *
 * @param int $user_id Membre.
 * @return string
 */
function asso_core_bloc_cotisation( $user_id ) {
	$cotisations = asso_core_cotisations( $user_id );
	$html        = '<section class="asso-espace__bloc" aria-labelledby="asso-titre-cotisation"><h2 id="asso-titre-cotisation">' . esc_html__( 'Ma cotisation', 'asso-core' ) . '</h2>';

	if ( ! $cotisations ) {
		$html .= '<p>' . esc_html__( 'Aucune cotisation n’est encore enregistrée sur votre compte. Si vous venez de payer par Zelle, le trésorier l’enregistrera après avoir vérifié sa réception.', 'asso-core' ) . '</p>';
		$html .= '<p><a href="' . esc_url( home_url( '/adhesion/' ) ) . '">' . esc_html__( 'Comment payer ma cotisation par Zelle', 'asso-core' ) . '</a></p>';
		return $html . '</section>';
	}

	$derniere = $cotisations[0];
	$montant  = asso_core_texte_montant( $derniere['montant'] );
	$html    .= '<p class="asso-espace__cotisation"><strong>' . esc_html( $derniere['periode'] ) . '</strong> : ';
	/* translators: %s: date de réception. */
	$html .= esc_html( sprintf( __( 'reçue le %s', 'asso-core' ), asso_core_texte_date_cotisation( $derniere['date'] ) ) );
	$html .= $montant ? ' (' . $montant . ')' : '';
	$html .= '.</p>';
	if ( $derniere['note'] ) {
		$html .= '<p>' . esc_html( $derniere['note'] ) . '</p>';
	}
	$html .= '<p class="asso-espace__mention">' . esc_html__( 'Informations enregistrées à la main par le trésorier. Une question ? Écrivez au bureau depuis la page Contact.', 'asso-core' ) . '</p>';

	if ( count( $cotisations ) > 1 ) {
		$html .= '<details><summary>' . esc_html__( 'Voir l’historique', 'asso-core' ) . '</summary><ul>';
		foreach ( array_slice( $cotisations, 1 ) as $cotisation ) {
			$montant = asso_core_texte_montant( $cotisation['montant'] );
			/* translators: 1: période, 2: date de réception. */
			$html .= '<li>' . esc_html( sprintf( __( '%1$s : reçue le %2$s', 'asso-core' ), $cotisation['periode'], asso_core_texte_date_cotisation( $cotisation['date'] ) ) ) . ( $montant ? ' (' . $montant . ')' : '' ) . '</li>';
		}
		$html .= '</ul></details>';
	}
	return $html . '</section>';
}

/**
 * Bloc « Annonces ».
 *
 * @return string
 */
function asso_core_bloc_annonces() {
	$annonces = get_posts(
		array(
			'post_type'   => 'asso_annonce',
			'post_status' => 'publish',
			'numberposts' => 5,
		)
	);
	$html = '<section class="asso-espace__bloc" aria-labelledby="asso-titre-annonces"><h2 id="asso-titre-annonces">' . esc_html__( 'Annonces', 'asso-core' ) . '</h2>';
	if ( ! $annonces ) {
		return $html . '<p>' . esc_html__( 'Aucune annonce pour le moment.', 'asso-core' ) . '</p></section>';
	}
	foreach ( $annonces as $annonce ) {
		$html .= '<article class="asso-annonce"><h3>' . esc_html( get_the_title( $annonce ) ) . '</h3>';
		$html .= '<p class="asso-annonce__date"><time datetime="' . esc_attr( get_post_time( 'c', true, $annonce ) ) . '">' . esc_html( get_the_date( 'l j F Y', $annonce ) ) . '</time></p>';
		$html .= '<div class="asso-annonce__contenu">' . asso_core_contenu_annonce( $annonce ) . '</div></article>';
	}
	return $html . '</section>';
}

/**
 * Bloc « Documents ».
 *
 * @return string
 */
function asso_core_bloc_documents() {
	$documents = get_posts(
		array(
			'post_type'   => 'asso_document',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_key'    => 'asso_fichier', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- quelques documents seulement.
		)
	);
	$html = '<section class="asso-espace__bloc" aria-labelledby="asso-titre-documents"><h2 id="asso-titre-documents">' . esc_html__( 'Documents', 'asso-core' ) . '</h2>';
	if ( ! $documents ) {
		return $html . '<p>' . esc_html__( 'Aucun document pour le moment.', 'asso-core' ) . '</p></section>';
	}
	$html .= '<ul class="asso-documents">';
	foreach ( $documents as $document ) {
		$extension = strtoupper( pathinfo( (string) get_post_meta( $document->ID, 'asso_fichier_nom', true ), PATHINFO_EXTENSION ) );
		$taille    = size_format( (int) get_post_meta( $document->ID, 'asso_fichier_taille', true ) );
		$html     .= '<li><a href="' . esc_url( asso_core_url_document( $document->ID ) ) . '">' . esc_html( get_the_title( $document ) ) . '</a>';
		$html     .= ' <span class="asso-documents__infos">(' . esc_html( trim( $extension . ', ' . $taille, ', ' ) ) . ')</span>';
		if ( has_excerpt( $document ) ) {
			$html .= '<br><span class="asso-documents__description">' . esc_html( get_the_excerpt( $document ) ) . '</span>';
		}
		$html .= '</li>';
	}
	return $html . '</ul></section>';
}

/**
 * Accueil de l'espace membres : [asso_espace_membres].
 *
 * @return string
 */
function asso_core_page_espace() {
	if ( ! current_user_can( 'asso_espace_membres' ) ) {
		return asso_core_refus_espace();
	}
	$utilisateur = wp_get_current_user();
	$prenom      = $utilisateur->first_name ? $utilisateur->first_name : $utilisateur->display_name;

	/* translators: %s: prénom du membre. */
	$html  = '<div class="asso-espace"><p class="asso-espace__bonjour">' . esc_html( sprintf( __( 'Bonjour %s, bienvenue dans votre espace !', 'asso-core' ), $prenom ) ) . '</p>';
	$html .= '<nav class="asso-espace__liens" aria-label="' . esc_attr__( 'Espace membres', 'asso-core' ) . '"><ul>';
	$html .= '<li><a href="' . esc_url( asso_core_url_espace( 'mon-profil' ) ) . '">' . esc_html__( 'Mon profil', 'asso-core' ) . '</a></li>';
	$html .= '<li><a href="' . esc_url( asso_core_url_espace( 'annuaire' ) ) . '">' . esc_html__( 'Annuaire des membres', 'asso-core' ) . '</a></li>';
	$html .= '<li><a href="' . esc_url( wp_logout_url() ) . '">' . esc_html__( 'Se déconnecter', 'asso-core' ) . '</a></li>';
	$html .= '</ul></nav>';
	$html .= asso_core_bloc_cotisation( $utilisateur->ID );
	$html .= asso_core_bloc_annonces();
	$html .= asso_core_bloc_documents();
	return $html . '</div>';
}
add_shortcode( 'asso_espace_membres', 'asso_core_page_espace' );

/* ---------- Mon profil ---------- */

/**
 * Traite le formulaire « Mon profil » (envoyé vers la page elle-même).
 */
function asso_core_traiter_profil() {
	if ( ! is_page( 'mon-profil' ) || 'POST' !== $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['asso_profil_nonce'] ) ) {
		return;
	}
	$page = asso_core_url_espace( 'mon-profil' );
	$fin  = static function ( $code ) use ( $page ) {
		wp_safe_redirect( add_query_arg( 'profil', $code, $page ) );
		exit;
	};
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['asso_profil_nonce'] ) ), 'asso_profil' ) ) {
		$fin( 'expire' );
	}
	if ( ! current_user_can( 'asso_espace_membres' ) ) {
		$fin( 'refus' );
	}

	$utilisateur = wp_get_current_user();
	$email       = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$actuel      = isset( $_POST['mdp_actuel'] ) ? (string) wp_unslash( $_POST['mdp_actuel'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- mot de passe : jamais modifié.
	$nouveau     = isset( $_POST['mdp_nouveau'] ) ? (string) wp_unslash( $_POST['mdp_nouveau'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$confirme    = isset( $_POST['mdp_confirme'] ) ? (string) wp_unslash( $_POST['mdp_confirme'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( ! is_email( $email ) ) {
		$fin( 'email' );
	}
	$change_email = strtolower( $email ) !== strtolower( $utilisateur->user_email );
	if ( $change_email && email_exists( $email ) ) {
		$fin( 'email_pris' );
	}
	// Changer d'e-mail ou de mot de passe demande le mot de passe actuel.
	if ( ( $change_email || '' !== $nouveau ) && ! wp_check_password( $actuel, $utilisateur->user_pass, $utilisateur->ID ) ) {
		$fin( 'mdp_actuel' );
	}
	if ( '' !== $nouveau && ( strlen( $nouveau ) < 10 || $nouveau !== $confirme ) ) {
		$fin( 'mdp_nouveau' );
	}

	$donnees = array(
		'ID'         => $utilisateur->ID,
		'first_name' => isset( $_POST['prenom'] ) ? sanitize_text_field( wp_unslash( $_POST['prenom'] ) ) : '',
		'last_name'  => isset( $_POST['nom'] ) ? sanitize_text_field( wp_unslash( $_POST['nom'] ) ) : '',
		'user_email' => $email,
	);
	$donnees['display_name'] = trim( $donnees['first_name'] . ' ' . $donnees['last_name'] ) ? trim( $donnees['first_name'] . ' ' . $donnees['last_name'] ) : $utilisateur->display_name;
	if ( '' !== $nouveau ) {
		$donnees['user_pass'] = $nouveau; // wp_update_user() garde la session ouverte.
	}
	$resultat = wp_update_user( $donnees );
	if ( is_wp_error( $resultat ) ) {
		$fin( 'erreur' );
	}

	update_user_meta( $utilisateur->ID, 'asso_telephone', isset( $_POST['telephone'] ) ? sanitize_text_field( wp_unslash( $_POST['telephone'] ) ) : '' );
	$section = isset( $_POST['section'] ) ? sanitize_key( wp_unslash( $_POST['section'] ) ) : '';
	update_user_meta( $utilisateur->ID, 'asso_section', array_key_exists( $section, asso_core_sections_membres() ) ? $section : '' );
	foreach ( array( 'asso_annuaire', 'asso_annuaire_email', 'asso_annuaire_telephone' ) as $cle ) {
		if ( ! empty( $_POST[ $cle ] ) ) {
			update_user_meta( $utilisateur->ID, $cle, '1' );
		} else {
			delete_user_meta( $utilisateur->ID, $cle );
		}
	}
	$fin( 'ok' );
}
add_action( 'template_redirect', 'asso_core_traiter_profil' );

/**
 * Formulaire « Mon profil » : [asso_profil].
 *
 * @return string
 */
function asso_core_page_profil() {
	if ( ! current_user_can( 'asso_espace_membres' ) ) {
		return asso_core_refus_espace();
	}
	$u        = wp_get_current_user();
	$code     = isset( $_GET['profil'] ) ? sanitize_key( wp_unslash( $_GET['profil'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple affichage.
	$messages = array(
		'ok'          => array( 'succes', __( 'Vos informations sont enregistrées.', 'asso-core' ) ),
		'email'       => array( 'erreur', __( 'L’adresse e-mail n’est pas valide.', 'asso-core' ) ),
		'email_pris'  => array( 'erreur', __( 'Cette adresse e-mail est déjà utilisée par un autre compte.', 'asso-core' ) ),
		'mdp_actuel'  => array( 'erreur', __( 'Pour changer votre e-mail ou votre mot de passe, saisissez votre mot de passe actuel (exact).', 'asso-core' ) ),
		'mdp_nouveau' => array( 'erreur', __( 'Le nouveau mot de passe doit faire au moins 10 caractères et être saisi deux fois à l’identique.', 'asso-core' ) ),
		'expire'      => array( 'erreur', __( 'La page a expiré. Merci de réessayer.', 'asso-core' ) ),
		'refus'       => array( 'erreur', __( 'Action non autorisée.', 'asso-core' ) ),
		'erreur'      => array( 'erreur', __( 'Une erreur est survenue. Merci de réessayer.', 'asso-core' ) ),
	);
	$section  = get_user_meta( $u->ID, 'asso_section', true );
	$champ    = static function ( $id, $nom, $libelle, $valeur, $type = 'text', $extra = '' ) {
		return sprintf(
			'<p class="asso-champ"><label for="%1$s">%3$s</label><input type="%5$s" id="%1$s" name="%2$s" value="%4$s"%6$s></p>',
			esc_attr( $id ),
			esc_attr( $nom ),
			esc_html( $libelle ),
			esc_attr( $valeur ),
			esc_attr( $type ),
			$extra
		);
	};
	$case = static function ( $cle, $libelle ) use ( $u ) {
		return sprintf(
			'<p class="asso-case"><input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s> <label for="%1$s">%3$s</label></p>',
			esc_attr( $cle ),
			checked( '1', get_user_meta( $u->ID, $cle, true ), false ) ? ' checked' : '',
			esc_html( $libelle )
		);
	};

	$html = '<form class="asso-connexion asso-profil" method="post" action="' . esc_url( asso_core_url_espace( 'mon-profil' ) ) . '">';
	if ( isset( $messages[ $code ] ) ) {
		$html .= sprintf( '<p class="asso-connexion__message asso-connexion__message--%1$s" role="%2$s">%3$s</p>', esc_attr( $messages[ $code ][0] ), 'erreur' === $messages[ $code ][0] ? 'alert' : 'status', esc_html( $messages[ $code ][1] ) );
	}

	$html .= '<fieldset><legend>' . esc_html__( 'Mes coordonnées', 'asso-core' ) . '</legend>';
	$html .= $champ( 'asso-prenom', 'prenom', __( 'Prénom', 'asso-core' ), $u->first_name, 'text', ' autocomplete="given-name"' );
	$html .= $champ( 'asso-nom', 'nom', __( 'Nom', 'asso-core' ), $u->last_name, 'text', ' autocomplete="family-name"' );
	$html .= $champ( 'asso-email', 'email', __( 'Adresse e-mail', 'asso-core' ), $u->user_email, 'email', ' autocomplete="email" required' );
	$html .= $champ( 'asso-telephone', 'telephone', __( 'Téléphone (facultatif)', 'asso-core' ), get_user_meta( $u->ID, 'asso_telephone', true ), 'tel', ' autocomplete="tel"' );
	$html .= '<fieldset class="asso-choix"><legend>' . esc_html__( 'Ma section', 'asso-core' ) . '</legend>';
	foreach ( asso_core_sections_membres() as $cle => $nom ) {
		$html .= sprintf( '<p class="asso-case"><input type="radio" id="asso-section-%1$s" name="section" value="%1$s"%2$s> <label for="asso-section-%1$s">%3$s</label></p>', esc_attr( $cle ), checked( $section, $cle, false ) ? ' checked' : '', esc_html( $nom ) );
	}
	$html .= '</fieldset></fieldset>';

	$html .= '<fieldset><legend>' . esc_html__( 'Annuaire des membres', 'asso-core' ) . '</legend>';
	$html .= '<p class="asso-aide">' . esc_html__( 'Vous choisissez ce que les autres membres connectés voient de vous. Rien n’apparaît sans votre accord.', 'asso-core' ) . '</p>';
	$html .= $case( 'asso_annuaire', __( 'Apparaître dans l’annuaire (prénom, nom et section)', 'asso-core' ) );
	$html .= $case( 'asso_annuaire_email', __( 'Montrer mon e-mail dans l’annuaire', 'asso-core' ) );
	$html .= $case( 'asso_annuaire_telephone', __( 'Montrer mon téléphone dans l’annuaire', 'asso-core' ) );
	$html .= '</fieldset>';

	$html .= '<fieldset><legend>' . esc_html__( 'Changer mon mot de passe', 'asso-core' ) . '</legend>';
	$html .= '<p class="asso-aide">' . esc_html__( 'Laissez ces champs vides pour garder votre mot de passe. Le mot de passe actuel est aussi demandé pour changer d’adresse e-mail.', 'asso-core' ) . '</p>';
	$html .= $champ( 'asso-mdp-actuel', 'mdp_actuel', __( 'Mot de passe actuel', 'asso-core' ), '', 'password', ' autocomplete="current-password"' );
	$html .= $champ( 'asso-mdp-nouveau', 'mdp_nouveau', __( 'Nouveau mot de passe (10 caractères au moins)', 'asso-core' ), '', 'password', ' autocomplete="new-password" minlength="10"' );
	$html .= $champ( 'asso-mdp-confirme', 'mdp_confirme', __( 'Nouveau mot de passe, une seconde fois', 'asso-core' ), '', 'password', ' autocomplete="new-password" minlength="10"' );
	$html .= '</fieldset>';

	$html .= wp_nonce_field( 'asso_profil', 'asso_profil_nonce', false, false );
	$html .= '<p class="asso-connexion__actions"><button type="submit" class="wp-element-button">' . esc_html__( 'Enregistrer', 'asso-core' ) . '</button> <a href="' . esc_url( asso_core_url_espace() ) . '">' . esc_html__( 'Retour à mon espace', 'asso-core' ) . '</a></p>';
	return $html . '</form>';
}
add_shortcode( 'asso_profil', 'asso_core_page_profil' );

/* ---------- Annuaire ---------- */

/**
 * Annuaire des membres : [asso_annuaire]. Filtre facultatif ?section=dallas|oklahoma.
 *
 * @return string
 */
function asso_core_page_annuaire() {
	if ( ! current_user_can( 'asso_espace_membres' ) ) {
		return asso_core_refus_espace();
	}
	$sections = asso_core_sections_membres();
	$filtre   = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple filtre d'affichage.
	$filtre   = array_key_exists( $filtre, $sections ) ? $filtre : '';
	$requete  = array(
		'capability' => 'asso_espace_membres',
		'meta_query' => array( array( 'key' => 'asso_annuaire', 'value' => '1' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- peu de comptes.
		'orderby'    => 'display_name',
		'order'      => 'ASC',
	);
	if ( $filtre ) {
		$requete['meta_query'][] = array( 'key' => 'asso_section', 'value' => $filtre );
	}
	$membres = get_users( $requete );

	$html  = '<div class="asso-annuaire">';
	$html .= '<p class="asso-aide">' . esc_html__( 'Seuls les membres qui l’ont accepté apparaissent ici, avec les informations qu’ils ont choisi de partager. Ces informations sont réservées aux membres : merci de ne pas les diffuser.', 'asso-core' ) . ' <a href="' . esc_url( asso_core_url_espace( 'mon-profil' ) ) . '">' . esc_html__( 'Régler ma présence dans l’annuaire', 'asso-core' ) . '</a></p>';

	$html .= '<nav class="asso-filtre-sections" aria-label="' . esc_attr__( 'Filtrer par section', 'asso-core' ) . '"><ul>';
	$html .= '<li><a href="' . esc_url( asso_core_url_espace( 'annuaire' ) ) . '"' . ( '' === $filtre ? ' aria-current="true"' : '' ) . '>' . esc_html__( 'Toutes les sections', 'asso-core' ) . '</a></li>';
	foreach ( $sections as $cle => $nom ) {
		$html .= '<li><a href="' . esc_url( add_query_arg( 'section', $cle, asso_core_url_espace( 'annuaire' ) ) ) . '"' . ( $filtre === $cle ? ' aria-current="true"' : '' ) . '>' . esc_html( $nom ) . '</a></li>';
	}
	$html .= '</ul></nav>';

	if ( ! $membres ) {
		return $html . '<p>' . esc_html__( 'Aucun membre à afficher pour le moment.', 'asso-core' ) . '</p></div>';
	}
	$html .= '<ul class="asso-annuaire__liste">';
	foreach ( $membres as $membre ) {
		$section = get_user_meta( $membre->ID, 'asso_section', true );
		$tel     = get_user_meta( $membre->ID, 'asso_telephone', true );
		$html   .= '<li class="asso-annuaire__membre"><p class="asso-annuaire__nom">' . esc_html( $membre->display_name ) . '</p>';
		if ( isset( $sections[ $section ] ) ) {
			/* translators: %s: nom de la section. */
			$html .= '<p class="asso-annuaire__section">' . esc_html( sprintf( __( 'Section %s', 'asso-core' ), $sections[ $section ] ) ) . '</p>';
		}
		if ( '1' === get_user_meta( $membre->ID, 'asso_annuaire_email', true ) ) {
			$courriel = antispambot( $membre->user_email );
			$html    .= '<p><a href="' . esc_url( 'mailto:' . $courriel ) . '">' . esc_html( $courriel ) . '</a></p>';
		}
		if ( $tel && '1' === get_user_meta( $membre->ID, 'asso_annuaire_telephone', true ) ) {
			$html .= '<p><a href="' . esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $tel ) ) . '">' . esc_html( $tel ) . '</a></p>';
		}
		$html .= '</li>';
	}
	return $html . '</ul></div>';
}
add_shortcode( 'asso_annuaire', 'asso_core_page_annuaire' );
