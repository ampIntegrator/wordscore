#!/usr/bin/env python3
"""
Corriger content_bg_custom : color_picker au lieu de text
Uniformisation avec le reste du projet
"""

import json
import sys

def fix_carousel_colorpicker():
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

    # Corriger content_bg_custom
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_bg_custom':
            carousel['sub_fields'][i] = {
                "key": "field_carousel_content_bg_custom",
                "label": "Couleur personnalisée",
                "name": "content_bg_custom",
                "type": "color_picker",
                "conditional_logic": [
                    [
                        {
                            "field": "field_carousel_content_bg",
                            "operator": "==",
                            "value": "custom"
                        }
                    ]
                ],
                "wrapper": {
                    "width": "33",
                    "class": "",
                    "id": ""
                },
                "return_format": "string",
                "enable_opacity": True,
                "return_opacity": True
            }
            print("✓ Fixed content_bg_custom to color_picker with transparency support")
            break

    # Sauvegarder
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ Carousel color_picker fixed")
    return True

if __name__ == '__main__':
    success = fix_carousel_colorpicker()
    sys.exit(0 if success else 1)
