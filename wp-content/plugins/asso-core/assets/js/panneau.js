/**
 * Panneaux « Détails de l'activité » et « Détails de l'album » de l'éditeur.
 * JavaScript sans étape de compilation : uniquement les modules fournis par WordPress (wp.*).
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var Panneau = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || wp.editPost.PluginDocumentSettingPanel;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var useSelect = wp.data.useSelect;
	var useEntityProp = wp.coreData.useEntityProp;
	var decodeEntities = wp.htmlEntities.decodeEntities;

	/* Métadonnées du contenu en cours d'édition, et fonction de mise à jour d'un champ. */
	function useMeta() {
		var type = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );
		var paire = useEntityProp( 'postType', type, 'meta' );
		var meta = paire[ 0 ] || {};
		var definir = paire[ 1 ];
		return {
			type: type,
			meta: meta,
			maj: function ( cle, valeur ) {
				var changement = {};
				changement[ cle ] = valeur;
				definir( Object.assign( {}, meta, changement ) );
			},
		};
	}

	function champ( donnees, cle, reglages ) {
		return el(
			TextControl,
			Object.assign(
				{
					value: donnees.meta[ cle ] || '',
					onChange: function ( valeur ) {
						donnees.maj( cle, valeur );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true,
				},
				reglages
			)
		);
	}

	function PanneauActivite() {
		var donnees = useMeta();
		if ( 'asso_event' !== donnees.type ) {
			return null;
		}
		return el(
			Panneau,
			{ name: 'asso-core-activite', title: __( 'Détails de l’activité', 'asso-core' ) },
			el(
				'div',
				{ style: { display: 'grid', gap: '16px' } },
				champ( donnees, 'asso_debut', {
					label: __( 'Début', 'asso-core' ),
					type: 'datetime-local',
					help: __( 'Heure du Centre (Dallas, Oklahoma).', 'asso-core' ),
				} ),
				champ( donnees, 'asso_fin', {
					label: __( 'Fin (facultative)', 'asso-core' ),
					type: 'datetime-local',
				} ),
				champ( donnees, 'asso_lieu', { label: __( 'Lieu', 'asso-core' ) } ),
				champ( donnees, 'asso_adresse', {
					label: __( 'Adresse', 'asso-core' ),
					help: __( 'Sert au bouton « Voir sur la carte ».', 'asso-core' ),
				} ),
				champ( donnees, 'asso_tarif', {
					label: __( 'Tarif en $ US', 'asso-core' ),
					type: 'number',
					min: 0,
					step: 0.01,
					help: __( '0 = gratuit. Vide = à préciser.', 'asso-core' ),
				} ),
				champ( donnees, 'asso_lien', {
					label: __( 'Lien d’inscription ou de paiement', 'asso-core' ),
					type: 'url',
					help: __( 'Facultatif. Le bouton n’apparaît que s’il y a un lien.', 'asso-core' ),
				} )
			)
		);
	}

	function PanneauAlbum() {
		var donnees = useMeta();
		var activites = useSelect( function ( select ) {
			return select( 'core' ).getEntityRecords( 'postType', 'asso_event', {
				per_page: 100,
				orderby: 'date',
				order: 'desc',
				_fields: 'id,title',
			} );
		}, [] );
		if ( 'asso_album' !== donnees.type ) {
			return null;
		}
		var options = [ { label: __( '— Aucune —', 'asso-core' ), value: '0' } ].concat(
			( activites || [] ).map( function ( activite ) {
				return {
					label: decodeEntities( activite.title.rendered ) || '#' + activite.id,
					value: String( activite.id ),
				};
			} )
		);
		return el(
			Panneau,
			{ name: 'asso-core-album', title: __( 'Détails de l’album', 'asso-core' ) },
			el(
				'div',
				{ style: { display: 'grid', gap: '16px' } },
				champ( donnees, 'asso_date', { label: __( 'Date', 'asso-core' ), type: 'date' } ),
				el( SelectControl, {
					label: __( 'Activité liée', 'asso-core' ),
					value: String( donnees.meta.asso_activite || 0 ),
					options: options,
					onChange: function ( valeur ) {
						donnees.maj( 'asso_activite', parseInt( valeur, 10 ) || 0 );
					},
					__nextHasNoMarginBottom: true,
					__next40pxDefaultSize: true,
				} )
			)
		);
	}

	wp.plugins.registerPlugin( 'asso-core-activite', { render: PanneauActivite } );
	wp.plugins.registerPlugin( 'asso-core-album', { render: PanneauAlbum } );
}( window.wp ) );
