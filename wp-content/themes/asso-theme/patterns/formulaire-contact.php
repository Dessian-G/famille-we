<?php
/**
 * Title: Formulaire de contact
 * Slug: asso-theme/formulaire-contact
 * Categories: asso-theme, contact
 * Description: Formulaire Fluent Forms de contact dans une carte blanche (identifiant lu dans l'option asso_formulaires).
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_ids = (array) get_option( 'asso_formulaires', array() );
$asso_id  = isset( $asso_ids['contact'] ) ? (int) $asso_ids['contact'] : 0;
?>
<!-- wp:group {"className":"asso-formulaire","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"}}},"backgroundColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group asso-formulaire has-blanc-background-color has-background" style="border-radius:24px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem">
<?php if ( $asso_id ) : ?>
<!-- wp:shortcode -->
[fluentform id="<?php echo (int) $asso_id; ?>"]
<!-- /wp:shortcode -->
<?php else : ?>
<!-- wp:paragraph -->
<p><?php echo esc_html__( '[Formulaire de contact à créer : lancer scripts/creer-formulaires.php]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
</div>
<!-- /wp:group -->
