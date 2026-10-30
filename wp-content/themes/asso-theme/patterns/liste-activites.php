<?php
/**
 * Title: Liste des activités
 * Slug: asso-theme/liste-activites
 * Inserter: no
 * Description: Page Activités : filtre par section, activités à venir puis passées (extension asso-core).
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

// Balisage de blocs fixe produit par les fonctions du thème.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Rencontres, fêtes, sorties en famille&nbsp;: retrouvez ici toutes les activités des sections de Dallas et de l’Oklahoma. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<?php echo asso_theme_filtre_sections( '/activites/' ); ?>

<!-- wp:heading {"className":"asso-revele"} -->
<h2 class="wp-block-heading asso-revele"><?php echo esc_html__( 'Prochaines activités', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":11,"query":{"perPage":12,"pages":0,"offset":0,"postType":"asso_event","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"assoPeriode":"a-venir","assoFiltreSection":true}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"asso-cascade","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":"20rem"}} -->
<?php echo asso_theme_carte_activite(); ?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Aucune activité à venir pour le moment. De nouvelles dates arrivent bientôt.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

<!-- wp:heading {"className":"asso-revele"} -->
<h2 class="wp-block-heading asso-revele"><?php echo esc_html__( 'Activités passées', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":12,"query":{"perPage":12,"pages":0,"offset":0,"postType":"asso_event","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"assoPeriode":"passees","assoFiltreSection":true}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"asso-cascade","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":"20rem"}} -->
<?php echo asso_theme_carte_activite( true ); ?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Les activités passées apparaîtront ici, avec leurs albums photo.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
