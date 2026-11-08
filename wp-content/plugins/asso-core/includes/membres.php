<?php
/**
 * Espace membres : rôle « Membre », page Connexion, protection des pages réservées,
 * limitation des essais de connexion et redirections.
 *
 * Les comptes sont créés par un administrateur (aucune inscription libre).
 * Capacités : « asso_espace_membres » (voir l'espace) et « asso_gerer_membres »
 * (gérer cotisations, annonces et documents), données aux administrateurs ; un futur
 * rôle « Bureau » ou « Trésorier » n'aura qu'à recevoir la seconde.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/** Version des rôles : l'augmenter pour réappliquer asso_core_installer_roles(). */
const ASSO_CORE_ROLES_VERSION = '1';

/** Nombre d'essais de connexion ratés avant blocage, et durée du blocage (secondes). */
const ASSO_CORE_ESSAIS_MAX     = 5;
const ASSO_CORE_ESSAIS_BLOCAGE = 15 * MINUTE_IN_SECONDS;

/**
 * Adresse de la page Connexion.
 *
 * @return string
 */
function asso_core_url_connexion() {
	return home_url( '/connexion/' );
}

/**
 * Adresse de l'espace membres (ou d'une de ses sous-pages).
 *
 * @param string $sous_page Adresse de la sous-page (ex. « mon-profil »), facultative.
 * @return string
 */
function asso_core_url_espace( $sous_page = '' ) {
	return home_url( '/espace-membres/' . ( $sous_page ? trailingslashit( $sous_page ) : '' ) );
}

/**
 * Crée le rôle « Membre » et donne les capacités de l'espace aux administrateurs.
 */
function asso_core_installer_roles() {
	if ( ! get_role( 'asso_membre' ) ) {
		add_role(
			'asso_membre',
			__( 'Membre', 'asso-core' ),
			array(
				'read'                => true,
				'asso_espace_membres' => true,
			)
		);
	}
	$admin = get_role( 'administrator' );
	if ( $admin ) {
		$admin->add_cap( 'asso_espace_membres' );
		$admin->add_cap( 'asso_gerer_membres' );
	}
	// Un compte créé dans WP Admin est un compte de membre, sauf choix contraire.
	update_option( 'default_role', 'asso_membre' );
	update_option( 'asso_core_roles_version', ASSO_CORE_ROLES_VERSION );
}

/**
 * Installe les rôles une fois (et à chaque nouvelle version des rôles).
 */
function asso_core_verifier_roles() {
	if ( get_option( 'asso_core_roles_version' ) !== ASSO_CORE_ROLES_VERSION ) {
		asso_core_installer_roles();
	}
}
add_action( 'init', 'asso_core_verifier_roles' );

/**
 * Vrai si la page affichée est l'espace membres ou l'une de ses sous-pages.
 *
 * @return bool
 */
