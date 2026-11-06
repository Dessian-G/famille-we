# CLAUDE.md — Site de La Famille Wê de Dallas & Oklahoma

Ce fichier guide Claude Code sur ce projet. Le relire avant chaque tâche. Max est le responsable du projet : c'est lui qui valide chaque étape.

---

## 1. L'association

- **Nom complet** : La Famille Wê de Dallas & Oklahoma (nom court : La Famille Wê)
- **Slogan** : Ensemble Allons Plus Loin
- **Qui sommes-nous** : association de la diaspora ivoirienne, avec deux sections, Dallas (Texas) et Oklahoma. Association laïque.
- **Public** : membres et leurs familles, diaspora ivoirienne du Texas et de l'Oklahoma, sympathisants, partenaires.
- **Objectifs du site** :
  1. Présenter l'association, ses valeurs, son bureau et ses deux sections.
  2. Recruter de nouveaux membres.
  3. Annoncer les activités et en garder la mémoire (galerie photos).
  4. Collecter cotisations et dons en dollars américains par Zelle.
  5. Faciliter le contact avec l'association.

---

## 2. Contenus et ton

- **Langue** : français uniquement. Le thème et l'extension restent traduisibles pour une éventuelle version anglaise plus tard.
- **Ton** : chaleureux, familial et rassembleur. On vouvoie le visiteur.
- **Typographie française** : guillemets « », espace insécable avant : ; ! ? et avant $, majuscules accentuées (À, É, Ç).
- **Monnaie** : dollar américain. Affichage `25 $ US`, `12,50 $ US` (virgule décimale, espace insécable).
- **Dates et heures** : `samedi 14 novembre 2026`, `18 h 30`. Fuseau : America/Chicago (Dallas et l'Oklahoma sont tous deux à l'heure du Centre).
- **Ne jamais inventer** de noms, chiffres, dates, montants, témoignages, partenaires ou photos. Mettre un repère visible `[À FOURNIR : …]` et tenir à jour la liste de la section 12.
- Tout texte provisoire rédigé par Claude est marqué `[À VALIDER]` jusqu'à validation par Max.

---

## 3. Coordonnées et réseaux sociaux

| Élément | Valeur |
|---|---|
| E-mail de contact | famillewededallas@gmail.com |
| Facebook | Pas encore de page (à créer) : ne rien afficher ni annoncer d'ici là |
| Instagram, WhatsApp, TikTok, YouTube | Aucun pour l'instant : ne pas afficher d'icône |

- Afficher l'e-mail avec `antispambot()`, jamais en clair dans le HTML.
- Chaque icône de réseau a un libellé accessible (ex. « Page Facebook de La Famille Wê »).

---

## 4. Charte graphique

### Couleurs

Ratios de contraste calculés selon WCAG 2.x (seuil AA : 4,5:1 pour le texte courant).

| Rôle | Nom (slug theme.json) | Hex | Usage |
|---|---|---|---|
| Principale | Orange WE (`orange`) | `#F77F00` | Boutons d'action (Adhérer, Faire un don), aplats, détails décoratifs. Toujours avec texte `#1F2A24` (5,6:1). |
| Principale foncée | Orange profond (`orange-profond`) | `#A64E00` | Texte orange (surtitres, dates), liens au survol, boutons à texte blanc (5,7:1 sur blanc). Foncé le 02/11/2026 (avant `#B35400`) pour rester lisible sur le fond bleu ciel. |
| Titres et liens | Bleu WE (`bleu`) | `#1F5AA6` | Titres, liens, logotype de l'en-tête, chiffres clés. Texte blanc dessus : 6,8:1. Ajouté le 02/11/2026 à la demande de Max : le vert ne doit plus dominer dans les textes. |
| Secondaire | Vert WE (`vert`) | `#00754A` | Pied de page, aplats (bande défilante, tuiles de date, appel final), « plus loin » du slogan. Plus utilisé pour les titres ni les liens. Texte blanc dessus : 5,8:1. |
| Fond de page | Bleu ciel (`bleu-ciel`) | `#E3EEF9` | Fond principal du site, volontairement visible derrière les cartes blanches (bleu dessus : 5,8:1 ; orange profond : 4,8:1 ; vert : 4,9:1). |
| Fond d'attente | Gris léger (`gris-leger`) | `#F3F4F2` | Emplacements de photos en attente dans les cartes (ancien fond de page). |
| Cartes | Blanc (`blanc`) | `#FFFFFF` | Cartes et surfaces posées sur le bleu ciel. |
| Fond doux | Crème (`creme`) | `#FFF7EE` | Texte sur fond encre, blocs d'appoint (vert dessus : 5,4:1 ; orange profond : 5,3:1). |
| Texte | Encre (`encre`) | `#1F2A24` | Texte courant (12,6:1 sur bleu ciel, 14,8:1 sur blanc). |

