/**
 * Effets du thème asso-theme : titre révélé ligne par ligne, apparitions au
 * défilement, compteurs, bande défilante avec bouton pause, ombre de l'en-tête.
 * Aucune dépendance. Sans JavaScript, tout le contenu reste affiché normalement.
 */
( function () {
	'use strict';

	var racine = document.documentElement;
	var mouvementReduit = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	// Textes traduisibles fournis par functions.php (wp_localize_script).
	var textes = window.assoEffets || {
		pause: 'Pause',
		lecture: 'Lecture',
		pauseLibelle: 'Mettre en pause les animations',
		lectureLibelle: 'Reprendre les animations',
		copie: 'Copié !',
		copieAnnonce: 'Destinataire Zelle copié dans le presse-papiers.',
		copieEchec: 'La copie a échoué : sélectionnez le destinataire à la main.'
	};

	racine.classList.add( 'asso-js' );

	/* Filtre par section : signale le bouton qui correspond à l'adresse actuelle. */
	var sectionActive = new URLSearchParams( window.location.search ).get( 'section' ) || '';
	document.querySelectorAll( '.asso-filtre-sections a' ).forEach( function ( lien ) {
		var section = new URL( lien.href, window.location.href ).searchParams.get( 'section' ) || '';
		if ( section === sectionActive ) {
			lien.setAttribute( 'aria-current', 'true' );
		}
	} );

	/* Titre : chaque segment séparé par un <br> devient une ligne animée. */
	if ( ! mouvementReduit ) {
		document.querySelectorAll( '.asso-titre-revele' ).forEach( function ( titre ) {
			var lignes = titre.innerHTML.split( /<br\s*\/?>/i );
			titre.innerHTML = lignes
				.map( function ( ligne, i ) {
					return '<span class="asso-ligne"><span style="animation-delay:' + ( 0.05 + i * 0.15 ).toFixed( 2 ) + 's">' + ligne + '</span></span>';
				} )
				.join( '' );
		} );
	}

	/* Bloc Zelle : bouton « Copier le destinataire », affiché seulement si un destinataire est réglé. */
	document.querySelectorAll( '.asso-zelle' ).forEach( function ( bloc ) {
		var destinataire = bloc.querySelector( '.asso-zelle-destinataire' );
		var bouton = bloc.querySelector( '.asso-zelle-copier button' );
		if ( ! destinataire || ! bouton ) {
			return;
		}
		bloc.classList.add( 'a-destinataire' );
		var annonce = document.createElement( 'span' );
		annonce.className = 'asso-visuellement-cache';
		annonce.setAttribute( 'role', 'status' );
		bouton.parentNode.appendChild( annonce );
		var texteInitial = bouton.textContent;

		function signaler( reussi ) {
			annonce.textContent = reussi ? textes.copieAnnonce : textes.copieEchec;
			bouton.textContent = reussi ? textes.copie : texteInitial;
			window.setTimeout( function () {
				bouton.textContent = texteInitial;
				annonce.textContent = '';
			}, 3000 );
		}

		bouton.addEventListener( 'click', function () {
			var valeur = destinataire.textContent.trim();
			if ( navigator.clipboard && window.isSecureContext ) {
				navigator.clipboard.writeText( valeur ).then( function () {
					signaler( true );
				}, function () {
					signaler( false );
				} );
				return;
			}
			// Ancien navigateur ou page non sécurisée : sélection puis copie classique.
			var zone = document.createElement( 'textarea' );
			zone.value = valeur;
			zone.setAttribute( 'readonly', '' );
			zone.className = 'asso-visuellement-cache';
			document.body.appendChild( zone );
			zone.select();
			var reussi = false;
			try {
				reussi = document.execCommand( 'copy' );
			} catch ( erreur ) {
				reussi = false;
			}
			document.body.removeChild( zone );
			signaler( reussi );
		} );
	} );

	/* Compteur : un nombre entier qui monte de 0 à sa valeur. */
	function animerCompteur( element ) {
		var cible = parseInt( element.textContent.trim(), 10 );
		if ( mouvementReduit || ! /^\d+$/.test( element.textContent.trim() ) || cible < 2 ) {
			return;
		}
		var debut = null;
		var duree = 1200;
		element.setAttribute( 'aria-label', String( cible ) );
		function etape( instant ) {
			if ( null === debut ) {
				debut = instant;
			}
			var avancement = Math.min( ( instant - debut ) / duree, 1 );
			element.textContent = String( Math.round( cible * ( 1 - Math.pow( 1 - avancement, 3 ) ) ) );
			if ( avancement < 1 ) {
				window.requestAnimationFrame( etape );
			}
		}
		window.requestAnimationFrame( etape );
	}

	/* Apparitions au défilement. */
	var aRevele = document.querySelectorAll( '.asso-revele, .asso-cascade' );
	function montrer( element ) {
		element.classList.add( 'est-visible' );
		element.querySelectorAll( '.asso-compteur' ).forEach( animerCompteur );
	}
	if ( mouvementReduit || ! ( 'IntersectionObserver' in window ) ) {
		aRevele.forEach( montrer );
	} else {
		var observateur = new IntersectionObserver(
			function ( entrees ) {
				entrees.forEach( function ( entree ) {
					if ( entree.isIntersecting ) {
						montrer( entree.target );
						observateur.unobserve( entree.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -10% 0px', threshold: 0.15 }
		);
		aRevele.forEach( function ( element ) {
			observateur.observe( element );
		} );
	}

	/* Bande défilante : piste doublée pour une boucle continue, plus un bouton pause. */
	if ( ! mouvementReduit ) {
		document.querySelectorAll( '.asso-defile' ).forEach( function ( bande ) {
			var piste = bande.querySelector( '.asso-defile__piste' );
			if ( ! piste ) {
				return;
			}
			Array.prototype.slice.call( piste.children ).forEach( function ( enfant ) {
				var copie = enfant.cloneNode( true );
				copie.setAttribute( 'aria-hidden', 'true' );
				piste.appendChild( copie );
			} );
			piste.classList.add( 'est-double' );

			var bouton = document.createElement( 'button' );
			bouton.type = 'button';
			bouton.className = 'asso-pause-animations';
			bouton.setAttribute( 'aria-pressed', 'false' );
			bouton.textContent = textes.pause;
			bouton.setAttribute( 'aria-label', textes.pauseLibelle );
			bouton.addEventListener( 'click', function () {
				var enPause = racine.classList.toggle( 'asso-pause' );
				bouton.setAttribute( 'aria-pressed', enPause ? 'true' : 'false' );
				bouton.textContent = enPause ? textes.lecture : textes.pause;
				bouton.setAttribute( 'aria-label', enPause ? textes.lectureLibelle : textes.pauseLibelle );
			} );
			bande.appendChild( bouton );
		} );
	}

	/* Ombre sous l'en-tête dès que la page défile. */
	var enAttente = false;
	window.addEventListener(
		'scroll',
		function () {
			if ( enAttente ) {
				return;
			}
			enAttente = true;
			window.requestAnimationFrame( function () {
				racine.classList.toggle( 'asso-a-defile', window.scrollY > 8 );
				enAttente = false;
			} );
		},
		{ passive: true }
	);
}() );
