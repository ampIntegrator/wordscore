#!/usr/bin/env python3
"""
Script pour corriger CORRECTEMENT le carousel :
1. UN bouton par slide (link, btn_type, btn_outline) - PAS de repeater
2. text_color GLOBAL au carousel (pas dans chaque slide)
"""

import json
import sys

def fix_carousel_correct():
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

    # 1. Retirer le repeater buttons du niveau global
    carousel['sub_fields'] = [
        f for f in carousel['sub_fields']
        if f.get('name') != 'buttons'
    ]
    print("✓ Removed global buttons repeater")

    # 2. Ajouter text_color global (après content_text_align)
    text_color_global = {
        "key": "field_carousel_text_color",
        "label": "Couleur du texte",
        "name": "text_color",
        "type": "select",
        "instructions": "Couleur du texte dans le content-wrapper (constant sur toutes les slides)",
        "choices": {
            "light": "Light (blanc)",
            "dark": "Dark (ink)"
        },
        "default_value": "light",
        "wrapper": {
            "width": "33",
            "class": "",
            "id": ""
        }
    }

    # Trouver content_text_align et insérer après
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_text_align':
            carousel['sub_fields'].insert(i + 1, text_color_global)
            print("✓ Added global text_color after content_text_align")
            break

    # 3. Dans les slides : retirer text_color et ajouter champs bouton simple
    slides_field = None
    for sub_field in carousel['sub_fields']:
        if sub_field.get('name') == 'slides':
            slides_field = sub_field
            break

    if slides_field:
        # Retirer text_color des slides
        slides_field['sub_fields'] = [
            f for f in slides_field['sub_fields']
            if f.get('name') != 'text_color'
        ]
        print("✓ Removed text_color from slides")

        # Ajouter champs bouton simple (après text)
        button_fields = [
            {
                "key": "field_slide_button_link",
                "label": "Bouton : Lien",
                "name": "button_link",
                "type": "link",
                "instructions": "Lien du bouton (optionnel)",
                "return_format": "array",
                "wrapper": {
                    "width": "50",
                    "class": "",
                    "id": ""
                }
            },
            {
                "key": "field_slide_button_type",
                "label": "Bouton : Type",
                "name": "button_type",
                "type": "select",
                "choices": {
                    "primary": "Primary",
                    "secondary": "Secondary"
                },
                "default_value": "primary",
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            },
            {
                "key": "field_slide_button_outline",
                "label": "Bouton : Outline",
                "name": "button_outline",
                "type": "true_false",
                "default_value": 0,
                "ui": 1,
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            }
        ]

        # Trouver la position de 'text' et insérer après
        for i, sf in enumerate(slides_field['sub_fields']):
            if sf.get('name') == 'text':
                for j, bf in enumerate(button_fields):
                    slides_field['sub_fields'].insert(i + 1 + j, bf)
                print("✓ Added simple button fields (link, type, outline) to each slide")
                break

    # Sauvegarder le fichier
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ group_flexilder.json fixed correctly")
    return True

if __name__ == '__main__':
    success = fix_carousel_correct()
    sys.exit(0 if success else 1)
