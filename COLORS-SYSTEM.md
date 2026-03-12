# Système de Gestion des Couleurs - Wordscore Child Theme

## Vue d'ensemble

Le thème utilise un système de couleurs **100% dynamique** basé sur les options ACF de la page "Options Globales".

### Architecture

```
ACF Options (global)
    ↓
wordscore_get_theme_colors() [cache-helpers.php]
    ↓
Variables CSS injectées dans <head> [functions.php]
    ↓
Classes CSS générées dynamiquement
```

## Couleurs disponibles

### 6 couleurs thématiques + Ink

| Nom | Clé ACF | Variable CSS | Valeur par défaut | Usage |
|-----|---------|--------------|-------------------|--------|
| Theme 1 | `theme1_color` | `--colorTheme1` | `#0d6efd` | Primary / Couleur principale |
| Theme 2 | `theme2_color` | `--colorTheme2` | `#6c757d` | Secondary / Couleur secondaire |
| Theme 3 | `theme3_color` | `--colorTheme3` | `#dc3545` | Danger / Rouge |
| Theme 4 | `theme4_color` | `--colorTheme4` | `#0dcaf0` | Info / Cyan |
| Theme 5 | `theme5_color` | `--colorTheme5` | `#198754` | Success / Vert |
| Theme 6 | `theme6_color` | `--colorTheme6` | `#333333` | Dark / Gris foncé |
| Ink | `ink_color` | `--colorText` | `#202224` | Couleur du texte principal |

## Mapping Bootstrap

Les couleurs Bootstrap `primary` et `secondary` sont **automatiquement mappées** sur `theme1` et `theme2` :

```css
--bs-primary: var(--colorTheme1);
--bs-secondary: var(--colorTheme2);
```

### Classes Bootstrap surchargées dynamiquement

Les classes suivantes utilisent les couleurs ACF :

- `.btn-primary` → utilise `theme1`
- `.btn-secondary` → utilise `theme2`
- `.btn-outline-primary` → utilise `theme1`
- `.btn-outline-secondary` → utilise `theme2`

## Classes disponibles

### Classes de background

```html
<div class="bg-theme1">Utilise theme1</div>
<div class="bg-theme2">Utilise theme2</div>
<div class="bg-theme3">Utilise theme3</div>
<div class="bg-theme4">Utilise theme4</div>
<div class="bg-theme5">Utilise theme5</div>
<div class="bg-theme6">Utilise theme6</div>
```

### Utilisation dans le Flexible Content

Dans vos templates de blocs flexibles, utilisez les helpers :

```php
// Récupérer le background (retourne classe + style inline)
$background = flexible_get_background($bloc, true);

// Utilisation
<section class="<?php echo $background['class']; ?>" style="<?php echo $background['style']; ?>">
```

### Boutons

Les boutons générés via `flexible_render_button()` utilisent automatiquement le système :

```php
// Type = 'primary' → utilise theme1
// Type = 'secondary' → utilise theme2
flexible_render_button(
    $button['link'],
    'primary',  // utilise theme1
    false,
    'btn-lg'
);
```

## Code source

### Fonction centralisée

**Fichier :** `inc/cache-helpers.php`

```php
wordscore_get_theme_colors()
```

Cette fonction :
- Utilise le cache statique (mémoire)
- Retourne un tableau avec les 7 couleurs
- Est appelée UNE SEULE FOIS par requête

### Injection des variables CSS

**Fichier :** `functions.php`

**Hook :** `wp_head` (priorité 6)

**Fonction :** `bootscore_child_inject_global_css()`

Injecte dans `<head>` :
1. Variables CSS (--colorTheme1 à --colorTheme6)
2. Variables Bootstrap (--bs-primary, --bs-secondary)
3. Classes `.bg-theme1` à `.bg-theme6`
4. Classes `.btn-primary`, `.btn-secondary`, etc.

### Injection admin

**Hook :** `admin_head`

**Fonction :** `bootscore_child_inject_admin_colors()`

Injecte les mêmes variables CSS dans l'admin WordPress pour :
- La palette de couleurs visible à droite
- Les sélecteurs de couleurs dans ACF
- Les previews dans les pages d'options

## SCSS (fallback uniquement)

**Fichier :** `assets/scss/_bootscore-variables.scss`

Les valeurs SCSS sont des **fallbacks pour la compilation initiale uniquement**.

Elles ne sont **PAS utilisées** en production car les classes CSS sont générées dynamiquement via PHP.

## Avantages du système

✅ **Une seule source de vérité** : Options ACF
✅ **Pas de duplication** : Une fonction centralisée
✅ **Performance** : Cache en mémoire + transients
✅ **Cohérence** : Bootstrap suit automatiquement les couleurs du thème
✅ **Flexibilité** : Changement des couleurs en temps réel sans recompilation SCSS

## Changement de couleurs

1. Aller dans **Apparence → Options Globales**
2. Modifier les couleurs Theme 1 à 6
3. Sauvegarder
4. Le cache est automatiquement vidé (hook `acf/save_post`)
5. Les nouvelles couleurs sont appliquées immédiatement

## Migration depuis l'ancien système

### Avant (❌)

```php
// Récupération multiple fois dans le code
$theme1 = wordscore_get_cached_option('theme1_color', '#0d6efd');
$theme2 = wordscore_get_cached_option('theme2_color', '#6c757d');
// etc.
```

```scss
// Couleurs hardcodées dans SCSS
$primary: #2A9CC7;
$secondary: #fb8500;
```

### Maintenant (✅)

```php
// Une seule récupération
$colors = wordscore_get_theme_colors();

// Utilisation
echo $colors['theme1'];
echo $colors['theme2'];
```

```css
/* Variables CSS dynamiques */
.mon-element {
    background: var(--colorTheme1);
    color: var(--bs-primary);
}
```

## Notes techniques

- Les classes `.bg-theme1` à `.bg-theme6` ont `!important` pour surcharger Bootstrap
- Les boutons Bootstrap sont surchargés avec `!important` également
- Le fichier `admin-colors.scss` est conservé mais ne contient plus de règles de couleur
- La palette admin est générée par `display_color_palette_admin()` dans `functions.php`
