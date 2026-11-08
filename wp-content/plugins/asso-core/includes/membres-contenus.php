<?php
/**
 * Contenus réservés aux membres : annonces internes et documents à télécharger.
 *
 * - Annonces (asso_annonce) : jamais publiques, ni par adresse, ni par l'API REST
 *   (lecture réservée aux comptes qui ont « asso_espace_membres »).
 * - Documents (asso_document) : fichier rangé dans uploads/asso-prive/ sous un nom aléatoire,
 *   accès direct interdit, téléchargement seulement par ?asso_document=ID une fois connecté.
 * Gestion réservée à la capacité « asso_gerer_membres » (administrateurs).
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Capacités d'un type de contenu géré par le bureau : toutes ramenées à « asso_gerer_membres ».
 *
 * @return array
 */
function asso_core_capacites_bureau() {
	$capacites = array();
	foreach ( array( 'edit_posts', 'edit_others_posts', 'edit_private_posts', 'edit_published_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'create_posts' ) as $capacite ) {
		$capacites[ $capacite ] = 'asso_gerer_membres';
	}
	return $capacites;
}

/**
 * Enregistre les types de contenu Annonces et Documents.
 */
function asso_core_enregistrer_contenus_membres() {
	register_post_type(
		'asso_annonce',
		array(
			'labels'              => array(
				'name'               => __( 'Annonces membres', 'asso-core' ),
				'singular_name'      => __( 'Annonce membres', 'asso-core' ),
				'add_new'            => __( 'Ajouter', 'asso-core' ),
				'add_new_item'       => __( 'Ajouter une annonce pour les membres', 'asso-core' ),
				'edit_item'          => __( 'Modifier l’annonce', 'asso-core' ),
				'new_item'           => __( 'Nouvelle annonce', 'asso-core' ),
				'search_items'       => __( 'Rechercher une annonce', 'asso-core' ),
				'not_found'          => __( 'Aucune annonce.', 'asso-core' ),
				'not_found_in_trash' => __( 'Aucune annonce dans la corbeille.', 'asso-core' ),
				'all_items'          => __( 'Toutes les annonces', 'asso-core' ),
				'menu_name'          => __( 'Annonces membres', 'asso-core' ),
			),
			'description'           => __( 'Annonces visibles seulement dans l’espace membres.', 'asso-core' ),
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_in_rest'          => true,
			'rest_controller_class' => 'Asso_Core_REST_Reserve',
			'menu_icon'             => 'dashicons-megaphone',
			'menu_position'         => 7,
			'supports'              => array( 'title', 'editor', 'revisions' ),
			'capabilities'          => asso_core_capacites_bureau(),
			'map_meta_cap'          => true,
			'rewrite'               => false,
			'query_var'             => false,
		)
	);

	register_post_type(
		'asso_document',
		array(
			'labels'            => array(
				'name'               => __( 'Documents membres', 'asso-core' ),
				'singular_name'      => __( 'Document membres', 'asso-core' ),
				'add_new'            => __( 'Ajouter', 'asso-core' ),
				'add_new_item'       => __( 'Ajouter un document pour les membres', 'asso-core' ),
				'edit_item'          => __( 'Modifier le document', 'asso-core' ),
				'new_item'           => __( 'Nouveau document', 'asso-core' ),
				'search_items'       => __( 'Rechercher un document', 'asso-core' ),
				'not_found'          => __( 'Aucun document.', 'asso-core' ),
				'not_found_in_trash' => __( 'Aucun document dans la corbeille.', 'asso-core' ),
				'all_items'          => __( 'Tous les documents', 'asso-core' ),
				'menu_name'          => __( 'Documents membres', 'asso-core' ),
			),
			'description'       => __( 'Fichiers téléchargeables seulement depuis l’espace membres.', 'asso-core' ),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => false, // Éditeur classique : envoi de fichier, et rien d'exposé par l'API.
			'menu_icon'         => 'dashicons-media-document',
			'menu_position'     => 8,
			'supports'          => array( 'title', 'excerpt' ),
			'capabilities'      => asso_core_capacites_bureau(),
			'map_meta_cap'      => true,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'asso_core_enregistrer_contenus_membres' );

/**
 * Contrôleur REST des annonces : lecture réservée aux membres connectés
 * (par défaut, WordPress rend lisible par tous un contenu publié).
 */
function asso_core_charger_controleur_rest() {
	if ( class_exists( 'Asso_Core_REST_Reserve' ) ) {
		return;
	}
	/**
	 * Contrôleur REST réservé aux membres.
	 */
	class Asso_Core_REST_Reserve extends WP_REST_Posts_Controller {
		/**
		 * Liste : réservée aux membres.
		 *
		 * @param WP_REST_Request $request Requête.
		 * @return true|WP_Error
		 */
		public function get_items_permissions_check( $request ) {
			return current_user_can( 'asso_espace_membres' ) ? parent::get_items_permissions_check( $request ) : $this->refus();
		}

		/**
		 * Élément : réservé aux membres.
		 *
		 * @param WP_REST_Request $request Requête.
		 * @return true|WP_Error
		 */
		public function get_item_permissions_check( $request ) {
			return current_user_can( 'asso_espace_membres' ) ? parent::get_item_permissions_check( $request ) : $this->refus();
		}

		/**
		 * Erreur d'accès.
		 *
		 * @return WP_Error
		 */
		private function refus() {
			return new WP_Error( 'rest_forbidden', __( 'Contenu réservé aux membres.', 'asso-core' ), array( 'status' => rest_authorization_required_code() ) );
		}
	}
}
add_action( 'init', 'asso_core_charger_controleur_rest', 1 );

/* ---------- Documents : fichier privé ---------- */

/**
 * Dossier privé des documents (créé et protégé au besoin).
 *
 * @return string Chemin avec barre finale.
 */
function asso_core_dossier_prive() {
	$dossier = trailingslashit( wp_upload_dir( null, false )['basedir'] ) . 'asso-prive/';
	if ( ! is_dir( $dossier ) ) {
		wp_mkdir_p( $dossier );
	}
	if ( ! file_exists( $dossier . '.htaccess' ) ) {
		// Apache et LiteSpeed : accès direct interdit. Sur nginx, prévoir une règle « deny » équivalente.
		file_put_contents( $dossier . '.htaccess', "# Documents réservés aux membres : téléchargement par l'espace membres uniquement.\n<IfModule mod_authz_core.c>\n\tRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n\tDeny from all\n</IfModule>\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
	if ( ! file_exists( $dossier . 'index.php' ) ) {
		file_put_contents( $dossier . 'index.php', "<?php\n// Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
	return $dossier;
}

/**
 * Types de fichiers acceptés pour les documents.
 *
 * @return array Extension(s) => type MIME.
 */
function asso_core_types_documents() {
	return array(
		'pdf'      => 'application/pdf',
		'doc'      => 'application/msword',
		'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls'      => 'application/vnd.ms-excel',
		'xlsx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'ppt'      => 'application/vnd.ms-powerpoint',
		'pptx'     => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'odt'      => 'application/vnd.oasis.opendocument.text',
		'ods'      => 'application/vnd.oasis.opendocument.spreadsheet',
		'txt'      => 'text/plain',
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
	);
}

/**
 * Formulaire d'édition des documents : autorise l'envoi de fichier.
 *
 * @param WP_Post $post Contenu édité.
 */
function asso_core_formulaire_document_fichier( $post ) {
	if ( 'asso_document' === $post->post_type ) {
		echo ' enctype="multipart/form-data"';
	}
}
add_action( 'post_edit_form_tag', 'asso_core_formulaire_document_fichier' );

/**
 * Ajoute le cadre « Fichier » à l'écran d'un document.
 */
function asso_core_cadre_document() {
	add_meta_box( 'asso_document_fichier', __( 'Fichier', 'asso-core' ), 'asso_core_afficher_cadre_document', 'asso_document', 'normal', 'high' );
}
add_action( 'add_meta_boxes_asso_document', 'asso_core_cadre_document' );

/**
 * Contenu du cadre « Fichier ».
 *
 * @param WP_Post $post Document.
 */
function asso_core_afficher_cadre_document( $post ) {
	wp_nonce_field( 'asso_document_fichier', 'asso_document_nonce' );
	$nom = get_post_meta( $post->ID, 'asso_fichier_nom', true );
	if ( $nom ) {
		printf(
			'<p>%1$s <strong>%2$s</strong> (%3$s)</p>',
			esc_html__( 'Fichier actuel :', 'asso-core' ),
			esc_html( $nom ),
			esc_html( size_format( (int) get_post_meta( $post->ID, 'asso_fichier_taille', true ) ) )
		);
	}
	printf(
		'<p><label for="asso_fichier">%1$s</label><br><input type="file" id="asso_fichier" name="asso_fichier" accept="%2$s"></p><p class="description">%3$s</p>',
		esc_html( $nom ? __( 'Remplacer par un autre fichier :', 'asso-core' ) : __( 'Choisir le fichier :', 'asso-core' ) ),
		esc_attr( '.' . str_replace( '|', ',.', implode( ',.', array_keys( asso_core_types_documents() ) ) ) ),
		/* translators: %s: taille maximale d'envoi. */
		esc_html( sprintf( __( 'PDF, Word, Excel, PowerPoint, OpenDocument, texte ou image ; %s au maximum. Le fichier n’est jamais public : seuls les membres connectés peuvent le télécharger.', 'asso-core' ), size_format( wp_max_upload_size() ) ) )
	);
	echo '<p class="description">' . esc_html__( 'Le champ « Extrait » ci-dessous sert de courte description dans l’espace membres.', 'asso-core' ) . '</p>';
}

/**
 * Enregistre le fichier envoyé dans le dossier privé.
 *
 * @param int $post_id Document.
 */
function asso_core_enregistrer_fichier_document( $post_id ) {
	if ( ! isset( $_POST['asso_document_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['asso_document_nonce'] ) ), 'asso_document_fichier' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( empty( $_FILES['asso_fichier']['name'] ) ) {
		return;
	}
	$fichier = $_FILES['asso_fichier']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- vérifié ci-dessous.
	$erreur  = '';

	if ( UPLOAD_ERR_OK !== (int) $fichier['error'] || ! is_uploaded_file( $fichier['tmp_name'] ) ) {
		$erreur = __( 'Le fichier n’a pas pu être envoyé (trop gros ou envoi interrompu).', 'asso-core' );
	} else {
		$verif = wp_check_filetype_and_ext( $fichier['tmp_name'], sanitize_file_name( $fichier['name'] ), asso_core_types_documents() );
		if ( empty( $verif['ext'] ) || empty( $verif['type'] ) ) {
			$erreur = __( 'Ce type de fichier n’est pas accepté.', 'asso-core' );
		} else {
			$dossier = asso_core_dossier_prive();
			$nom     = wp_generate_password( 32, false ) . '.' . $verif['ext'];
			if ( ! move_uploaded_file( $fichier['tmp_name'], $dossier . $nom ) ) {
				$erreur = __( 'Le fichier n’a pas pu être enregistré sur le serveur.', 'asso-core' );
			} else {
				$ancien = get_post_meta( $post_id, 'asso_fichier', true );
				if ( $ancien && file_exists( $dossier . basename( $ancien ) ) ) {
					wp_delete_file( $dossier . basename( $ancien ) );
				}
				update_post_meta( $post_id, 'asso_fichier', $nom );
				update_post_meta( $post_id, 'asso_fichier_nom', sanitize_file_name( $fichier['name'] ) );
				update_post_meta( $post_id, 'asso_fichier_type', $verif['type'] );
				update_post_meta( $post_id, 'asso_fichier_taille', (int) filesize( $dossier . $nom ) );
			}
		}
	}
	if ( $erreur ) {
		set_transient( 'asso_document_erreur_' . get_current_user_id(), $erreur, MINUTE_IN_SECONDS );
	}
}
add_action( 'save_post_asso_document', 'asso_core_enregistrer_fichier_document' );

/**
 * Affiche l'erreur d'envoi éventuelle après l'enregistrement.
 */
function asso_core_avis_document() {
	$cle    = 'asso_document_erreur_' . get_current_user_id();
	$erreur = get_transient( $cle );
	if ( $erreur ) {
		delete_transient( $cle );
		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $erreur ) );
	}
}
add_action( 'admin_notices', 'asso_core_avis_document' );

/**
 * Supprime le fichier privé quand le document est supprimé définitivement.
 *
 * @param int $post_id Contenu supprimé.
 */
function asso_core_supprimer_fichier_document( $post_id ) {
	if ( 'asso_document' !== get_post_type( $post_id ) ) {
		return;
	}
	$nom = get_post_meta( $post_id, 'asso_fichier', true );
	if ( $nom && file_exists( asso_core_dossier_prive() . basename( $nom ) ) ) {
		wp_delete_file( asso_core_dossier_prive() . basename( $nom ) );
	}
}
add_action( 'before_delete_post', 'asso_core_supprimer_fichier_document' );

/**
 * Colonne « Fichier » dans la liste des documents.
 *
 * @param array $colonnes Colonnes.
 * @return array
 */
function asso_core_colonnes_documents( $colonnes ) {
	$date = isset( $colonnes['date'] ) ? $colonnes['date'] : null;
	unset( $colonnes['date'] );
	$colonnes['asso_fichier'] = __( 'Fichier', 'asso-core' );
	if ( $date ) {
		$colonnes['date'] = $date;
	}
	return $colonnes;
}
add_filter( 'manage_asso_document_posts_columns', 'asso_core_colonnes_documents' );

/**
 * Contenu de la colonne « Fichier ».
 *
 * @param string $colonne Colonne.
 * @param int    $post_id Document.
 */
function asso_core_colonne_document( $colonne, $post_id ) {
	if ( 'asso_fichier' !== $colonne ) {
		return;
	}
	$nom = get_post_meta( $post_id, 'asso_fichier_nom', true );
	echo $nom ? esc_html( $nom . ' (' . size_format( (int) get_post_meta( $post_id, 'asso_fichier_taille', true ) ) . ')' ) : '<em>' . esc_html__( 'Aucun fichier', 'asso-core' ) . '</em>';
}
add_action( 'manage_asso_document_posts_custom_column', 'asso_core_colonne_document', 10, 2 );

/**
 * Adresse de téléchargement d'un document.
 *
 * @param int $post_id Document.
 * @return string
 */
function asso_core_url_document( $post_id ) {
	return add_query_arg( 'asso_document', (int) $post_id, home_url( '/' ) );
}

/**
 * Sert le fichier d'un document, seulement à un membre connecté.
 */
function asso_core_telecharger_document() {
	if ( ! isset( $_GET['asso_document'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- lecture seule, droits vérifiés.
		return;
	}
	$post_id = absint( $_GET['asso_document'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	nocache_headers();

	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( asso_core_url_document( $post_id ) ), asso_core_url_connexion() ) );
		exit;
	}
	if ( ! current_user_can( 'asso_espace_membres' ) ) {
		wp_die( esc_html__( 'Ce document est réservé aux membres.', 'asso-core' ), '', array( 'response' => 403 ) );
	}

	$document = get_post( $post_id );
	$nom      = $document ? get_post_meta( $post_id, 'asso_fichier', true ) : '';
	$chemin   = $nom ? asso_core_dossier_prive() . basename( $nom ) : '';
	if ( ! $document || 'asso_document' !== $document->post_type || 'publish' !== $document->post_status || ! $chemin || ! is_file( $chemin ) ) {
		wp_die( esc_html__( 'Ce document n’existe pas ou n’est plus disponible.', 'asso-core' ), '', array( 'response' => 404 ) );
	}

	$type = get_post_meta( $post_id, 'asso_fichier_type', true );
	header( 'Content-Type: ' . ( $type ? $type : 'application/octet-stream' ) );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( get_post_meta( $post_id, 'asso_fichier_nom', true ) ) . '"' );
	header( 'Content-Length: ' . filesize( $chemin ) );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Robots-Tag: noindex' );
	readfile( $chemin ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
	exit;
}
add_action( 'template_redirect', 'asso_core_telecharger_document', 0 );