**Interdits** : texte blanc sur `#F77F00` (2,6:1) et texte `#F77F00` sur fond blanc ou bleu ciel. Pour de l'orange en texte, utiliser `#A64E00`.

**Signature visuelle (direction D « Mélange », validée le 29/10/2026)** :
- Bande tricolore orange-blanc-vert tout en haut, puis en-tête collant en verre dépoli (bleu ciel translucide et flouté). Liens du menu en encre : ils restent lisibles quelle que soit la section qui passe sous l'en-tête translucide.
- Motif géométrique inspiré des pagnes ivoiriens (`assets/images/motif-pagne.svg`), tampon rotatif, collage de photos en formes d'arche et de cadre.
- Slogan en très grand dans le bandeau d'ouverture ; le H1 reste le nom de l'association.
- Cartes très arrondies (24 px), boutons en pilule, ombres douces. Les maquettes sont sur le canevas Claude Design « La Famille WE — Directions visuelles ».

### Polices

Google Fonts, **hébergées dans le thème** en woff2 (`assets/fonts/`), déclarées dans `theme.json` (fontFace). Aucun appel à fonts.googleapis.com.

- **Titres** : Bricolage Grotesque 700 et 800.
- **Texte** : Figtree 400, 400 italique, 600 et 700.
- Échelle inspirée de joinit.com/features (mesurée le 29/10/2026), tailles fluides (`clamp()`) dans `theme.json` :
  - texte courant 17 px sur ordinateur, 16 px sur mobile, interligne 1,55 ; petit texte 14 px (libellés, dates, mentions) ;
  - titres de cartes (H3) 20 px ; titres de page (H1) et intertitres (H2) des pages intérieures 26 px, 20 px sur mobile, soit la taille du logotype de l'en-tête (demande de Max du 06/11/2026) ; titres de section de l'accueil 36 px, 26 px sur mobile (taille fixée dans le motif `accueil`) ; slogan de l'accueil 60 px, 36 px sur mobile ;
  - titres en Bricolage 700 (800 réservé au slogan et aux grands chiffres), interlettrage -0,02 em ;
  - boutons de 48 px de haut, texte 16 px en Figtree 600 ;
  - petit surtitre en capitales orange profond au-dessus des titres de section ; liseré pêche `#F6D2B0` de 1 px sur les cartes blanches.

### Animations

- Effets dans `assets/css/effets.css` et `assets/js/effets.js` (sans bibliothèque) : titre révélé ligne par ligne, apparitions au défilement, compteurs, bande défilante, collage flottant, tampon rotatif.
- Toute animation qui dure plus de 5 secondes doit pouvoir être mise en pause (bouton « Pause » de la bande défilante, WCAG 2.2.2).
- Réglage « réduire les animations » de l'appareil : aucune animation, état final affiché directement.

### Logo

