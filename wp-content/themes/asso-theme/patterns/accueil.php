<?php
/**
 * Title: Page d'accueil
 * Slug: asso-theme/accueil
 * Categories: asso-theme
 * Post Types: page
 * Block Types: core/post-content
 * Description: Accueil (direction D) : bandeau, bande défilante, chiffres, piliers, activités, sections, souvenirs, actualités, appel final.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;

/*
 * Icône décorative au trait (24 × 24), masquée aux lecteurs d'écran.
 */
$asso_icone = static function ( $chemins, $couleur = 'currentColor' ) {
	return '<svg aria-hidden="true" focusable="false" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr( $couleur ) . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $chemins . '</svg>';
};

$asso_section = '{"align":"full","className":"%1$s","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"75rem"}}';
$asso_section_html = '<div class="wp-block-group alignfull %1$s" style="margin-top:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">';

$asso_carte_blanche = '{"style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"}}},"backgroundColor":"blanc","layout":{"type":"default"}}';
$asso_carte_blanche_html = '<div class="wp-block-group has-blanc-background-color has-background" style="border-radius:24px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem">';

// Photos de la médiathèque (repères « À FOURNIR » si elles manquent).
$asso_photo_arche = asso_theme_photo( 'famille-we-souvenir-01', 'asso-collage__arche', 'medium_large' );
$asso_photo_cadre = asso_theme_photo( 'famille-we-souvenir-07', 'asso-collage__cadre', 'medium_large' );
$asso_souvenirs   = asso_theme_souvenirs( 6 );

// Gabarits et icônes : balisage de blocs fixe, sans donnée externe.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"align":"full","className":"asso-bandeau","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"75rem"}} -->
<div class="wp-block-group alignfull asso-bandeau" style="margin-top:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:heading {"level":1,"className":"asso-surtitre","style":{"typography":{"fontWeight":"700","letterSpacing":"0.04em"}},"textColor":"bleu","fontFamily":"figtree","fontSize":"small"} -->
<h1 class="wp-block-heading asso-surtitre has-bleu-color has-text-color has-figtree-font-family has-small-font-size" style="font-weight:700;letter-spacing:0.04em"><?php echo esc_html__( 'La Famille Wê de Dallas & Oklahoma', 'asso-theme' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"asso-titre-revele","style":{"typography":{"fontWeight":"800","letterSpacing":"-0.04em","lineHeight":"0.92"}},"textColor":"encre","fontFamily":"bricolage","fontSize":"xxx-large"} -->
<p class="asso-titre-revele has-encre-color has-text-color has-bricolage-font-family has-xxx-large-font-size" style="font-weight:800;letter-spacing:-0.04em;line-height:0.92"><?php echo esc_html__( 'Ensemble', 'asso-theme' ); ?><br><?php echo esc_html__( 'on va', 'asso-theme' ); ?><br><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-vert-color"><?php echo esc_html__( 'plus loin', 'asso-theme' ); ?></mark><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-orange-profond-color">.</mark></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"asso-separateur","style":{"spacing":{"padding":{"top":"1.4rem"},"blockGap":"1.4rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group asso-separateur" style="padding-top:1.4rem"><!-- wp:paragraph -->
<p><?php echo esc_html__( 'L’association de la diaspora ivoirienne du Texas et de l’Oklahoma&nbsp;: deux sections, une seule famille, unie par l’entraide et la culture. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"1.25rem"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/adhesion/' ) ); ?>"><?php echo esc_html__( 'Devenir membre', 'asso-theme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/activites/' ) ); ?>"><?php echo esc_html__( 'Nos activités', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"className":"asso-collage","layout":{"type":"default"}} -->
<div class="wp-block-group asso-collage"><!-- wp:group {"className":"asso-collage__motif asso-motif-pagne","layout":{"type":"default"}} -->
<div class="wp-block-group asso-collage__motif asso-motif-pagne"></div>
<!-- /wp:group -->

<?php if ( $asso_photo_arche ) : ?>
<?php echo $asso_photo_arche; ?>
<?php else : ?>
<!-- wp:group {"className":"asso-collage__arche","backgroundColor":"vert","textColor":"blanc","fontSize":"small","layout":{"type":"default"}} -->
<div class="wp-block-group asso-collage__arche has-blanc-color has-vert-background-color has-text-color has-background has-small-font-size"><!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: photo d’une fête de l’association]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endif; ?>

