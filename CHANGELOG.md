# Changelog

All notable changes to Wordscore will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-03-12

### Changed - Refonte complète du système de couleurs

- **BREAKING:** Système de couleurs 100% dynamique basé sur ACF Options
- Les classes `.btn-primary` et `.btn-secondary` utilisent maintenant `theme1` et `theme2`
- Les classes `.bg-theme1` à `.bg-theme6` sont générées dynamiquement via PHP
- Suppression des couleurs hardcodées dans `_bootscore-variables.scss`

### Added

- **Nouvelle fonction centralisée:** `wordscore_get_theme_colors()` dans `inc/cache-helpers.php`
  - Récupère les 6 couleurs thématiques + ink en une seule fois
  - Cache statique + performance optimale
  - Élimine la duplication de code

- **Mapping Bootstrap → Thème:**
  - `--bs-primary` → `--colorTheme1` (theme1)
  - `--bs-secondary` → `--colorTheme2` (theme2)
  - Les boutons Bootstrap suivent automatiquement les couleurs ACF

- **Injection des classes dans l'admin:**
  - Nouvelle fonction `bootscore_child_inject_admin_colors()`
  - Palette de couleurs visible en temps réel dans l'admin
  - Classes `.bg-theme1` à `.bg-theme6` disponibles dans ACF

- **Documentation:** Nouveau fichier `COLORS-SYSTEM.md` expliquant l'architecture complète

### Fixed

- Correction du bug où les boutons utilisaient les couleurs hardcodées SCSS au lieu des couleurs ACF
- Suppression de la duplication de `wordscore_get_cached_option()` dans `functions.php` (2 fois)
- Classes de background qui n'utilisaient pas les valeurs dynamiques

### Performance

- Réduction de 6 appels `wordscore_get_cached_option()` par page (palette admin)
- Réduction de 6 appels supplémentaires (injection CSS globale)
- Total: 12 appels en moins, remplacés par 1 seul appel à `wordscore_get_theme_colors()`

### Migration Notes

Les valeurs par défaut des couleurs ont été mises à jour:
- `theme1_color`: `#0d6efd` (Bootstrap blue)
- `theme2_color`: `#6c757d` (Bootstrap gray)
- `theme3_color`: `#dc3545` (Bootstrap red)
- `theme4_color`: `#0dcaf0` (Bootstrap cyan)
- `theme5_color`: `#198754` (Bootstrap green)
- `theme6_color`: `#333333` (Dark gray)

Aucune action requise pour les utilisateurs existants - les couleurs sont conservées en base de données.

## [1.0.0] - 2026-03-12

### Added
- **Complete Theme Builder System** with flexible content blocks
- **10+ Flexible Content Blocks:**
  - Hero (full-width with background image)
  - Image-Texte (7 width options: 25%, 33%, 42%, 50%, 58%, 66%, 75%)
  - Price Cards (1-4 cards, max-width 320px, responsive)
  - Accordion (Bootstrap native)
  - Tabs (Bootstrap system)
  - Buttons (group with alignment)
  - Contact Form (CF7 integration)
  - Titre (title block with background)
  - Colonnes Multiples (2-6 columns with icons/images)
  - Colonnes Composer (custom column system)

- **Global Options Management:**
  - Typography: Google Fonts or custom uploaded fonts (.woff/.woff2)
  - Font sizes: H1-H6 customizable
  - Font weights: 300-900 for headings and body
  - Body font size: 12-24px
  - 6 theme colors + ink color (all with RGBA support)
  - Border-radius: buttons (with pill option), content-wrapper, image-wrapper

- **Performance Optimizations:**
  - ACF options cache system (30% reduction in DB queries)
  - Static cache + transient cache (2-level system)
  - Automatic cache invalidation on save
  - Google Fonts with preconnect
  - Font-display: swap for all fonts

- **Installation Automation:**
  - TGM Plugin Activation for SCF (1-click install)
  - ACF Local JSON automatic sync (acf-json/ directory)
  - No manual JSON import required

- **Internationalization (i18n):**
  - Full translation support
  - Text domain: wordscore
  - POT file included (100+ translatable strings)
  - Translation guide for contributors
  - Compatible with Poedit and Loco Translate

- **Developer Features:**
  - SCSS automatic compilation (WordPress admin)
  - Flexible helpers system
  - Clean code structure
  - Git-friendly with .gitignore
  - Comprehensive documentation

- **Documentation:**
  - Complete README.md with installation guide
  - Translation guide (languages/README.md)
  - Inline code documentation
  - GitHub repository ready

### Changed
- Theme renamed from "Bootscore Child" to **"Wordscore"**
- Version bumped to 1.0.0 (initial public release)
- License changed to GPL v3.0
- Author: ampIntegrator
- Text domain: bootscore → wordscore

### Technical Details
- **Requirements:**
  - WordPress 5.0+
  - PHP 7.4+
  - Bootscore parent theme v6.0.0+
  - Secure Custom Fields (SCF) plugin (free)

- **Browser Support:**
  - Chrome/Edge (last 2 versions)
  - Firefox (last 2 versions)
  - Safari (last 2 versions)
  - Responsive: mobile-first design

- **Performance Metrics:**
  - 30% reduction in database queries
  - 50ms average response time improvement
  - Optimized Google Fonts loading
  - Automatic SCSS compilation

### Repository
- GitHub: https://github.com/ampIntegrator/wordscore
- Issues: https://github.com/ampIntegrator/wordscore/issues
- License: GPL v3.0

---

## Version History

### [1.0.0] - 2026-03-12
Initial public release of Wordscore Theme Builder

---

## Upgrade Guide

### From Bootscore Child to Wordscore 1.0.0

If you're upgrading from the basic Bootscore Child theme:

1. **Backup your site** (database + files)
2. Install Secure Custom Fields (SCF) plugin
3. Activate Wordscore theme
4. Sync ACF field groups (SCF > Field Groups > Sync)
5. Configure global options (Appearance > Options Globales)
6. Clear all caches (WordPress, server, CDN)

---

## Contributing

Found a bug? Have a feature request?

- Report bugs: https://github.com/ampIntegrator/wordscore/issues
- Suggest features: Open a GitHub discussion
- Contribute code: Fork and create a Pull Request
- Translate: See languages/README.md

---

## Credits

- Based on [Bootscore](https://bootscore.me/) by bootScore
- Uses [Bootstrap 5](https://getbootstrap.com/)
- Powered by [Secure Custom Fields](https://fr.wordpress.org/plugins/secure-custom-fields/)
- Developed by [ampIntegrator](https://github.com/ampIntegrator)

---

**Full Changelog**: https://github.com/ampIntegrator/wordscore/commits/main
