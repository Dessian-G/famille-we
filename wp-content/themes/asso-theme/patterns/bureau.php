<?php
/**
 * Title: Cartes du bureau
 * Slug: asso-theme/bureau
 * Categories: asso-theme, team
 * Description: Grille de cartes des membres du bureau (photo, nom, fonction, section).
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

$asso_fonctions = array(
	__( 'Présidence', 'asso-theme' ),
	__( 'Trésorerie', 'asso-theme' ),
	__( '[À FOURNIR&nbsp;: fonction]', 'asso-theme' ),
	__( '[À FOURNIR&nbsp;: fonction]', 'asso-theme' ),
);
?>
<!-- wp:group {"align":"wide","layout":{"type":"grid","minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $asso_fonctions as $asso_fonction ) : ?>
<!-- wp:group {"style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|10"}},"backgroundColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group has-blanc-background-color has-background" style="border-radius:24px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:group {"style":{"border":{"radius":"16px"},"dimensions":{"minHeight":"200px"}},"backgroundColor":"gris-leger","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group has-gris-leger-background-color has-background" style="border-radius:16px;min-height:200px"><!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( '[À FOURNIR&nbsp;: photo, avec accord]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size"><?php echo esc_html__( '[À FOURNIR&nbsp;: prénom et nom]', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong><?php echo esc_html( $asso_fonction ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Section&nbsp;: [À FOURNIR]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
