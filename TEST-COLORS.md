# Test du système de couleurs dynamiques

## Comment tester

### 1. Vérifier l'injection des variables CSS

Ouvrir une page du site et inspecter le `<head>` :

```html
<style id="global-settings">
:root {
    --bs-primary: #0d6efd;
    --bs-primary-rgb: 13, 110, 253;
    --bs-primary-text-emphasis: #052c65;
    --bs-primary-bg-subtle: #cfe2ff;
    --bs-primary-border-subtle: #9ec5fe;
    --bs-secondary: #6c757d;
    --bs-secondary-rgb: 108, 117, 125;
    /* etc. */
}
</style>
```

### 2. Vérifier que les boutons utilisent les bonnes couleurs

**Test 1 - Boutons dans le Flexible Content Hero:**

1. Aller dans **Apparence → Options Globales**
2. Changer `theme1_color` vers une couleur vive (ex: `#FF0000` rouge)
3. Changer `theme2_color` vers une autre couleur (ex: `#00FF00` vert)
4. Sauvegarder
5. Actualiser une page avec un bloc Hero contenant des boutons `.btn-primary` et `.btn-secondary`

**Résultat attendu:**
- Les boutons `.btn-primary` doivent être rouges
- Les boutons `.btn-secondary` doivent être verts

**Test 2 - Inspecter les variables CSS dans le navigateur:**

Ouvrir les DevTools et inspecter un bouton `.btn-primary` :

```css
.btn-primary {
    --bs-btn-bg: #FF0000; /* Devrait être votre theme1 */
    --bs-btn-border-color: #FF0000;
}
```

### 3. Tester tous les composants Bootstrap

Créer une page de test avec :

```html
<!-- Boutons -->
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-outline-primary">Outline Primary</button>
<button class="btn btn-outline-secondary">Outline Secondary</button>

<!-- Alertes -->
<div class="alert alert-primary">Alerte Primary</div>
<div class="alert alert-secondary">Alerte Secondary</div>

<!-- Badges -->
<span class="badge bg-primary">Badge Primary</span>
<span class="badge bg-secondary">Badge Secondary</span>

<!-- Backgrounds -->
<div class="bg-primary text-white p-3">Background Primary</div>
<div class="bg-secondary text-white p-3">Background Secondary</div>
<div class="bg-primary-subtle p-3">Background Primary Subtle</div>
<div class="bg-secondary-subtle p-3">Background Secondary Subtle</div>

<!-- Text -->
<p class="text-primary">Texte Primary</p>
<p class="text-secondary">Texte Secondary</p>
<p class="text-primary-emphasis">Texte Primary Emphasis</p>
<p class="text-secondary-emphasis">Texte Secondary Emphasis</p>

<!-- Borders -->
<div class="border border-primary p-3">Border Primary</div>
<div class="border border-secondary p-3">Border Secondary</div>
<div class="border border-primary-subtle p-3">Border Primary Subtle</div>
<div class="border border-secondary-subtle p-3">Border Secondary Subtle</div>
```

**Résultat attendu:**
- TOUS ces éléments doivent utiliser les couleurs theme1 et theme2 définies dans ACF
- Si vous changez theme1/theme2 dans les options et actualisez, TOUS les composants changent

### 4. Tester la palette admin

1. Aller dans **Apparence → Options Globales** (ou toute page d'édition avec ACF)
2. Vérifier la palette de couleurs à droite
3. Les couleurs affichées doivent correspondre aux valeurs actuelles

### 5. Tester les classes bg-theme1 à bg-theme6

```html
<div class="bg-theme1 p-3 text-white">Theme 1</div>
<div class="bg-theme2 p-3 text-white">Theme 2</div>
<div class="bg-theme3 p-3 text-white">Theme 3</div>
<div class="bg-theme4 p-3 text-white">Theme 4</div>
<div class="bg-theme5 p-3 text-white">Theme 5</div>
<div class="bg-theme6 p-3 text-white">Theme 6</div>
```

**Résultat attendu:**
- Chaque div doit avoir la couleur correspondante définie dans ACF Options Globales

## Résolution de problèmes

### Les couleurs ne changent pas

1. **Vider le cache:**
   - Le cache ACF est automatiquement vidé lors de la sauvegarde
   - Si problème persistant, vider le cache WordPress
   - Vider le cache du navigateur (Ctrl+Shift+R)

2. **Vérifier les valeurs en base:**
   ```php
   // Ajouter temporairement dans functions.php
   var_dump(wordscore_get_theme_colors());
   ```

3. **Vérifier l'injection CSS:**
   - Inspecter le `<head>` de la page
   - Chercher `<style id="global-settings">`
   - Vérifier que les variables `--bs-primary` et `--bs-secondary` sont présentes

### Les boutons ont encore les anciennes couleurs

Si Bootstrap utilise toujours les couleurs hardcodées :

1. Vérifier que le CSS compilé n'écrase pas les variables
2. Vérifier l'ordre de chargement des CSS (le style inline doit être après Bootstrap)
3. Vérifier la priorité du hook `wp_head` (doit être >= 6)

### La palette admin n'affiche pas les bonnes couleurs

1. Vérifier que `bootscore_child_inject_admin_colors()` est appelé
2. Vérifier dans le `<head>` de l'admin : `<style id="admin-theme-colors">`
3. Vider le cache du navigateur

## Checklist de validation

- [ ] Les variables `--bs-primary` et `--bs-secondary` sont dans le `<head>`
- [ ] Les variables RGB sont calculées correctement
- [ ] Les boutons `.btn-primary` utilisent theme1
- [ ] Les boutons `.btn-secondary` utilisent theme2
- [ ] Les alertes suivent les couleurs ACF
- [ ] Les badges suivent les couleurs ACF
- [ ] Les backgrounds subtle fonctionnent
- [ ] Les text-emphasis fonctionnent
- [ ] Les borders fonctionnent
- [ ] La palette admin affiche les bonnes couleurs
- [ ] Les classes `.bg-theme1` à `.bg-theme6` fonctionnent
- [ ] Changer une couleur dans ACF met à jour TOUT le site
