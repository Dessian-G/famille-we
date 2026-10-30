<?php
/**
 * Title: Liste des albums
 * Slug: asso-theme/liste-albums
 * Inserter: no
 * Description: Page Galerie : filtre par section et albums photo (extension asso-core).
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

// Balisage de blocs fixe produit par les fonctions du thème.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Revivez nos activités en images, album par album. Cliquez sur une photo pour l’agrandir. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<?php echo asso_theme_filtre_sections( '/galerie/' ); ?>

<!-- wp:query {"queryId":21,"query":{"perPage":12,"pages":0,"offset":0,"postType":"asso_album","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"assoFiltreSection":true}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"asso-cascade","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":"16rem"}} -->
<!-- wp:group {"className":"asso-carte asso-carte-album","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1rem","bottom":"1.5rem","left":"1rem","right":"1rem"},"blockGap":"0.75rem"}},"backgroundColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte asso-carte-album has-blanc-background-color has-background" style="border-radius:24px;padding-top:1rem;padding-right:1rem;padding-bottom:1.5rem;padding-left:1rem"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->

<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->

<!-- wp:paragraph {<?php echo asso_theme_liaison( 'album_resume' ); ?>,"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Date · section', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Aucun album pour le moment. Les photos de nos activités arriveront bientôt.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Les photos sont publiées avec l’accord des personnes photographiées. Aucune photo d’enfant identifiable n’est publiée sans l’accord de ses parents.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
