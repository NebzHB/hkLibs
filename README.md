# hkLibs

Code PHP commun aux plugins Jeedom **hkControl** et **reverseHomebridge** (HomeKit / HAP).

| Fichier | Contenu |
|---|---|
| `homekitUtils.class.php` | Conversions de valeurs : unités, UUID, bornes min/max/pas, couleurs HomeKit (teinte/saturation) ↔ HTML |
| `homekitEnums.class.php` | Libellés techniques des services et caractéristiques, valeurs possibles |

Les fichiers sont **copiés** dans `core/class/` de chaque plugin (les archives du market n'incluent pas les sous-modules git).
Ne les modifiez pas dans les plugins : modifiez-les ici.

Tests : `for t in test/*.php; do php "$t"; done`
