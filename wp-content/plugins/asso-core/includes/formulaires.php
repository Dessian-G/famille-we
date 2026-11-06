<?php
/**
 * Formulaires (extension Fluent Forms) : réglages de confidentialité.
 *
 * @package asso-core
 */

defined( 'ABSPATH' ) || exit;

/*
 * Aucune adresse IP enregistrée avec les envois de formulaires (décision de Max du 06/11/2026) :
 * l'anti-spam (champ piège) n'en a pas besoin, et la page Confidentialité l'annonce.
 */
add_filter( 'fluentform/disable_ip_logging', '__return_true' );
