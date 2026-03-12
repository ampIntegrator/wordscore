# Test de l'injection SCSS dynamique

## Comment ça fonctionne maintenant

### 1. Injection des variables SCSS

Les couleurs ACF sont maintenant **injectées directement dans le compilateur SCSS** via le filtre `bootscore/scss/compiler`.

**Fonction dans functions.php (ligne ~203) :**
```php
add_filter('bootscore/scss/compiler', 'bootscore_child_inject_colors_to_scss');
function bootscore_child_inject_colors_to_scss($compiler) {
    $colors = wordscore_get_theme_colors();

    $compiler->setVariables([
        'primary'   => $colors['theme1'],
        'secondary' => $colors['theme2'],
        'theme1'    => $colors['theme1'],
        'theme2'    => $colors['theme2'],
        // etc.
    ]);

    return $compiler;
}
```

### 2. Compilation SCSS

Quand le SCSS est compilé :
1. Le filtre `bootscore/scss/compiler` est appelé
2. Les variables sont injectées avec `$compiler->setVariables()`
3. Bootstrap compile avec les bonnes valeurs
4. Le CSS généré contient les couleurs ACF

### 3. Variables disponibles dans SCSS

Vous pouvez maintenant utiliser ces variables dans TOUS vos fichiers SCSS :

```scss
// Ces variables sont automatiquement disponibles
$primary    // = theme1_color depuis ACF
$secondary  // = theme2_color depuis ACF
$theme1     // = theme1_color depuis ACF
$theme2     // = theme2_color depuis ACF
$theme3     // = theme3_color depuis ACF
$theme4     // = theme4_color depuis ACF
$theme5     // = theme5_color depuis ACF
$theme6     // = theme6_color depuis ACF
$ink        // = ink_color depuis ACF
```

## Test de fonctionnement

### 1. Tester l'injection

1. Aller dans **Apparence → Options Globales**
2. Changer `theme1_color` vers `#FF0000` (rouge)
3. Changer `theme2_color` vers `#00FF00` (vert)
4. Sauvegarder

### 2. Forcer la recompilation

La recompilation se fait automatiquement si :
- Vous êtes en environnement `development` ou `local`
- Vous modifiez un fichier SCSS

Si besoin, forcez la recompilation :
```bash
touch /path/to/bootscore-child/assets/scss/_bootscore-variables.scss
```

### 3. Vérifier le CSS compilé

Ouvrir `/assets/css/main.css` et chercher `.btn-primary` :

```css
.btn-primary {
    --bs-btn-color: #fff;
    --bs-btn-bg: #FF0000; /* Devrait être votre theme1 */
    --bs-btn-border-color: #FF0000;
    /* etc. */
}
```

### 4. Vérifier dans le navigateur

Actualiser la page avec un bouton `.btn-primary` :
- Le bouton devrait être rouge (theme1)

Inspecter le bouton :
- Les variables `--bs-btn-bg` devraient avoir la valeur de theme1

## Avantages de cette méthode

✅ **Bootstrap compilé avec les bonnes couleurs**
- Les variables SCSS `$primary` et `$secondary` utilisent directement les valeurs ACF
- Tout le système de couleurs Bootstrap est cohérent

✅ **Pas de surcharge CSS**
- Pas besoin de surcharger les variables CSS après coup
- Le CSS compilé contient directement les bonnes valeurs

✅ **Performance optimale**
- Une seule source de vérité (ACF)
- Pas de duplication de code
- CSS compilé minifié et optimisé

✅ **Maintenabilité**
- Changement dans ACF → recompilation automatique → tout le site à jour
- Pas besoin de toucher au SCSS manuellement

## Débogage

### Le CSS ne se recompile pas

1. **Vérifier l'environnement WordPress :**
   ```php
   echo wp_get_environment_type(); // Devrait être 'development' ou 'local'
   ```

2. **Forcer la recompilation :**
   ```bash
   touch assets/scss/_bootscore-variables.scss
   ```

3. **Vérifier que le filtre est appelé :**
   Ajouter temporairement dans `bootscore_child_inject_colors_to_scss()` :
   ```php
   error_log('SCSS colors injected: ' . print_r($colors, true));
   ```

### Les couleurs ne changent pas

1. **Vérifier le cache ACF :**
   ```php
   var_dump(wordscore_get_theme_colors());
   ```

2. **Vider tous les caches :**
   - Cache WordPress
   - Cache transients (automatiquement vidé par ACF)
   - Cache navigateur (Ctrl+Shift+R)

3. **Vérifier le CSS compilé :**
   - Ouvrir `assets/css/main.css`
   - Chercher `.btn-primary`
   - Vérifier que `--bs-btn-bg` a la bonne valeur

## Différence avec l'approche précédente

### Avant (injection CSS uniquement)

```
ACF → Variables CSS injectées dans <head> → Tentative de surcharge
```

❌ Problème : Bootstrap utilise des variables SCSS compilées, pas les variables CSS

### Maintenant (injection SCSS)

```
ACF → Variables SCSS injectées AVANT compilation → Bootstrap compile avec les bonnes valeurs
```

✅ Solution : Bootstrap utilise directement les couleurs ACF dès la compilation
