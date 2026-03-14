#!/usr/bin/env python3
"""
Script pour corriger les boutons du carousel
- Déplacer le repeater buttons hors des slides (niveau global carousel)
- Le text_color dans les slides est pour le texte, pas les boutons
"""

import json
import sys

def fix_carousel_buttons():
    json_path = '/Applications/MAMP/htdocs/blou/wp-content/themes/bootscore-child/group_flexilder.json'

    with open(json_path, 'r', encoding='utf-8') as f:
        data = json.load(f)

    # Trouver le champ flexilder
    field = None
    for f in data['fields']:
        if f.get('name') == 'flexilder':
            field = f
            break

    if not field:
        print("ERROR: flexilder field not found")
        return False

    # Trouver le layout carousel
    carousel = field['layouts'].get('layout_carousel')
    if not carousel:
        print("ERROR: layout_carousel not found")
        return False

    # 1. Trouver et retirer le repeater buttons des slides
    slides_field = None
    buttons_field = None
    for sub_field in carousel['sub_fields']:
        if sub_field.get('name') == 'slides':
            slides_field = sub_field
            break

    if slides_field:
        # Chercher et retirer le repeater buttons
        for i, sf in enumerate(slides_field['sub_fields']):
            if sf.get('name') == 'buttons':
                buttons_field = slides_field['sub_fields'].pop(i)
                print("✓ Removed buttons repeater from slides")
                break

    # 2. Ajouter le repeater buttons au niveau global (après slides)
    if buttons_field:
        # Modifier la clé pour éviter conflit
        buttons_field['key'] = 'field_carousel_buttons'
        buttons_field['label'] = 'Boutons'
        buttons_field['name'] = 'buttons'

        # Mettre à jour les clés des sous-champs
        for sf in buttons_field['sub_fields']:
            if sf.get('name') == 'link':
                sf['key'] = 'field_carousel_button_link'
            elif sf.get('name') == 'btn_type':
                sf['key'] = 'field_carousel_button_type'
            elif sf.get('name') == 'btn_outline':
                sf['key'] = 'field_carousel_button_outline'
            elif sf.get('name') == 'btn_text_color':
                sf['key'] = 'field_carousel_button_text_color'

        # Trouver la position de slides et insérer après
        for i, sub_field in enumerate(carousel['sub_fields']):
            if sub_field.get('name') == 'slides':
                carousel['sub_fields'].insert(i + 1, buttons_field)
                print("✓ Added buttons repeater at carousel level (after slides)")
                break

    # Sauvegarder le fichier
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ group_flexilder.json fixed successfully")
    return True

if __name__ == '__main__':
    success = fix_carousel_buttons()
    sys.exit(0 if success else 1)
