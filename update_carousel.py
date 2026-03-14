#!/usr/bin/env python3
"""
Script pour mettre à jour le bloc carousel dans group_flexilder.json
Modifications :
1. height_custom : min=300, max=600, step=20
2. Retirer padding_top et padding_bottom
3. Ajouter content_valign (alignement vertical)
4. Ajouter content_text_align (alignement interne texte)
5. Remplacer btn_text/btn_url/btn_style par repeater buttons
"""

import json
import sys

def update_carousel_block():
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

    # 1. Modifier height_custom (min=300, max=600, step=20)
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'height_custom':
            carousel['sub_fields'][i]['min'] = 300
            carousel['sub_fields'][i]['max'] = 600
            carousel['sub_fields'][i]['step'] = 20
            carousel['sub_fields'][i]['default_value'] = 500
            print("✓ Modified height_custom: min=300, max=600, step=20")
            break

    # 2. Retirer padding_top et padding_bottom
    carousel['sub_fields'] = [
        f for f in carousel['sub_fields']
        if f.get('name') not in ['padding_top', 'padding_bottom']
    ]
    print("✓ Removed padding_top and padding_bottom")

    # 3. Ajouter content_valign après content_align
    content_valign_field = {
        "key": "field_carousel_content_valign",
        "label": "Alignement vertical du content-wrapper",
        "name": "content_valign",
        "type": "select",
        "instructions": "Position verticale du content-wrapper",
        "choices": {
            "start": "Haut",
            "center": "Centre",
            "end": "Bas"
        },
        "default_value": "center",
        "wrapper": {
            "width": "33",
            "class": "",
            "id": ""
        }
    }

    # Trouver la position de content_align et insérer après
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_align':
            carousel['sub_fields'].insert(i + 1, content_valign_field)
            print("✓ Added content_valign after content_align")
            break

    # 4. Ajouter content_text_align après content_valign
    content_text_align_field = {
        "key": "field_carousel_content_text_align",
        "label": "Alignement interne du texte",
        "name": "content_text_align",
        "type": "select",
        "instructions": "Alignement du texte à l'intérieur du content-wrapper",
        "choices": {
            "start": "Gauche",
            "center": "Centre",
            "end": "Droite"
        },
        "default_value": "center",
        "wrapper": {
            "width": "34",
            "class": "",
            "id": ""
        }
    }

    # Trouver la position de content_valign et insérer après
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_valign':
            carousel['sub_fields'].insert(i + 1, content_text_align_field)
            print("✓ Added content_text_align after content_valign")
            break

    # 5. Remplacer btn_text/btn_url/btn_style par repeater buttons dans les slides
    slides_field = None
    for sub_field in carousel['sub_fields']:
        if sub_field.get('name') == 'slides':
            slides_field = sub_field
            break

    if slides_field:
        # Retirer les anciens champs bouton
        slides_field['sub_fields'] = [
            f for f in slides_field['sub_fields']
            if f.get('name') not in ['btn_text', 'btn_url', 'btn_style']
        ]

        # Ajouter repeater buttons
        buttons_field = {
            "key": "field_slide_buttons",
            "label": "Boutons",
            "name": "buttons",
            "type": "repeater",
            "layout": "table",
            "button_label": "Ajouter un bouton",
            "sub_fields": [
                {
                    "key": "field_slide_button_link",
                    "label": "Lien",
                    "name": "link",
                    "type": "link",
                    "return_format": "array"
                },
                {
                    "key": "field_slide_button_type",
                    "label": "Type",
                    "name": "btn_type",
                    "type": "select",
                    "choices": {
                        "primary": "Primary",
                        "secondary": "Secondary"
                    },
                    "default_value": "primary"
                },
                {
                    "key": "field_slide_button_outline",
                    "label": "Outline",
                    "name": "btn_outline",
                    "type": "true_false",
                    "default_value": 0,
                    "ui": 1
                },
                {
                    "key": "field_slide_button_text_color",
                    "label": "Couleur du texte",
                    "name": "btn_text_color",
                    "type": "select",
                    "choices": {
                        "": "Défaut",
                        "light": "Light",
                        "dark": "Dark"
                    },
                    "default_value": ""
                }
            ]
        }

        slides_field['sub_fields'].append(buttons_field)
        print("✓ Replaced btn_text/btn_url/btn_style with buttons repeater")

    # Sauvegarder le fichier
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ group_flexilder.json updated successfully")
    return True

if __name__ == '__main__':
    success = update_carousel_block()
    sys.exit(0 if success else 1)
