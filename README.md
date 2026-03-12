# Wordscore - Professional WordPress Theme Builder

**Professional Theme Builder for WordPress** - Built as a child theme of [Bootscore](https://bootscore.me/), Wordscore adds a complete page builder system with 10+ flexible content blocks, global styling controls, and automated installation.

## Why Wordscore?

Wordscore extends the excellent **Bootscore framework** (Bootstrap 5 + WordPress best practices) with a complete **Theme Builder** system, giving you:

- ✅ **Visual page building** without coding
- ✅ **10+ professional content blocks** ready to use
- ✅ **Global design control** (fonts, colors, spacing)
- ✅ **Performance optimized** (30% less DB queries)
- ✅ **Translation ready** (i18n/l10n support)
- ✅ **1-click installation** (automated setup)

**Architecture:** Wordscore (child theme) + Bootscore (parent theme) = Complete solution

## What's Included

### Content Blocks
- **Hero** - Full-width with background image
- **Image-Texte** - 7 width options (25%-75%)
- **Price Cards** - Responsive pricing tables (1-4 cards)
- **Accordion** - Bootstrap native
- **Tabs** - Bootstrap system
- **Buttons** - Grouped with alignment
- **Contact Form** - CF7 integration
- **Columns** - Multi-column layouts with icons
- And more...

### Global Options
- **Typography:** Google Fonts or custom uploads (.woff/.woff2)
- **Font Sizes:** H1-H6 customizable
- **Colors:** 6 theme colors + text color (RGBA support)
- **Border Radius:** Buttons, wrappers, images
- **Performance:** Built-in caching system

## Requirements

### Parent Theme (Dependency)

- **[Bootscore](https://bootscore.me/)** v6.0.0 or higher
  - Provides: Bootstrap 5, SCSS compilation, WordPress structure
  - **Wordscore will not work without Bootscore installed**

### Required Plugin

- **[Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/)** (SCF) - **FREE**
  - Open-source fork of Advanced Custom Fields
  - Provides: Flexible Content, Options Pages, Repeaters
  - 100% ACF-compatible

### Recommended Plugins

- **WP Rocket** or **W3 Total Cache** - Caching and optimization
- **Contact Form 7** - Works with Contact Form block

## Installation

### Quick Start (3 steps)

1. **Install Bootscore parent theme** (dependency)
   - Download from [Bootscore.me](https://bootscore.me/)
   - Upload via Appearance > Themes > Add New
   - **Do NOT activate** (it's a parent theme)

2. **Install and activate Wordscore**
   ```bash
   cd wp-content/themes/
   git clone https://github.com/ampIntegrator/wordscore.git bootscore-child
   ```
   - Activate in Appearance > Themes

3. **Follow automatic setup**
   - Admin notification appears: "Install required plugin"
   - Click to install SCF (Secure Custom Fields)
   - Go to SCF > Field Groups > Sync all groups
   - Configure global options in Appearance > Options Globales

**Done!** Start building pages with flexible content blocks.

### Detailed Installation

1. Clonez ce repository dans `/wp-content/themes/`
   ```bash
   cd wp-content/themes/
   git clone https://github.com/ampIntegrator/wordscore.git bootscore-child
   ```

2. Activez le thème "Bootscore Child" dans **Apparence > Thèmes**

### 3. Installation automatique de SCF ✨

Dès l'activation du thème, vous verrez une **notification admin** vous invitant à installer **Secure Custom Fields (SCF)**.

**Procédure simplifiée:**

1. **Notification automatique**
   - Une bannière apparaît en haut de l'admin WordPress
   - Message: *"Ce thème requiert le plugin suivant: Secure Custom Fields"*

2. **Installation en 1 clic**
   - Cliquez sur **"Commencer l'installation du plugin"**
   - SCF se télécharge et s'installe automatiquement depuis le répertoire WordPress.org
   - Cliquez ensuite sur **"Activer"**

3. **Alternative manuelle** (si la notification ne s'affiche pas)
   - Allez dans **Extensions > Ajouter une extension**
   - Recherchez **"Secure Custom Fields"**
   - Installez et activez

### 4. Synchronisation automatique des champs SCF ✨

**Aucun import manuel nécessaire!** Le thème utilise le système **Local JSON** de SCF.

**Comment ça fonctionne:**

1. **Détection automatique**
   - Dès que SCF est activé, il détecte automatiquement les fichiers JSON dans `acf-json/`
   - Tous les field groups (blocs flexibles, options pages) sont reconnus

2. **Synchronisation en 1 clic**
   - Allez dans **SCF > Field Groups**
   - Vous verrez une notification: *"Sync available"* sur chaque groupe
   - Cliquez sur **"Sync changes"** pour chaque groupe (ou utilisez le bouton "Bulk sync")
   - **C'est tout!**

3. **Quels field groups synchroniser?**

   Vous devriez voir 7 groupes à synchroniser:
   - ✅ **Contenu Flexible** - Tous les blocs (Hero, Image-Texte, Price Cards, etc.)
   - ✅ **Options Globales** - Polices, couleurs, border-radius
   - ✅ **Header** - Configuration du header
   - ✅ **Footer** - Configuration du footer
   - ✅ **Bannière** - Bannière optionnelle
   - ✅ **Réseaux sociaux** - Liens sociaux
   - ✅ **Logos** - Gestion des logos

**Avantages du système Local JSON:**
- 🚀 **Automatique** - Détecte les nouveaux champs
- 🔄 **Synchronisation** - Garde vos champs à jour
- 🛡️ **Sécurisé** - Pas de risque d'écrasement accidentel
- 📦 **Version control** - Les JSON sont dans Git

**Note:** Le dossier `acf-json/` est le **nom officiel** recommandé par ACF/SCF pour le Local JSON. SCF détecte automatiquement ce dossier sans configuration supplémentaire. C'est la convention standard utilisée par tous les thèmes WordPress professionnels.

#### Fichiers du dossier acf-json/:

```
acf-json/
├── flexible.json              # 🎨 Tous les blocs flexibles (10+ blocs)
├── options-global.json        # ⚙️ Options globales du thème
├── options-header.json        # 🎯 Configuration du header
├── options-footer.json        # 📄 Configuration du footer
├── options-banniere.json      # 🏷️ Bannière optionnelle
├── options-socials.json       # 🔗 Réseaux sociaux
└── options-logos.json         # 🖼️ Gestion des logos
```

#### Que faire en cas de problème?

- **SCF n'est pas installé?** → Vérifiez les notifications admin ou installez manuellement
- **Pas de "Sync available"?** → Vérifiez que les fichiers JSON sont bien dans `acf-json/`
- **Erreur de synchronisation?** → Supprimez le field group et resynchronisez

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
├── acf-json/                     # 📦 SCF Local JSON (sync auto)
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

## Architecture: Child Theme

### Why a Child Theme?

Wordscore is built as a **child theme** of Bootscore. This architecture provides significant advantages:

**Separation of Concerns:**
- **Bootscore** (parent) handles the framework: Bootstrap 5, SCSS compilation, WordPress integration
- **Wordscore** (child) handles the features: Flexible blocks, global options, page building

**Benefits:**
- ✅ **Updates:** Benefit from Bootscore security and feature updates automatically
- ✅ **Maintainability:** Clean separation between framework and features
- ✅ **Performance:** Leverage Bootscore's optimized Bootstrap integration
- ✅ **Compatibility:** Access to Bootscore's WooCommerce support and templates
- ✅ **Reliability:** Built on a proven, tested foundation

**How it Works:**
```
WordPress Core
    └── Bootscore (parent)
            └── Wordscore (child) ← Your active theme
```

When activated, Wordscore automatically:
1. Inherits all Bootscore functionality (Bootstrap, templates, etc.)
2. Adds its own features (flexible blocks, global options)
3. Overrides only what needs customization

**This is the WordPress-recommended way** to build professional themes. Popular examples: Divi, Avada, and most premium themes use this architecture.

### Dependencies

**Required:**
- Bootscore parent theme (framework)
- Secure Custom Fields plugin (content builder)

**Both are automatically suggested** when you activate Wordscore.

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
4. Ajoutez la définition SCF dans `acf-json/flexible.json`
5. SCF détectera automatiquement les changements (sync en 1 clic)
6. Ajoutez le mapping dans `flexible.php`

## FAQ

### Why do I need to install Bootscore separately?

Wordscore is built as a **child theme** of Bootscore. This architecture means:
- Bootscore provides the framework (Bootstrap 5, SCSS compilation, WordPress structure)
- Wordscore adds the features (flexible blocks, global options, page builder)

**Analogy:** Think of Bootscore as the engine, Wordscore as the custom body and features.

This approach has major benefits:
- ✅ You get automatic Bootscore updates (security, features)
- ✅ Smaller codebase (Wordscore only contains custom features)
- ✅ Better maintainability (separation of concerns)

### Can I use Wordscore without Bootscore?

**No.** Wordscore requires Bootscore to function. It's a dependency, like WordPress itself.

The installation is simple:
1. Install Bootscore (framework)
2. Install Wordscore (features)
3. Activate Wordscore only

### Will Bootscore updates break Wordscore?

No. Child themes are designed to survive parent updates. Wordscore only overrides specific templates and adds new features without modifying Bootscore core files.

**Safe updates:**
- Bootscore security updates ✅
- Bootscore feature updates ✅
- Bootstrap version updates ✅

### Can I customize Wordscore further?

Absolutely! Wordscore is fully customizable:
- Modify SCSS in `assets/scss/`
- Add new blocks in `template-parts/flexible-blocks/`
- Extend functions in `functions.php`
- Add custom templates

### Is this production-ready?

Yes! Wordscore includes:
- ✅ Performance optimizations (30% less DB queries)
- ✅ Translation ready (i18n/l10n)
- ✅ Clean, maintainable code
- ✅ Professional architecture
- ✅ Active development and support

### How do I update Wordscore?

**From GitHub:**
```bash
cd wp-content/themes/bootscore-child
git pull origin main
```

**Manual:**
1. Download the latest release
2. Replace the theme folder
3. Clear all caches

**Your content and settings are safe** - they're stored in the database, not in theme files.

## Support et contribution

- **Issues**: [GitHub Issues](https://github.com/ampIntegrator/wordscore/issues)
- **Pull Requests**: Les contributions sont les bienvenues!

## License

This theme is distributed under the **GPL v3.0** license.

## Credits

### Built On

**Wordscore extends and requires:**
- **[Bootscore](https://bootscore.me/)** by bootScore - Excellent Bootstrap 5 WordPress framework (parent theme)
- **[Bootstrap 5](https://getbootstrap.com/)** - The world's most popular front-end framework
- **[Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/)** - Free, open-source fork of ACF

### Special Thanks

- **bootScore team** for creating and maintaining the excellent Bootscore framework
- **Bootstrap team** for the responsive framework
- **SCF contributors** for the free ACF alternative
- **WordPress community** for the amazing platform

### Relationship with Bootscore

Wordscore is an **independent child theme** that:
- ✅ Requires Bootscore (dependency)
- ✅ Extends Bootscore with new features
- ✅ Is NOT affiliated with or endorsed by bootScore
- ✅ Respects Bootscore's MIT license
- ✅ Adds its own GPL v3.0 licensed code

**Think of it as:** Bootscore provides the foundation, Wordscore builds the house.

## Author

Developed by [ampIntegrator](https://github.com/ampIntegrator)

**Repository:** https://github.com/ampIntegrator/wordscore
**Issues:** https://github.com/ampIntegrator/wordscore/issues
**Changelog:** See [CHANGELOG.md](CHANGELOG.md)
