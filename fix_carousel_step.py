#!/usr/bin/env python3
"""
Corriger step de content_padding : 2 au lieu de 5
"""

import json
import sys

def fix_carousel_step():
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

    # Corriger content_padding step
    for i, sub_field in enumerate(carousel['sub_fields']):
        if sub_field.get('name') == 'content_padding':
            carousel['sub_fields'][i]['step'] = 2
            print("✓ Changed content_padding step from 5 to 2")
            break

    # Sauvegarder
    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

    print("\n✅ Carousel content_padding step fixed")
    return True

if __name__ == '__main__':
    success = fix_carousel_step()
    sys.exit(0 if success else 1)