function asso_core_est_page_membres() {
	if ( ! is_page() ) {
		return false;
	}
	$page = get_queried_object();
	if ( ! $page instanceof WP_Post ) {
		return false;
	}
	if ( 'espace-membres' === $page->post_name ) {
		return true;
	}
	foreach ( get_post_ancestors( $page ) as $parent ) {
		if ( 'espace-membres' === get_post_field( 'post_name', $parent ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Identifiants des pages Connexion et Espace membres (avec sous-pages), pour les exclure
 * de la recherche et du plan du site.
 *
 * @return int[]
 */
function asso_core_ids_pages_membres() {
	$ids    = array();
	$espace = get_page_by_path( 'espace-membres' );
	if ( $espace ) {
		$ids[] = $espace->ID;
		$ids   = array_merge( $ids, get_children( array( 'post_parent' => $espace->ID, 'post_type' => 'page', 'fields' => 'ids' ) ) );
	}
	$connexion = get_page_by_path( 'connexion' );
	if ( $connexion ) {
		$ids[] = $connexion->ID;
	}
	return array_map( 'intval', $ids );
}

/**
 * Pages réservées : un visiteur non connecté est envoyé vers la page Connexion.
 * Ces pages ne sont jamais mises en cache par le navigateur.
 */
function asso_core_proteger_pages_membres() {
	if ( ! asso_core_est_page_membres() ) {
		return;
	}
	nocache_headers();
	if ( ! is_user_logged_in() ) {
		$ici = home_url( add_query_arg( array() ) );
		wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( $ici ), asso_core_url_connexion() ) );
		exit;
	}
}
add_action( 'template_redirect', 'asso_core_proteger_pages_membres', 1 );

/**
 * Pages Connexion et Espace membres : pas d'indexation par les moteurs de recherche.
 *
 * @param array $robots Directives robots.
 * @return array
 */
function asso_core_pages_membres_non_indexees( $robots ) {
	if ( is_page( 'connexion' ) || asso_core_est_page_membres() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'asso_core_pages_membres_non_indexees' );

/**
 * Exclut les pages membres de la recherche du site.
 *
 * @param WP_Query $requete Requête.
 */
function asso_core_exclure_pages_membres_recherche( $requete ) {
	if ( ! is_admin() && $requete->is_main_query() && $requete->is_search() ) {
		$requete->set( 'post__not_in', array_merge( (array) $requete->get( 'post__not_in' ), asso_core_ids_pages_membres() ) );
	}
}
add_action( 'pre_get_posts', 'asso_core_exclure_pages_membres_recherche' );

/**
 * Exclut les pages membres du plan du site (wp-sitemap.xml).
 *
 * @param array  $args      Arguments de la requête.
 * @param string $post_type Type de contenu.
 * @return array
 */
function asso_core_exclure_pages_membres_plan( $args, $post_type ) {
	if ( 'page' === $post_type ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), asso_core_ids_pages_membres() );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'asso_core_exclure_pages_membres_plan', 10, 2 );

/* ---------- Connexion ---------- */

/**
 * Traite le formulaire de la page Connexion (envoyé vers la page elle-même).
 */
function asso_core_traiter_connexion() {
	if ( ! is_page( 'connexion' ) || 'POST' !== $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['asso_connexion_nonce'] ) ) {
		return;
	}
	nocache_headers();

	$retour = isset( $_POST['redirect_to'] ) ? wp_validate_redirect( wp_unslash( $_POST['redirect_to'] ), asso_core_url_espace() ) : asso_core_url_espace();
	$erreur = static function ( $code ) use ( $retour ) {
		wp_safe_redirect( add_query_arg( array( 'connexion' => $code, 'redirect_to' => rawurlencode( $retour ) ), asso_core_url_connexion() ) );
		exit;
	};

	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['asso_connexion_nonce'] ) ), 'asso_connexion' ) ) {
		$erreur( 'expire' );
	}

	$identifiant  = isset( $_POST['log'] ) ? sanitize_user( wp_unslash( $_POST['log'] ) ) : '';
	$mot_de_passe = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- mot de passe : jamais modifié.
	if ( '' === $identifiant || '' === $mot_de_passe ) {
		$erreur( 'vide' );
	}

	$utilisateur = wp_signon(
		array(
			'user_login'    => $identifiant,
			'user_password' => $mot_de_passe,
			'remember'      => ! empty( $_POST['rememberme'] ),
		),
		is_ssl()
	);
	if ( is_wp_error( $utilisateur ) ) {
		$erreur( 'asso_bloque' === $utilisateur->get_error_code() ? 'bloque' : 'echec' );
	}

	// Les membres vont dans leur espace, même si l'adresse demandée était l'administration.
	if ( ! user_can( $utilisateur, 'edit_posts' ) && str_starts_with( $retour, admin_url() ) ) {
		$retour = asso_core_url_espace();
	}
	wp_safe_redirect( $retour );
	exit;
}
add_action( 'template_redirect', 'asso_core_traiter_connexion' );

/**
 * Message d'information de la page Connexion, selon le paramètre « connexion » de l'adresse.
 *
 * @return array{0:string,1:string}|null Type (erreur|info) et texte, ou null.
 */