<?php if ( $asso_photo_cadre ) : ?>
<?php echo $asso_photo_cadre; ?>
<?php else : ?>
<!-- wp:group {"className":"asso-collage__cadre","backgroundColor":"blanc","textColor":"encre","fontSize":"small","layout":{"type":"default"}} -->
<div class="wp-block-group asso-collage__cadre has-encre-color has-blanc-background-color has-text-color has-background has-small-font-size"><!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: photo des deux sections réunies]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endif; ?>

<!-- wp:html -->
<div class="asso-collage__tampon"><svg viewBox="0 0 160 160" role="img" aria-label="<?php echo esc_attr__( 'La Famille Wê, Dallas, Oklahoma', 'asso-theme' ); ?>"><defs><path id="asso-cercle" d="M80,80 m-58,0 a58,58 0 1,1 116,0 a58,58 0 1,1 -116,0"></path></defs><circle cx="80" cy="80" r="78" fill="#F77F00"></circle><text fill="#1F2A24" style="font-family:'Bricolage Grotesque',sans-serif;font-weight:800;font-size:14px;letter-spacing:2px"><textPath href="#asso-cercle"><?php echo esc_html__( 'LA FAMILLE WÊ • DALLAS • OKLAHOMA •', 'asso-theme' ); ?></textPath></text><polygon points="80,62 98,80 80,98 62,80" fill="#00754A"></polygon></svg></div>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"asso-collage__pastille","style":{"typography":{"fontWeight":"700"}},"backgroundColor":"encre","textColor":"creme","fontFamily":"bricolage","fontSize":"small"} -->
<p class="asso-collage__pastille has-creme-color has-encre-background-color has-text-color has-background has-bricolage-font-family has-small-font-size" style="font-weight:700"><?php echo esc_html__( '2 sections · 1 famille', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"asso-defile","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"1.4rem","bottom":"1.4rem","left":"0","right":"0"}}},"backgroundColor":"vert","textColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull asso-defile has-blanc-color has-vert-background-color has-text-color has-background" style="margin-top:0;padding-top:1.4rem;padding-right:0;padding-bottom:1.4rem;padding-left:0"><!-- wp:group {"className":"asso-defile__piste","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group asso-defile__piste"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"-0.01em"}},"fontFamily":"bricolage","fontSize":"x-large"} -->
<p class="has-bricolage-font-family has-x-large-font-size" style="font-weight:700;letter-spacing:-0.01em"><?php echo esc_html__( 'Solidarité ✦ Culture ✦ Famille ✦ Entraide ✦ Dallas ✦ Oklahoma ✦', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, 'asso-revele' ); ?> -->
<?php printf( $asso_section_html, 'asso-revele' ); ?><!-- wp:group {"className":"asso-chiffres","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group asso-chiffres"><!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"blockGap":"0.5rem"}}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"asso-compteur","style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"textColor":"bleu","fontFamily":"bricolage","fontSize":"xx-large"} -->
<p class="asso-compteur has-bleu-color has-text-color has-bricolage-font-family has-xx-large-font-size" style="font-weight:800;line-height:1">—</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase"><?php echo esc_html__( 'Membres [À FOURNIR]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"blockGap":"0.5rem"}}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"asso-compteur","style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"textColor":"bleu","fontFamily":"bricolage","fontSize":"xx-large"} -->
<p class="asso-compteur has-bleu-color has-text-color has-bricolage-font-family has-xx-large-font-size" style="font-weight:800;line-height:1">2</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase"><?php echo esc_html__( 'Sections&nbsp;: Dallas & Oklahoma', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"blockGap":"0.5rem"}}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"asso-compteur","style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"textColor":"bleu","fontFamily":"bricolage","fontSize":"xx-large"} -->
<p class="asso-compteur has-bleu-color has-text-color has-bricolage-font-family has-xx-large-font-size" style="font-weight:800;line-height:1">—</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"600","letterSpacing":"0.06em","textTransform":"uppercase"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-weight:600;letter-spacing:0.06em;text-transform:uppercase"><?php echo esc_html__( 'Activités par an [À FOURNIR]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( '[À FOURNIR&nbsp;: nombre de membres et d’activités par an. Bloc à masquer avant la mise en ligne si les chiffres manquent.]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, '' ); ?> -->
<?php printf( $asso_section_html, '' ); ?><!-- wp:group {"className":"asso-revele","style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group asso-revele"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.08em;text-transform:uppercase"><?php echo esc_html__( 'Nos piliers', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html__( 'Ce que nous faisons ensemble', 'asso-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Quatre piliers qui font de l’association une vraie famille, à Dallas comme en Oklahoma. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-cascade","style":{"spacing":{"blockGap":"1.25rem"}},"layout":{"type":"grid","columnCount":4,"minimumColumnWidth":"15rem"}} -->
<div class="wp-block-group asso-cascade"><!-- wp:group {"className":"asso-carte","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"},"blockGap":"0.9rem"},"dimensions":{"minHeight":"260px"}},"backgroundColor":"vert","textColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte has-blanc-color has-vert-background-color has-text-color has-background" style="border-radius:24px;min-height:260px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:group {"className":"asso-carte__tete","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group asso-carte__tete"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"fontFamily":"bricolage","fontSize":"x-large"} -->
<p class="has-bricolage-font-family has-x-large-font-size" style="font-weight:800;line-height:1">01</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo $asso_icone( '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"></path>' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"textColor":"blanc"} -->
<h3 class="wp-block-heading has-blanc-color has-text-color"><?php echo esc_html__( 'Solidarité', 'asso-theme' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Nous nous serrons les coudes dans les moments heureux comme dans les épreuves. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-carte","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"},"blockGap":"0.9rem"},"dimensions":{"minHeight":"260px"}},"backgroundColor":"orange","textColor":"encre","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte has-encre-color has-orange-background-color has-text-color has-background" style="border-radius:24px;min-height:260px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:group {"className":"asso-carte__tete","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group asso-carte__tete"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"fontFamily":"bricolage","fontSize":"x-large"} -->
<p class="has-bricolage-font-family has-x-large-font-size" style="font-weight:800;line-height:1">02</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo $asso_icone( '<ellipse cx="12" cy="6" rx="6" ry="2.5"></ellipse><path d="M6 6v9c0 1.4 2.7 2.5 6 2.5s6-1.1 6-2.5V6"></path><path d="M8 20l2-3M16 20l-2-3"></path>' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"textColor":"encre"} -->
<h3 class="wp-block-heading has-encre-color has-text-color"><?php echo esc_html__( 'Culture', 'asso-theme' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Nous faisons vivre et transmettons la culture ivoirienne, au Texas et en Oklahoma. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-carte","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"},"blockGap":"0.9rem"},"dimensions":{"minHeight":"260px"}},"backgroundColor":"blanc","textColor":"encre","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte has-encre-color has-blanc-background-color has-text-color has-background" style="border-radius:24px;min-height:260px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:group {"className":"asso-carte__tete","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group asso-carte__tete"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"textColor":"orange-profond","fontFamily":"bricolage","fontSize":"x-large"} -->
<p class="has-orange-profond-color has-text-color has-bricolage-font-family has-x-large-font-size" style="font-weight:800;line-height:1">03</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo $asso_icone( '<path d="M4 5h11v8H8l-4 3z"></path><path d="M15 9h5v8l-3-2h-6v-2"></path>', '#00754A' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"textColor":"encre"} -->
<h3 class="wp-block-heading has-encre-color has-text-color"><?php echo esc_html__( 'Rencontres', 'asso-theme' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Réunions, fêtes et sorties&nbsp;: des occasions régulières de se retrouver. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-carte","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"},"blockGap":"0.9rem"},"dimensions":{"minHeight":"260px"}},"backgroundColor":"encre","textColor":"creme","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte has-creme-color has-encre-background-color has-text-color has-background" style="border-radius:24px;min-height:260px;padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:group {"className":"asso-carte__tete","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group asso-carte__tete"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"800","lineHeight":"1"}},"fontFamily":"bricolage","fontSize":"x-large"} -->
<p class="has-bricolage-font-family has-x-large-font-size" style="font-weight:800;line-height:1">04</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo $asso_icone( '<path d="M3 11l9-7 9 7"></path><path d="M5 10v10h14V10"></path><path d="M10 20v-5h4v5"></path>' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":3,"textColor":"creme"} -->
<h3 class="wp-block-heading has-creme-color has-text-color"><?php echo esc_html__( 'Familles et jeunesse', 'asso-theme' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Des activités pensées pour les enfants, les jeunes et leurs parents. [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, 'asso-revele' ); ?> -->
<?php printf( $asso_section_html, 'asso-revele' ); ?><!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.08em;text-transform:uppercase"><?php echo esc_html__( 'Agenda', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html__( 'Prochaines activités', 'asso-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/activites/' ) ); ?>"><?php echo esc_html__( 'Toutes les activités', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":3,"query":{"perPage":3,"pages":0,"offset":0,"postType":"asso_event","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"assoPeriode":"a-venir"}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"asso-cascade","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"18rem"}} -->
<?php echo asso_theme_carte_activite(); ?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:group <?php echo $asso_carte_blanche; ?> -->
<?php echo $asso_carte_blanche_html; ?><!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php echo esc_html__( 'De nouvelles dates arrivent bientôt. Revenez nous voir très vite&nbsp;! [À VALIDER]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, '' ); ?> -->
<?php printf( $asso_section_html, '' ); ?><!-- wp:group {"className":"asso-revele","style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group asso-revele"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.08em;text-transform:uppercase"><?php echo esc_html__( 'Dallas & Oklahoma', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html__( 'Nos deux sections', 'asso-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-cascade","style":{"spacing":{"blockGap":"1.25rem"}},"layout":{"type":"grid","columnCount":2,"minimumColumnWidth":"18rem"}} -->
<div class="wp-block-group asso-cascade">
<?php foreach ( array( __( 'Dallas (Texas)', 'asso-theme' ), __( 'Oklahoma', 'asso-theme' ) ) as $asso_nom_section ) : ?>
<!-- wp:group {"className":"asso-carte","style":{"border":{"radius":"24px"},"spacing":{"padding":{"top":"1rem","bottom":"1.75rem","left":"1rem","right":"1rem"}}},"backgroundColor":"blanc","layout":{"type":"default"}} -->
<div class="wp-block-group asso-carte has-blanc-background-color has-background" style="border-radius:24px;padding-top:1rem;padding-right:1rem;padding-bottom:1.75rem;padding-left:1rem"><!-- wp:group {"style":{"border":{"radius":"16px"},"dimensions":{"minHeight":"200px"}},"backgroundColor":"gris-leger","fontSize":"small","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group has-gris-leger-background-color has-background has-small-font-size" style="border-radius:16px;min-height:200px"><!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: photo de la section]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"left":"0.75rem","right":"0.75rem"},"blockGap":"0.75rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-right:0.75rem;padding-left:0.75rem"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $asso_nom_section ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: courte présentation, ville et rythme des réunions]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/nos-sections/' ) ); ?>"><?php echo esc_html__( 'Découvrir la section', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, '' ); ?> -->
<?php printf( $asso_section_html, '' ); ?><!-- wp:group {"className":"asso-revele","style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group asso-revele"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.08em;text-transform:uppercase"><?php echo esc_html__( 'Galerie', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html__( 'Souvenirs', 'asso-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/galerie/' ) ); ?>"><?php echo esc_html__( 'Voir la galerie', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asso-mosaique asso-cascade","layout":{"type":"default"}} -->
<div class="wp-block-group asso-mosaique asso-cascade">
<?php if ( $asso_souvenirs ) : ?>
<?php echo $asso_souvenirs; ?>
<?php else : ?>
<?php for ( $asso_i = 0; $asso_i < 6; $asso_i++ ) : ?>
<!-- wp:group {"style":{"border":{"radius":"16px"}},"backgroundColor":"blanc","fontSize":"small","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group has-blanc-background-color has-background has-small-font-size" style="border-radius:16px"><!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: photo]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endfor; ?>
<?php endif; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group <?php printf( $asso_section, '' ); ?> -->
<?php printf( $asso_section_html, '' ); ?><!-- wp:group {"className":"asso-revele","style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group asso-revele"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"}},"textColor":"orange-profond","fontSize":"small"} -->
<p class="has-orange-profond-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.08em;text-transform:uppercase"><?php echo esc_html__( 'Le journal de la famille', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size"><?php echo esc_html__( 'Actualités', 'asso-theme' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"asso-lien-fleche"} -->
<p class="asso-lien-fleche"><a href="<?php echo esc_url( home_url( '/actualites/' ) ); ?>"><?php echo esc_html__( 'Toutes les actualités', 'asso-theme' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":2,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"asso-actus"} -->
<div class="wp-block-query asso-actus"><!-- wp:post-template {"className":"asso-cascade","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->

<!-- wp:post-date {"format":"l j F Y","fontSize":"small"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":20} /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:pattern {"slug":"asso-theme/aucun-resultat"} /-->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"asso-theme/appel-final"} /-->