- Badge rond fourni par Max le 29/10/2026 (capture de téléphone `famillewe.jpg`), découpé avec fond transparent :
  - `assets/images/logo-famille-we.png` (512 × 512, source de l'icône du site) ;
  - `assets/images/logo-famille-we-256.webp` (affiché dans l'en-tête et le pied de page).
- En-tête : badge 52 px + logotype texte « La Famille Wê » (Bricolage Grotesque 800, bleu, « Wê » en orange profond), un seul lien vers l'accueil ; badge seul sous 480 px, nom gardé pour les lecteurs d'écran.
- Pied de page : badge 96 px cerclé de blanc, au-dessus du nom.
- Icône du site : pièce jointe n° 32 de la médiathèque, réglée dans `site_icon`.
- Une version vectorielle (SVG) ou un PNG haute définition sur fond transparent reste souhaitable `[À FOURNIR]` : la source actuelle est une capture compressée.
- Orthographe officielle : « La Famille Wê », avec accent circonflexe (confirmée par Max le 29/10/2026).

---

## 5. Arborescence

### Menu principal

Accueil · L'association ▾ · Activités · Galerie · Actualités · Adhésion · Contact · **[Faire un don]** (bouton orange)

- Sous-menu « L'association » : Qui sommes-nous · Le bureau · Nos sections · Partenaires
- Mobile : menu hamburger (bloc Navigation natif) ; le bouton « Faire un don » reste visible dans l'en-tête.

### Pied de page

Fond vert `#00754A`, texte blanc, 3 colonnes : logo + slogan · liens rapides · contact (e-mail ; Facebook quand la page existera).
Ligne du bas : © année La Famille Wê · Mentions légales · Confidentialité.

### Pages

| Page | Adresse | Contenu |
|---|---|---|
| Accueil | `/` (page statique) | Voir 5.1 |
| Qui sommes-nous | `/qui-sommes-nous/` | Histoire, mission, valeurs, ce que nous faisons, mot du président `[À FOURNIR]` |
| Le bureau | `/le-bureau/` | Cartes des membres du bureau (photo, nom, rôle, section) via un motif réutilisable |
| Nos sections | `/nos-sections/` | Dallas et Oklahoma : présentation, responsable, ville, rythme des réunions, contact |
| Partenaires | `/partenaires/` | Logos et liens ; masquée du menu tant qu'il n'y a pas de partenaire |
| Activités | `/activites/` | Prochaines activités en haut, activités passées ensuite (avec lien vers l'album), filtre par section, fiche détaillée par activité |
| Galerie | `/galerie/` | Albums par activité (couverture, titre, date, section) ; photos agrandies au clic |
| Actualités | `/actualites/` | Page des articles. Catégories proposées `[À VALIDER]` : Vie de l'association, Annonces, Culture, Solidarité |
| Adhésion | `/adhesion/` | Pourquoi adhérer, qui peut adhérer, cotisation en $ US `[À FOURNIR]`, formulaire, bloc « Payer par Zelle » |
| Faire un don | `/faire-un-don/` | À quoi servent les dons, montants suggérés, bloc « Payer par Zelle » |
| Contact | `/contact/` | Formulaire, e-mail, contacts par section (Facebook quand la page existera) |
| FAQ | `/faq/` | Adhésion, cotisation, activités (blocs Détails natifs) |
| Merci | `/merci/` | Confirmation après l'envoi d'un formulaire (adhésion, contact) ; non indexée |
| Mentions légales | `/mentions-legales/` | Nom, statut, adresse postale `[À FOURNIR]`, responsable de publication, hébergeur |
| Confidentialité | `/confidentialite/` | Données collectées, paiements Zelle (traités par les banques, hors du site), cookies, droit à l'image |
| Page 404 | modèle `404.html` | Message chaleureux + liens vers Accueil, Activités, Contact |

### 5.1 Page d'accueil (de haut en bas)

1. **Bandeau d'ouverture** : grande photo de groupe `[À FOURNIR]` avec un voile vert assez sombre pour garder un contraste AA ; titre « La Famille Wê de Dallas & Oklahoma » ; slogan « Ensemble Allons Plus Loin » ; boutons « Devenir membre » (orange) et « Nos activités » (contour blanc).
2. **Bienvenue** : 3 à 4 phrases sur la mission + lien « Qui sommes-nous ».
3. **Ce que nous faisons** : 4 cartes avec icône `[À VALIDER]` (ex. Solidarité, Culture, Rencontres, Familles et jeunesse).
4. **Prochaines activités** : les 3 prochaines (date, lieu, section) + « Voir toutes les activités ». S'il n'y en a aucune : « De nouvelles dates arrivent bientôt. Revenez nous voir très vite ! » (renvoyer vers Facebook quand la page existera)
5. **Nos deux sections** : Dallas et Oklahoma côte à côte, lien vers « Nos sections ».
6. **En chiffres** : membres, années d'existence, activités par an `[À FOURNIR]`. Bloc masqué tant que les chiffres manquent.
7. **Souvenirs** : 6 photos récentes des albums + « Voir la galerie ».
8. **Actualités** : les 3 derniers articles.
9. **Appel final** (fond vert) : « Rejoignez la famille » + boutons « Adhérer » et « Faire un don ».

---

## 6. Contenus dynamiques (extension asso-core)

- **Type de contenu `asso_event`** (Activités), adresse `/activites/nom-de-l-activite/` : titre, image, description, date et heure de début, date de fin (facultative), lieu, adresse (lien Google Maps), section, tarif en $ US (0 = gratuit), lien d'inscription ou de paiement (facultatif). « À venir » et « passées » sont calculées selon la date du jour, fuseau America/Chicago.
- **Type de contenu `asso_album`** (Galerie), adresse `/galerie/nom-de-l-album/` : titre, date, section, activité liée (facultative), photos via le bloc Galerie natif avec « Agrandir au clic ».
- **Taxonomie `asso_section`**, partagée par les deux types : Dallas, Oklahoma, Les deux sections.
- **Métadonnées** : `register_post_meta()` avec `show_in_rest`, saisies dans un panneau de l'éditeur. Pas d'ACF sans accord de Max.
- **Affichage** : préférer le bloc Boucle de requête (Query Loop) natif ; un bloc dynamique sur mesure seulement s'il ne suffit pas (ex. tri « à venir »).

### Mise en œuvre (extension asso-core 0.1.0, 29/10/2026)

- **Champs** : `asso_debut`, `asso_fin` (`AAAA-MM-JJTHH:MM`, heure du Centre), `asso_lieu`, `asso_adresse`, `asso_tarif` (texte : vide = à préciser, `0` = gratuit), `asso_lien` ; album : `asso_date` (`AAAA-MM-JJ`), `asso_activite` (identifiant). Panneau « Détails » dans l'éditeur : `assets/js/panneau.js`, JavaScript sans compilation (modules `wp.*`).
- **Boucles natives** : clés ajoutées à l'attribut `query` d'un bloc `core/query` et traitées par le filtre `query_loop_block_query_vars` (`includes/requetes.php`) :
  - `"assoPeriode": "a-venir"` ou `"passees"` : tri par date de début, coupure à minuit du jour (fuseau du site) ;
  - `"assoFiltreSection": true` : applique `?section=dallas|oklahoma` de l'adresse (« Les deux sections » reste incluse) ;
  - `"assoAvecCouverture": true` : seulement les contenus qui ont une image mise en avant.
- **Liaisons de blocs** : source `asso-core/activite` (`includes/liaisons.php`) avec la clé `cle` : `jour`, `mois`, `date_longue`, `quand`, `lieu`, `section`, `tarif`, `resume`, `lien_url`, `carte_url`, `album_url` (activité) ; `album_resume`, `activite_liee_url`, `activite_liee_titre` (album). Un bouton de classe `asso-si-lien` disparaît si son lien lié est vide.
- **Thème** : modèles `archive-asso_event`, `single-asso_event`, `archive-asso_album`, `single-asso_album` ; motifs `liste-activites`, `fiche-activite`, `liste-albums`, `fiche-album` ; gabarits PHP `asso_theme_carte_activite()` et `asso_theme_filtre_sections()` dans `functions.php`. Les images s'agrandissent au clic (`theme.json`, visionneuse de `core/image`).
- **Contenus** : `/activites/` et `/galerie/` sont les listes automatiques ; les anciennes pages provisoires du même nom sont en brouillon. Exemples marqués « À SUPPRIMER » : `scripts/creer-exemples-asso-core.php`.
- **Photos (02/11/2026)** : 11 photos fournies par Max (dossier `Téléchargements\Mobile Devices`) importées dans la médiathèque sous les noms `famille-we-souvenir-01` à `-11` (WebP, 2000 px max, métadonnées GPS retirées) et réunies dans l'album « Nos moments en famille [À VALIDER] ». Elles restent hors de Git. Sur l'accueil, le collage utilise `famille-we-souvenir-01` (arche) et `-07` (cadre) via `asso_theme_photo()` ; la mosaïque « Souvenirs » montre les 6 dernières photos des albums publiés, chacune liée à son album (`asso_theme_souvenirs()`). Sans photo, les repères `[À FOURNIR]` réapparaissent. Non publiés : `40203.jpg` (enfant identifiable, accord des parents à obtenir), `40268.jpg` (capture du logo) et la vidéo `40213.mp4`.

---

## 7. Paiements, formulaires et données personnelles

- **Paiements** (cotisations et dons) : **Zelle uniquement**, en USD. Aucun paiement en ligne sur le site : pas de Stripe, de PayPal ni d'extension de paiement.
  - Zelle n'a ni bouton ni intégration web. Le site affiche des **instructions**, et le membre ou le donateur paie depuis l'application de sa propre banque.
  - Motif réutilisable « Payer par Zelle » (`asso-theme/zelle`), affiché sur Adhésion et Faire un don :
    1. Destinataire Zelle : `[À FOURNIR : e-mail ou numéro de téléphone enregistré chez Zelle]`, au nom de `[À FOURNIR : nom du titulaire tel qu'il s'affiche dans Zelle]`, avec un bouton « Copier » accessible.
    2. Montant : cotisation `[À FOURNIR]` $ US, ou montant libre pour un don.
    3. Mémo à indiquer : « Cotisation – Prénom Nom » ou « Don – Prénom Nom ».
    4. QR code Zelle, facultatif `[À FOURNIR]`.
    5. Rappel : vérifier le nom du destinataire avant d'envoyer, car un paiement Zelle ne peut généralement pas être annulé.
  - Le destinataire Zelle est enregistré une seule fois dans une option `asso_zelle` (page de réglages d'asso-core), modifiable dans WP Admin sans toucher au code.
  - Jamais de numéro de compte bancaire ni de numéro de routage sur le site.
  - Le site ne peut pas savoir si un paiement a été reçu : le trésorier vérifie les paiements à la main. Ne jamais afficher « paiement confirmé » ni de statut de cotisation automatique.
- **Formulaires** (contact, adhésion) : une extension reconnue, à valider avec Max (ex. Fluent Forms ou WPForms Lite). Case de consentement, anti-spam (champ piège), aucune donnée sensible demandée.
- Ne jamais écrire qu'un don est « déductible des impôts » ni mentionner un statut 501(c)(3) sans confirmation de Max.
- **Secrets** : aucune clé, aucun mot de passe, aucun jeton dans le code, dans ce fichier ou dans Git. Ils vont dans les réglages de l'extension concernée ou dans `wp-config.php`, hors Git.
- **Droit à l'image** : publier uniquement les photos fournies et validées par Max ; pas de photo d'enfant identifiable sans l'accord des parents ; jamais de nom d'enfant.
- **E-mails** : en local, ne pas compter sur l'envoi réel des e-mails, et ne pas en conclure qu'un formulaire est cassé. Pour la mise en ligne, prévoir un envoi SMTP (ex. WP Mail SMTP), à décider avec Max.

---

## 8. Environnement technique

- Windows 11. Projet : `C:\Users\maxde\Studio\famille-we-de-dallas-oklahoma`.
- Site local WordPress Studio : http://localhost:8881 — WordPress 7.1.4, base SQLite.
- Dans Git Bash, toujours `studio.bat wp …` (`studio` seul est introuvable).
- `studio.bat wp shell` n'existe pas. Pour exécuter du PHP, l'écrire dans un fichier et lancer `studio.bat wp eval-file chemin/du/fichier.php` : `studio.bat` passe par cmd.exe, qui casse les guillemets doubles (comme le `&`) d'un `studio.bat wp eval '…'`.
- `studio.bat wp db query` ne fonctionne pas (il cherche MySQL) : lire la base SQLite avec `$wpdb` dans un `eval-file`.
- Si toutes les pages affichent « Failed opening required …\router.php » : le fichier `router.php` manque dans l'installation de Studio (`studio_app\app-<version>\resources\cli\php\`). Le restaurer depuis le paquet `studio_app\packages\studio_app-<version>-full.nupkg` ou réinstaller Studio, puis `studio.bat stop` et `studio.bat start --skip-browser`.
- Ne jamais toucher `wp-content/db.php` ni le mu-plugin `99-studio-loader` (intégration SQLite).
- Ne jamais modifier le cœur de WordPress (`wp-admin/`, `wp-includes/`, fichiers à la racine).
- Ne pas changer le titre du site en ligne de commande : le `&` passe mal à travers `studio.bat`. Max le fait dans WP Admin > Réglages > Général.
- Si une commande échoue avec « cURL error 60 » : s'arrêter et prévenir Max. Ne jamais désactiver la vérification SSL pour contourner.
- Fichiers en UTF-8 sans BOM, fins de ligne LF.
- Commandes interdites (aussi bloquées dans `.claude/settings.json`) : `wp db reset`, `wp db drop`, `studio delete`.

---

## 9. Règles de développement

- **Thème bloc `asso-theme` uniquement** (`wp-content/themes/asso-theme/`) :
  - Tout le style dans `theme.json` (couleurs, polices, tailles, espacements) ; CSS supplémentaire minimal.
  - Le design vit dans les fichiers du thème (`templates/`, `parts/`, `patterns/`), pas seulement dans l'éditeur. Si Max modifie un modèle dans l'éditeur du site, reporter la modification dans les fichiers.
  - Après l'ajout d'un **nouveau fichier** dans `patterns/`, augmenter `Version` dans `style.css` : WordPress met en cache la liste des motifs du thème tant que la version ne change pas (avec une entrée de cache différente pour le serveur web et pour WP-CLI). Modifier un motif existant ne demande rien. Augmenter aussi la version après un changement de `assets/css` ou `assets/js`, pour que les navigateurs rechargent les fichiers.
- **Extension métier `asso-core`** (`wp-content/plugins/asso-core/`) : types de contenu, taxonomies, métadonnées, blocs dynamiques. Rien de tout cela dans le thème.
- **Nommage** : préfixe `asso_` pour fonctions, options, hooks et métadonnées ; domaines de traduction `asso-theme` et `asso-core`.
- **Sécurité** : sorties échappées (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), entrées assainies, nonce sur chaque formulaire et action, contrôle des capacités (`current_user_can`), requêtes préparées.
- **Textes** : tout texte affiché passe par les fonctions de traduction (`__()`, `esc_html__()`…).
- **Extensions tierces** : justifier le besoin (alternatives, poids, maintenance) et demander l'accord de Max avant toute installation.
- **Mobile d'abord** : vérifier à 375 px, 768 px et 1280 px. Zones tactiles d'au moins 44 px. Aucun défilement horizontal.
- **Accessibilité WCAG AA** : contrastes de la section 4, texte alternatif sur chaque image informative, focus visible, navigation au clavier, lien « Aller au contenu », un seul H1 par page, hiérarchie des titres respectée, `lang="fr-FR"`.
- **Performance** : images en WebP, 2000 px de large au maximum, chargement différé natif, pas de carrousel lourd, pas de jQuery côté visiteur, uniquement les graisses de police listées.
- **Référencement** : un titre et une méta-description par page ; extension SEO éventuelle à valider avec Max.

---

## 10. Méthode de travail

1. Avant chaque tâche : un plan court (fichiers touchés, étapes).
2. Après chaque tâche : ce que Max doit vérifier, et où (URL, écran, taille d'écran).
3. Un commit par étape validée, message en français (ex. « Ajoute le type de contenu Activités »).
4. Rien sur un site en ligne (synchronisation, publication, hébergeur) sans l'accord explicite de Max.
5. Si une information manque : poser la question, ou mettre un repère `[À FOURNIR]` et continuer.
6. Répondre à Max en français, simplement, sans jargon inutile.

### Ordre de construction

1. Thème `asso-theme` : `theme.json`, polices, en-tête, pied de page, modèles de base.
2. Pages et menus avec contenus provisoires.
3. Extension `asso-core` : Activités, Galerie, sections.
4. Page d'accueil complète.
5. Formulaires et motif « Payer par Zelle ».
6. FAQ, Mentions légales, Confidentialité, Merci, 404.
7. Recette mobile, accessibilité et performance, puis préparation de la mise en ligne.

---

## 11. Fichiers suivis par Git

`.gitignore` en liste blanche. Seuls sont suivis : `.gitignore`, `.gitattributes`, `CLAUDE.md`, `.claude/`, `scripts/`, `wp-content/themes/asso-theme/`, `wp-content/plugins/asso-core/`.
`.mcp.json` est ignoré. `AGENTS.md` et `STUDIO.md` restent sur le disque, hors Git, et ne sont pas chargés par ce fichier.

---

## 12. Informations à fournir par Max

- [ ] Logo (SVG ou PNG ≥ 512 px) et son chemin sur le PC
- [ ] Page Facebook : pas encore créée ; fournir son nom exact et son URL une fois en ligne
- [ ] Histoire de l'association (année de création, fondateurs), mission, valeurs
- [ ] Membres du bureau : noms, rôles, section, photos (avec leur accord)
- [ ] Sections : responsables, villes, lieux et rythme des réunions
- [ ] Montant de la cotisation et conditions d'adhésion
- [ ] Statut juridique, adresse postale, statut fiscal des dons (501(c)(3) ou non)
- [ ] Zelle : e-mail ou numéro destinataire, nom affiché dans Zelle, QR code éventuel
- [ ] Photos : 11 reçues le 02/11/2026 (album provisoire). Reste : nom, date et section des activités photographiées, photos des deux sections, accord des parents pour `40203.jpg` ; premières activités à annoncer
- [ ] Chiffres clés (membres, années d'existence, activités par an)
