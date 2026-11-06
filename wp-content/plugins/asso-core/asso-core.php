<?php
/**
 * Plugin Name: Asso Core
 * Description: Contenus de La Famille Wê de Dallas & Oklahoma : activités, albums photo et sections.
 * Version: 0.1.0
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Author: La Famille Wê de Dallas & Oklahoma
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: asso-core
 * Domain Path: /languages
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

define( 'ASSO_CORE_VERSION', '0.1.0' );
define( 'ASSO_CORE_DOSSIER', plugin_dir_path( __FILE__ ) );
define( 'ASSO_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once ASSO_CORE_DOSSIER . 'includes/contenus.php';
require_once ASSO_CORE_DOSSIER . 'includes/metadonnees.php';
require_once ASSO_CORE_DOSSIER . 'includes/dates.php';
require_once ASSO_CORE_DOSSIER . 'includes/requetes.php';
require_once ASSO_CORE_DOSSIER . 'includes/liaisons.php';
require_once ASSO_CORE_DOSSIER . 'includes/editeur.php';
require_once ASSO_CORE_DOSSIER . 'includes/referencement.php';
require_once ASSO_CORE_DOSSIER . 'includes/zelle.php';
require_once ASSO_CORE_DOSSIER . 'includes/formulaires.php';

/**
 * Charge les traductions de l'extension (dossier languages/), pour une future version anglaise.
 */
function asso_core_charger_traductions() {
	load_plugin_textdomain( 'asso-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'asso_core_charger_traductions' );

/**
 * Activation : enregistre les contenus, crée les sections, régénère les adresses.
 */
function asso_core_activation() {
	asso_core_enregistrer_contenus();
	asso_core_creer_sections();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'asso_core_activation' );

/**
 * Désactivation : régénère les adresses. Les contenus restent en base.
 */
function asso_core_desactivation() {
	unregister_post_type( 'asso_event' );
	unregister_post_type( 'asso_album' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'asso_core_desactivation' );
