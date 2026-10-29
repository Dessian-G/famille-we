<?php
/**
 * Title: Appel final « Rejoignez la famille »
 * Slug: asso-theme/appel-final
 * Categories: asso-theme, call-to-action
 * Description: Bandeau orange avec motif pagne et les boutons « Adhérer » et « Faire un don ».
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"asso-appel-final","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"orange","textColor":"encre","layout":{"type":"constrained","contentSize":"75rem"}} -->
<div class="wp-block-group alignfull asso-appel-final has-encre-color has-orange-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:group {"className":"asso-revele","style":{"spacing":{"blockGap":"1.4rem"}},"layout":{"type":"constrained","contentSize":"40rem","justifyContent":"left"}} -->
<div class="wp-block-group asso-revele"><!-- wp:heading {"textColor":"encre","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-encre-color has-text-color has-xx-large-font-size"><?php echo esc_html__( 'Rejoignez la famille', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php echo esc_html__( 'Devenez membre ou soutenez nos actions&nbsp;: chaque geste compte. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"encre","textColor":"blanc"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-blanc-color has-encre-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/adhesion/' ) ); ?>"><?php echo esc_html__( 'Adhérer', 'asso-theme' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/faire-un-don/' ) ); ?>"><?php echo esc_html__( 'Faire un don', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
