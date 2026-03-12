# Translations / Traductions

## Available Languages

Currently, Wordscore is available in:
- English (default)
- French (fr_FR) - Coming soon

## Contributing a Translation

Want to translate Wordscore into your language? Here's how:

### 1. Get the POT file

The master translation template is located at:
```
languages/wordscore.pot
```

### 2. Create your translation

#### Option A: Use Poedit (Recommended)
1. Download [Poedit](https://poedit.net/)
2. Open `wordscore.pot`
3. Create a new translation for your language
4. Save as `wordscore-{locale}.po` (e.g., `wordscore-fr_FR.po`)
5. Poedit will automatically generate the `.mo` file

#### Option B: Use Loco Translate (WordPress Plugin)
1. Install the [Loco Translate](https://wordpress.org/plugins/loco-translate/) plugin
2. Go to Loco Translate > Themes > Wordscore
3. Click "New language"
4. Select your language and translate

### 3. Submit your translation

- Fork the repository on GitHub
- Add your `.po` and `.mo` files to the `languages/` folder
- Create a Pull Request

## Translation Guidelines

- **Be concise**: Admin interfaces need short, clear text
- **Use formal language**: This is a professional tool
- **Test in context**: Install the theme and check your translations
- **Check placeholders**: Make sure `%s`, `%1$s`, etc. are preserved

## Need Help?

- Report translation issues: https://github.com/ampIntegrator/wordscore/issues
- Questions? Open a discussion on GitHub

## File Naming Convention

Translation files follow WordPress standards:
- `wordscore.pot` - Translation template
- `wordscore-{locale}.po` - Translation source (human-readable)
- `wordscore-{locale}.mo` - Compiled translation (machine-readable)

Examples:
- `wordscore-fr_FR.po` / `wordscore-fr_FR.mo` - French (France)
- `wordscore-de_DE.po` / `wordscore-de_DE.mo` - German (Germany)
- `wordscore-es_ES.po` / `wordscore-es_ES.mo` - Spanish (Spain)

## Text Domain

The theme uses the text domain: `wordscore`

Make sure all translations reference this domain.
