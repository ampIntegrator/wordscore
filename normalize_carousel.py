#!/usr/bin/env python3
"""
Normaliser le carousel selon les patterns du projet
- enable_opacity: True → 1
- Vérifier toutes les configurations
"""

import json
import sys

def normalize_carousel():
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

    changes = []

    # Parcourir tous les sub_fields
    for i, sub_field in enumerate(carousel['sub_fields']):
        name = sub_field.get('name')

        # 1. Corriger enable_opacity: True → 1
        if sub_field.get('type') == 'color_picker':
            if sub_field.get('enable_opacity') is True:
                carousel['sub_fields'][i]['enable_opacity'] = 1
                changes.append(f"✓ {name}: enable_opacity True → 1")
            if sub_field.get('return_opacity') is True:
                carousel['sub_fields'][i]['return_opacity'] = 1
                changes.append(f"✓ {name}: return_opacity True → 1")

        # 2. Vérifier true_false ont ui: 1
        if sub_field.get('type') == 'true_false':
            if sub_field.get('ui') != 1:
                carousel['sub_fields'][i]['ui'] = 1
                changes.append(f"✓ {name}: ui set to 1")

    # Vérifier les slides aussi
    for sub_field in carousel['sub_fields']:
        if sub_field.get('name') == 'slides':
            for j, slide_field in enumerate(sub_field['sub_fields']):
                if slide_field.get('type') == 'true_false':
                    if slide_field.get('ui') != 1:
                        sub_field['sub_fields'][j]['ui'] = 1
                        changes.append(f"✓ slide.{slide_field.get('name')}: ui set to 1")

    if changes:
        # Sauvegarder
        with open(json_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)

        print("\n".join(changes))
        print(f"\n✅ {len(changes)} normalizations applied")
    else:
        print("✅ All fields already normalized")

    return True

if __name__ == '__main__':
    success = normalize_carousel()
    sys.exit(0 if success else 1)
