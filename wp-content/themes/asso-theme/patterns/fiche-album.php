<?php
/**
 * Title: Fiche d'un album
 * Slug: asso-theme/fiche-album
 * Inserter: no
 * Description: Page d'un album : date et section, photos (agrandies au clic), lien vers l'activité liée.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

// Balisage de blocs fixe produit par les fonctions du thème.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"className":"asso-entete-page","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|30"},"margin":{"bottom":"var:preset|spacing|30"},"blockGap":"0.75rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group asso-entete-page" style="margin-bottom:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:paragraph {<?php echo asso_theme_liaison( 'album_resume' ); ?>,"style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase"><?php echo esc_html__( 'Album photo', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"level":1} /--></div>
<!-- /wp:group -->

<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline asso-si-lien","metadata":{"bindings":{"url":{"source":"asso-core/activite","args":{"cle":"activite_liee_url"}},"text":{"source":"asso-core/activite","args":{"cle":"activite_liee_titre"}}}}} -->
<div class="wp-block-button is-style-outline asso-si-lien"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Voir l’activité', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/galerie/' ) ); ?>"><?php echo esc_html__( 'Toute la galerie', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Une photo vous concerne et vous souhaitez la retirer&nbsp;? Écrivez-nous depuis la page Contact.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
