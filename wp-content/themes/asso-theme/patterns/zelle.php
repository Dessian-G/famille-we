<?php
/**
 * Title: Payer par Zelle
 * Slug: asso-theme/zelle
 * Categories: asso-theme
 * Description: Instructions de paiement Zelle (cotisation ou don). Le site ne traite aucun paiement.
 *
 * @package asso-theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"wide","className":"asso-zelle","style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"creme","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide asso-zelle has-creme-background-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html__( 'Payer par Zelle', 'asso-theme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Zelle permet d’envoyer de l’argent directement depuis l’application de votre banque. Le site ne traite aucun paiement.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li><?php echo esc_html__( 'Ouvrez l’application de votre banque et choisissez Zelle.', 'asso-theme' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo wp_kses_post( __( '<strong>Destinataire&nbsp;:</strong> [À FOURNIR&nbsp;: e-mail ou numéro de téléphone enregistré chez Zelle], au nom de [À FOURNIR&nbsp;: nom du titulaire tel qu’il s’affiche dans Zelle].', 'asso-theme' ) ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo wp_kses_post( __( '<strong>Montant&nbsp;:</strong> cotisation de [À FOURNIR]&nbsp;$&nbsp;US, ou le montant de votre choix pour un don.', 'asso-theme' ) ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo wp_kses_post( __( '<strong>Mémo à indiquer&nbsp;:</strong> «&nbsp;Cotisation – Prénom Nom&nbsp;» ou «&nbsp;Don – Prénom Nom&nbsp;».', 'asso-theme' ) ); ?></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( '[À FOURNIR&nbsp;: QR code Zelle, facultatif]', 'asso-theme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo wp_kses_post( __( '<strong>Important&nbsp;:</strong> vérifiez le nom du destinataire avant d’envoyer. Un paiement Zelle ne peut généralement pas être annulé.', 'asso-theme' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php echo esc_html__( 'Le trésorier vérifie les paiements reçus manuellement. N’indiquez jamais vos coordonnées bancaires sur ce site.', 'asso-theme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
