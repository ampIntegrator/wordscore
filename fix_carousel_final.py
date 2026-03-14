#!/usr/bin/env python3
"""
Script pour corriger le carousel selon le modèle image-texte :
1. Champs bouton : btn_link, btn_type, btn_outline, btn_text_color (par slide)
2. Backgrounds avec support RGBA (custom_color)
"""

import json
import sys

def fix_carousel_final():
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

    # 1. Modifier content_bg_custom pour accepter RGBA
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_bg_custom':
            carousel['sub_fields'][i]['type'] = 'text'
            carousel['sub_fields'][i]['instructions'] = 'Couleur personnalisée (hex, rgb, rgba)'
            carousel['sub_fields'][i]['placeholder'] = 'Ex: rgba(255, 255, 255, 0.9)'
            print("✓ Modified content_bg_custom to support RGBA")
            break

    # 2. Dans les slides : remplacer les champs bouton
    slides_field = None
    for sub_field in carousel['sub_fields']:
        if sub_field.get('name') == 'slides':
            slides_field = sub_field
            break

    if slides_field:
        # Retirer les anciens champs bouton
        slides_field['sub_fields'] = [
            f for f in slides_field['sub_fields']
            if not f.get('name', '').startswith('button_')
        ]
        print("✓ Removed old button fields")

        # Ajouter les bons champs bouton (après text)
        button_fields = [
            {
                "key": "field_slide_btn_link",
                "label": "Bouton (optionnel)",
                "name": "btn_link",
                "type": "link",
                "return_format": "array",
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            },
            {
                "key": "field_slide_btn_type",
                "label": "Type de bouton",
                "name": "btn_type",
                "type": "select",
                "choices": {
                    "primary": "Primary",
                    "secondary": "Secondary",
                    "success": "Success",
                    "danger": "Danger",
                    "warning": "Warning",
                    "info": "Info",
                    "light": "Light",
                    "dark": "Dark"
                },
                "default_value": "primary",
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            },
            {
                "key": "field_slide_btn_outline",
                "label": "Bouton outline",
                "name": "btn_outline",
                "type": "true_false",
                "default_value": 0,
                "ui": 1,
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            },
            {
                "key": "field_slide_btn_text_color",
                "label": "Couleur texte bouton",
                "name": "btn_text_color",
                "type": "select",
                "choices": {
                    "": "Auto (par défaut)",
                    "light": "Light (blanc)",
                    "dark": "Dark (noir)"
                },
                "default_value": "",
                "wrapper": {
                    "width": "25",
                    "class": "",
                    "id": ""
                }
            }
        ]

        # Trouver 'text' et insérer après
        for i, sf in enumerate(slides_field['sub_fields']):
            if sf.get('name') == 'text':
                for j, bf in enumerate(button_fields):
                    slides_field['sub_fields'].insert(i + 1 + j, bf)
                print("✓ Added correct button fields (btn_link, btn_type, btn_outline, btn_text_color)")
                break

    # Sauvegarder
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ group_flexilder.json fixed with correct button structure")
    return True

if __name__ == '__main__':
    success = fix_carousel_final()
    sys.exit(0 if success else 1)