function asso_core_message_connexion() {
	$code     = isset( $_GET['connexion'] ) ? sanitize_key( wp_unslash( $_GET['connexion'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple affichage.
	$messages = array(
		'echec'      => array( 'erreur', __( 'Identifiant ou mot de passe incorrect. Vérifiez votre saisie, ou utilisez « Mot de passe oublié ».', 'asso-core' ) ),
		'vide'       => array( 'erreur', __( 'Saisissez votre identifiant (ou votre e-mail) et votre mot de passe.', 'asso-core' ) ),
		'bloque'     => array( 'erreur', __( 'Trop d’essais de connexion. Pour votre sécurité, réessayez dans 15 minutes.', 'asso-core' ) ),
		'expire'     => array( 'erreur', __( 'La page a expiré. Merci de réessayer.', 'asso-core' ) ),
		'deconnecte' => array( 'info', __( 'Vous êtes déconnecté. À bientôt !', 'asso-core' ) ),
	);
	return isset( $messages[ $code ] ) ? $messages[ $code ] : null;
}

/**
 * Formulaire de connexion : [asso_connexion].
 *
 * @return string
 */
function asso_core_formulaire_connexion() {
	$html = '';

	if ( is_user_logged_in() ) {
		$utilisateur = wp_get_current_user();
		$html       .= '<div class="asso-connexion asso-connexion--connecte">';
		/* translators: %s: nom affiché du membre. */
		$html .= '<p>' . esc_html( sprintf( __( 'Vous êtes connecté en tant que %s.', 'asso-core' ), $utilisateur->display_name ) ) . '</p>';
		$html .= '<p class="asso-connexion__actions"><a class="wp-element-button" href="' . esc_url( asso_core_url_espace() ) . '">' . esc_html__( 'Aller à mon espace', 'asso-core' ) . '</a> ';
		$html .= '<a href="' . esc_url( wp_logout_url( add_query_arg( 'connexion', 'deconnecte', asso_core_url_connexion() ) ) ) . '">' . esc_html__( 'Se déconnecter', 'asso-core' ) . '</a></p>';
		return $html . '</div>';
	}

	$retour  = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( wp_unslash( $_GET['redirect_to'] ), asso_core_url_espace() ) : asso_core_url_espace(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- validé par wp_validate_redirect().
	$message = asso_core_message_connexion();

	$html .= '<form class="asso-connexion" method="post" action="' . esc_url( asso_core_url_connexion() ) . '">';
	if ( $message ) {
		$html .= sprintf(
			'<p class="asso-connexion__message asso-connexion__message--%1$s" role="%2$s">%3$s</p>',
			esc_attr( $message[0] ),
			'erreur' === $message[0] ? 'alert' : 'status',
			esc_html( $message[1] )
		);
	}
	$html .= '<p class="asso-champ"><label for="asso-identifiant">' . esc_html__( 'Identifiant ou adresse e-mail', 'asso-core' ) . '</label>';
	$html .= '<input type="text" id="asso-identifiant" name="log" autocomplete="username" autocapitalize="none" spellcheck="false" required></p>';
	$html .= '<p class="asso-champ"><label for="asso-mot-de-passe">' . esc_html__( 'Mot de passe', 'asso-core' ) . '</label>';
	$html .= '<input type="password" id="asso-mot-de-passe" name="pwd" autocomplete="current-password" required></p>';
	$html .= '<p class="asso-case"><input type="checkbox" id="asso-souvenir" name="rememberme" value="1"> <label for="asso-souvenir">' . esc_html__( 'Rester connecté sur cet appareil', 'asso-core' ) . '</label></p>';
	$html .= wp_nonce_field( 'asso_connexion', 'asso_connexion_nonce', false, false );
	$html .= '<input type="hidden" name="redirect_to" value="' . esc_attr( $retour ) . '">';
	$html .= '<p class="asso-connexion__actions"><button type="submit" class="wp-element-button">' . esc_html__( 'Se connecter', 'asso-core' ) . '</button> ';
	$html .= '<a href="' . esc_url( wp_lostpassword_url( asso_core_url_connexion() ) ) . '">' . esc_html__( 'Mot de passe oublié ?', 'asso-core' ) . '</a></p>';
	$html .= '</form>';

	return $html;
}
add_shortcode( 'asso_connexion', 'asso_core_formulaire_connexion' );

/* ---------- Limitation des essais de connexion ---------- */

/**
 * Clé de suivi des essais pour l'appareil courant (adresse IP hachée, jamais conservée en clair,
 * effacée automatiquement après le délai de blocage).
 *
 * @return string
 */
function asso_core_cle_essais() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'inconnue';
	return 'asso_essais_' . substr( wp_hash( $ip ), 0, 24 );
}

/**
 * Vrai si l'appareil courant est bloqué.
 *
 * @return bool
 */
function asso_core_connexion_bloquee() {
	$suivi = get_transient( asso_core_cle_essais() );
	return is_array( $suivi ) && ! empty( $suivi['jusqua'] ) && $suivi['jusqua'] > time();
}

/**
 * Refuse toute connexion pendant un blocage (y compris par wp-login.php), même avec le bon mot de passe.
 *
 * @param WP_User|WP_Error|null $utilisateur Résultat de l'authentification.
 * @return WP_User|WP_Error|null
 */
function asso_core_refuser_si_bloque( $utilisateur ) {
	if ( asso_core_connexion_bloquee() ) {
		return new WP_Error( 'asso_bloque', __( 'Trop d’essais de connexion. Pour votre sécurité, réessayez dans 15 minutes.', 'asso-core' ) );
	}
	return $utilisateur;
}
add_filter( 'authenticate', 'asso_core_refuser_si_bloque', 99 );

/**
 * Compte un essai raté ; bloque l'appareil après ASSO_CORE_ESSAIS_MAX échecs.
 */
function asso_core_compter_echec() {
	if ( asso_core_connexion_bloquee() ) {
		return;
	}
	$cle   = asso_core_cle_essais();
	$suivi = get_transient( $cle );
	$suivi = is_array( $suivi ) ? $suivi : array( 'essais' => 0 );
	++$suivi['essais'];
	if ( $suivi['essais'] >= ASSO_CORE_ESSAIS_MAX ) {
		$suivi['jusqua'] = time() + ASSO_CORE_ESSAIS_BLOCAGE;
	}
	set_transient( $cle, $suivi, ASSO_CORE_ESSAIS_BLOCAGE );
}
add_action( 'wp_login_failed', 'asso_core_compter_echec' );

/**
 * Remet le compteur à zéro après une connexion réussie.
 */
function asso_core_effacer_echecs() {
	delete_transient( asso_core_cle_essais() );
}
add_action( 'wp_login', 'asso_core_effacer_echecs' );

/* ---------- Redirections et barre d'administration ---------- */

/**
 * Les membres (sans droit de rédaction) n'entrent pas dans l'administration : retour à leur espace.
 */
function asso_core_tenir_membres_hors_admin() {
	if ( wp_doing_ajax() || ( defined( 'DOING_CRON' ) && DOING_CRON ) || current_user_can( 'edit_posts' ) ) {
		return;
	}
	$script = isset( $_SERVER['SCRIPT_NAME'] ) ? basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) ) : '';
	if ( 'admin-post.php' === $script ) {
		return;
	}
	wp_safe_redirect( asso_core_url_espace() );
	exit;
}
add_action( 'admin_init', 'asso_core_tenir_membres_hors_admin' );

/**
 * Pas de barre d'administration noire pour les membres.
 *
 * @param bool $afficher Afficher la barre.
 * @return bool
 */
function asso_core_barre_admin( $afficher ) {
	return current_user_can( 'edit_posts' ) ? $afficher : false;
}
add_filter( 'show_admin_bar', 'asso_core_barre_admin' );

/**
 * Après une connexion par wp-login.php (ex. après un nouveau mot de passe), un membre va dans son espace.
 *
 * @param string           $adresse     Adresse demandée.
 * @param string           $demandee    Adresse demandée d'origine.
 * @param WP_User|WP_Error $utilisateur Utilisateur.
 * @return string
 */
function asso_core_redirection_apres_connexion( $adresse, $demandee, $utilisateur ) {
	if ( $utilisateur instanceof WP_User && ! user_can( $utilisateur, 'edit_posts' ) && ( '' === $demandee || str_starts_with( $adresse, admin_url() ) ) ) {
		return asso_core_url_espace();
	}
	return $adresse;
}
add_filter( 'login_redirect', 'asso_core_redirection_apres_connexion', 10, 3 );

/**
 * Après une déconnexion, retour à la page Connexion avec un message.
 *
 * @param string $adresse  Adresse prévue.
 * @param string $demandee Adresse demandée.
 * @return string
 */
function asso_core_redirection_apres_deconnexion( $adresse, $demandee ) {
	return '' === $demandee ? add_query_arg( 'connexion', 'deconnecte', asso_core_url_connexion() ) : $adresse;
}
add_filter( 'logout_redirect', 'asso_core_redirection_apres_deconnexion', 10, 2 );

/* ---------- Mot de passe choisi par le membre (écran WordPress « Nouveau mot de passe ») ---------- */

/** Longueur minimale d'un mot de passe choisi par un membre (aussi exigée dans « Mon profil »). */
const ASSO_CORE_MDP_MIN = 10;

/**
 * Refuse un nouveau mot de passe trop court, même si la case « mot de passe faible » est forcée.
 *
 * @param WP_Error         $erreurs     Erreurs de validation.
 * @param WP_User|WP_Error $utilisateur Compte concerné.
 */
function asso_core_valider_nouveau_mdp( $erreurs, $utilisateur ) {
	$mdp = isset( $_POST['pass1'] ) ? trim( (string) wp_unslash( $_POST['pass1'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- clé de réinitialisation vérifiée par WordPress ; mot de passe jamais modifié.
	if ( '' !== $mdp && mb_strlen( $mdp ) < ASSO_CORE_MDP_MIN ) {
		/* translators: %d: nombre minimal de caractères. */
		$erreurs->add( 'asso_mdp_court', sprintf( __( 'Choisissez un mot de passe d’au moins %d caractères.', 'asso-core' ), ASSO_CORE_MDP_MIN ) );
	}
}
add_action( 'validate_password_reset', 'asso_core_valider_nouveau_mdp', 10, 2 );

/**
 * Consigne affichée au-dessus du bouton de l'écran « Nouveau mot de passe ».
 */
function asso_core_consigne_nouveau_mdp() {
	/* translators: %d: nombre minimal de caractères. */
	echo '<p class="description asso-consigne-mdp">' . esc_html( sprintf( __( 'Au moins %d caractères. Astuce : une petite phrase facile à retenir, par exemple « Mon-cafe-du-samedi-26 ».', 'asso-core' ), ASSO_CORE_MDP_MIN ) ) . '</p>';
}
add_action( 'resetpass_form', 'asso_core_consigne_nouveau_mdp' );

/**
 * Retire la case « Confirmer l'utilisation d'un mot de passe faible » : un mot de passe jugé
 * faible ne peut plus être validé sur cet écran.
 */
function asso_core_masquer_case_mdp_faible() {
	wp_add_inline_style( 'login', '.login .pw-weak{display:none!important}.login .asso-consigne-mdp{margin:0 0 1rem}' );
}
add_action( 'login_enqueue_scripts', 'asso_core_masquer_case_mdp_faible', 20 );

/* ---------- E-mail de bienvenue ---------- */

/**
 * E-mail envoyé au membre quand l'administrateur crée son compte : ton chaleureux, en français.
 * Le lien pour choisir le mot de passe est repris du message de WordPress.
 *
 * @param array   $courriel    Destinataire, sujet, message, en-têtes.
 * @param WP_User $utilisateur Nouveau compte.
 * @return array
 */
function asso_core_courriel_bienvenue( $courriel, $utilisateur ) {
	if ( ! preg_match( '#https?://\S+action=rp\S+#', $courriel['message'], $lien ) ) {
		return $courriel;
	}
	$courriel['subject'] = __( 'Bienvenue dans l’espace membres de La Famille Wê', 'asso-core' );
	$courriel['message'] = implode(
		"\n\n",
		array(
			/* translators: %s: prénom ou nom affiché du membre. */
			sprintf( __( 'Bonjour %s,', 'asso-core' ), $utilisateur->first_name ? $utilisateur->first_name : $utilisateur->display_name ),
			__( 'Votre compte dans l’espace membres de La Famille Wê de Dallas & Oklahoma vient d’être créé.', 'asso-core' ),
			/* translators: %s: identifiant de connexion. */
			sprintf( __( 'Votre identifiant : %s', 'asso-core' ), $utilisateur->user_login ),
			__( 'Pour choisir votre mot de passe, ouvrez ce lien :', 'asso-core' ) . "\n" . $lien[0],
			/* translators: %s: adresse de la page Connexion. */
			sprintf( __( 'Ensuite, connectez-vous à tout moment depuis %s', 'asso-core' ), asso_core_url_connexion() ),
			__( 'Ensemble, allons plus loin !', 'asso-core' ) . "\n" . __( 'Le bureau de La Famille Wê', 'asso-core' ),
		)
	);
	return $courriel;
}
add_filter( 'wp_new_user_notification_email', 'asso_core_courriel_bienvenue', 10, 2 );
