# Wordscore - WordPress Theme Builder

Thème enfant Bootscore transformé en Theme Builder complet avec système de blocs flexibles et gestion globale des options de design.

## Description

Wordscore est un thème WordPress professionnel basé sur Bootscore, offrant:

- **Système de blocs flexibles** (Flexible Content Builder)
- **Gestion globale du design** (polices, couleurs, espacements)
- **Page builder visuel** via SCF
- **Performance optimisée** avec compilation SCSS automatique
- **Responsive design** natif Bootstrap 5

## Prérequis

### Thème parent requis

- **[Bootscore](https://bootscore.me/)** v6.0.0 ou supérieur

### Plugins requis

- **[Secure Custom Fields](https://fr.wordpress.org/plugins/secure-custom-fields/)** (SCF) - **GRATUIT**
  - Fork d'Advanced Custom Fields (ACF)
  - Requis pour: Flexible Content, Options Pages, Repeaters, File Upload
  - 100% compatible avec ACF (utilise la même API)

### Plugins recommandés

- **WP Rocket** ou **W3 Total Cache** - Pour la mise en cache et l'optimisation
- **Contact Form 7** - Fonctionne avec le bloc Contact Form

## Installation

### 1. Installer le thème parent

1. Téléchargez et installez [Bootscore](https://bootscore.me/)
2. **N'activez PAS le thème parent**, installez uniquement

### 2. Installer SCF (Secure Custom Fields)

1. Allez dans **Extensions > Ajouter une extension**
2. Recherchez **"Secure Custom Fields"**
3. Cliquez sur **"Installer"** puis **"Activer"**
4. C'est gratuit, pas besoin de licence!

### 3. Installer Wordscore

1. Clonez ce repository dans `/wp-content/themes/`
   ```bash
   cd wp-content/themes/
   git clone https://github.com/ampIntegrator/wordscore.git bootscore-child
   ```

2. Activez le thème "Bootscore Child" dans **Apparence > Thèmes**

### 4. Importer les configurations SCF

**⚠️ IMPORTANT**: Cette étape est **obligatoire** pour que le thème fonctionne. Sans l'import des fichiers JSON, vous n'aurez pas accès aux blocs flexibles ni aux options de personnalisation.

Tous les fichiers de configuration SCF se trouvent dans le dossier **`builder/`** du thème.

#### Procédure d'import:

1. **Accédez aux outils SCF**
   - Dans l'admin WordPress, allez dans **SCF** (menu latéral gauche)
   - Cliquez sur **Tools** (Outils)

2. **Importez chaque fichier JSON**
   - Dans l'onglet "Import Field Groups", cliquez sur **"Choose File"**
   - Naviguez jusqu'à `/wp-content/themes/bootscore-child/builder/`
   - Sélectionnez un fichier JSON
   - Cliquez sur **"Import JSON"**

3. **Ordre d'importation recommandé:**

   **a) Options Pages (dans l'ordre):**
   - `builder/options-global.json` → Options globales (polices, couleurs, border-radius)
   - `builder/options-header.json` → Configuration du header
   - `builder/options-footer.json` → Configuration du footer
   - `builder/options-banniere.json` → Bannière (optionnelle)
   - `builder/options-socials.json` → Réseaux sociaux
   - `builder/options-logos.json` → Gestion des logos

   **b) Flexible Content (en dernier):**
   - `builder/flexible.json` → **Tous les blocs flexibles** (Hero, Image-Texte, Price Cards, etc.)

4. **Vérification de l'import**
   - Allez dans **SCF > Field Groups**
   - Vous devriez voir tous les groupes de champs importés
   - Vérifiez que "Contenu Flexible" apparaît dans la liste

#### Fichiers du dossier builder/:

```
builder/
├── flexible.json              # 🎨 Tous les blocs flexibles (10+ blocs)
├── options-global.json        # ⚙️ Options globales du thème
├── options-header.json        # 🎯 Configuration du header
├── options-footer.json        # 📄 Configuration du footer
├── options-banniere.json      # 🏷️ Bannière optionnelle
├── options-socials.json       # 🔗 Réseaux sociaux
└── options-logos.json         # 🖼️ Gestion des logos
```

#### Que faire en cas d'erreur d'import?

- Vérifiez que **SCF (Secure Custom Fields)** est bien activé
- Vérifiez que le fichier JSON n'est pas corrompu
- Essayez de réimporter le fichier
- Si un groupe existe déjà, supprimez-le avant de réimporter

## Pages d'options

Le thème ajoute automatiquement 3 pages d'options sous **Apparence**:

### Options Globales
- **Polices**: Google Fonts ou polices personnalisées uploadables
- **Font sizes**: H1-H6 personnalisables
- **Couleurs**: 6 couleurs de thème + couleur texte (Ink)
- **Border-radius**: Boutons, content-wrapper, image-wrapper
- Gestion des font-weights

### Header
- Logo
- Menu principal
- Bannière (optionnelle)
- Background colors

### Footer
- Colonnes de contenu
- Logos partenaires
- Copyright
- Background colors

## Blocs flexibles disponibles

### Blocs de contenu

- **Hero** - Section hero avec image de fond, titre, texte, bouton
- **Image Texte** - Image + texte côte à côte (7 largeurs disponibles: 25%, 33%, 42%, 50%, 58%, 66%, 75%)
- **Titre** - Bloc titre avec fond personnalisable
- **Buttons** - Groupe de boutons alignables
- **Contact Form** - Intégration Contact Form 7 avec heading et texte

### Blocs interactifs

- **Accordion** - Accordéon Bootstrap natif
- **Tabs** - Système d'onglets Bootstrap

### Blocs de mise en page

- **Colonnes Multiples** - 2 à 6 colonnes avec icônes ou images
- **Colonnes Composer** - Système de colonnes personnalisable
- **Price Cards** - Cartes de prix (1-4 cartes, responsive)

## Fonctionnalités

### Gestion des polices

#### Google Fonts
- 10 polices Google Fonts prédéfinies
- Option "Police personnalisée" pour ajouter n'importe quelle Google Font
- Chargement optimisé avec font-display: swap
- Tous les weights disponibles (300-900)

#### Polices personnalisées
- Upload de fichiers .woff/.woff2/.ttf/.otf
- 1 fichier pour headings + 1 pour body
- Précision du font-weight de chaque fichier
- Génération automatique des @font-face

### Système de couleurs

- 6 couleurs de thème personnalisables
- Couleur texte (Ink) indépendante
- Color pickers avec support RGBA
- Variables CSS injectées dynamiquement
- Palette de couleurs visible dans l'admin WP

### Performance

- **CSS dynamique injecté dans `<head>`** - Pas de fichier CSS externe pour les options
- **Compatible avec tous les plugins de cache** - WP Rocket, W3 Total Cache, etc.
- **Compilation SCSS automatique** - Via WordPress admin
- **Google Fonts avec preconnect** - Chargement optimisé
- **Font-display: swap** - Évite le FOIT (Flash Of Invisible Text)

## Structure des fichiers

```
bootscore-child/
├── assets/
│   ├── css/
│   │   ├── main.css              # CSS compilé (généré auto)
│   │   ├── main.css.map          # Source map
│   │   └── custom-fonts.css      # Template pour polices custom
│   ├── scss/
│   │   ├── main.scss             # Point d'entrée SCSS
│   │   ├── _bootscore-variables.scss  # Variables Bootstrap
│   │   ├── blocks/               # Styles des blocs flexibles
│   │   │   ├── _bloc-hero.scss
│   │   │   ├── _bloc-price-cards.scss
│   │   │   └── ...
│   │   └── components/           # Composants globaux
│   │       ├── _header.scss
│   │       ├── _footer.scss
│   │       └── _flexible-blocks.scss
│   └── js/
│       └── custom.js
├── builder/                      # 📦 Configurations SCF (à importer)
│   ├── flexible.json             # Tous les blocs flexibles
│   ├── options-global.json       # Options globales du thème
│   ├── options-header.json       # Configuration header
│   ├── options-footer.json       # Configuration footer
│   ├── options-banniere.json     # Bannière optionnelle
│   ├── options-socials.json      # Réseaux sociaux
│   └── options-logos.json        # Gestion des logos
├── inc/
│   └── flexible-helpers.php      # Fonctions helper pour blocs
├── template-parts/
│   ├── flexible-blocks/          # Templates des blocs flexibles
│   │   ├── bloc-hero.php
│   │   ├── bloc-image-texte.php
│   │   ├── bloc-price-cards.php
│   │   ├── bloc-accordion.php
│   │   └── ...
│   ├── header/                   # Composants header
│   └── footer/                   # Composants footer
├── flexible.php                  # Router des blocs flexibles
├── functions.php                 # Fonctions principales du thème
├── header.php                    # Template header
├── footer.php                    # Template footer
├── page-flexibleContent.php      # Template page avec blocs
├── style.css                     # Métadonnées du thème
└── README.md                     # Documentation (ce fichier)
```

## Utilisation

### Créer une page avec blocs flexibles

1. Créez ou éditez une page
2. Dans l'éditeur, vous verrez le champ **"Contenu Flexible"**
3. Cliquez sur **"Ajouter un bloc"**
4. Choisissez le type de bloc
5. Configurez les options du bloc
6. Répétez pour ajouter d'autres blocs
7. Publiez la page

### Personnaliser les couleurs globales

1. Allez dans **Apparence > Options Globales**
2. Modifiez les couleurs avec les color pickers
3. Sauvegardez
4. Les changements sont visibles immédiatement

### Changer les polices

#### Avec Google Fonts:
1. **Apparence > Options Globales**
2. Choisissez une police dans "Police des titres (Headings)"
3. Choisissez une police dans "Police du texte (Body)"
4. Ajustez les font-weights si nécessaire
5. Sauvegardez

#### Avec polices personnalisées:
1. **Apparence > Options Globales**
2. Activez "Utiliser des polices personnalisées"
3. Uploadez votre fichier pour Headings (.woff2 recommandé)
4. Précisez le font-weight de ce fichier
5. Uploadez votre fichier pour Body
6. Précisez le font-weight de ce fichier
7. Sauvegardez

## Développement

### Compiler le SCSS

Le SCSS se compile automatiquement via l'admin WordPress:

1. Modifiez vos fichiers `.scss` dans `assets/scss/`
2. Allez dans **Apparence > Options Globales** (ou n'importe quelle page d'options)
3. Le SCSS sera recompilé automatiquement
4. Le fichier `main.css` sera mis à jour

### Ajouter un nouveau bloc flexible

1. Créez le template dans `template-parts/flexible-blocks/bloc-nom.php`
2. Créez le style dans `assets/scss/blocks/_bloc-nom.scss`
3. Importez le SCSS dans `assets/scss/main.scss`
4. Ajoutez la définition SCF dans `builder/flexible.json`
5. Réimportez `flexible.json` dans SCF (Tools > Import)
6. Ajoutez le mapping dans `flexible.php`

## Support et contribution

- **Issues**: [GitHub Issues](https://github.com/ampIntegrator/wordscore/issues)
- **Pull Requests**: Les contributions sont les bienvenues!

## Licence

Ce thème est distribué sous licence GPL v2 ou ultérieure.

## Crédits

- Basé sur [Bootscore](https://bootscore.me/) par bootScore
- Utilise [Bootstrap 5](https://getbootstrap.com/)
- Utilise [Secure Custom Fields](https://fr.wordpress.org/plugins/secure-custom-fields/) (SCF) - Fork gratuit d'ACF

## Auteur

Développé par [ampIntegrator](https://github.com/ampIntegrator)
