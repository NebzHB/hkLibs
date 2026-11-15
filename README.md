# hkLibs

Code PHP commun aux plugins Jeedom **hkControl** et **reverseHomebridge** (HomeKit / HAP).

| Fichier | Contenu |
|---|---|
| `homekitUtils.class.php` | Conversions de valeurs : unités, UUID, bornes min/max/pas, couleurs HomeKit (teinte/saturation) ↔ HTML |
| `homekitEnums.class.php` | Libellés techniques des services et caractéristiques, valeurs possibles |
| `hapNames.class.php` | Noms traduits des services, caractéristiques et valeurs possibles, par UUID (généré depuis HAP-NodeJS) |

Les fichiers sont **copiés** dans `core/class/` de chaque plugin (les archives du market n'incluent pas les sous-modules git).
Ne les modifiez pas dans les plugins : modifiez-les ici.

- Chaque plugin a un workflow `sync-hklibs` (chaque semaine et à la demande) qui copie les `*.php` de la racine
  dans `core/class/`, note le commit dans `core/class/hkLibs.version` et ouvre une PR vers `beta`.
- La CI des plugins vérifie que les copies sont identiques à hkLibs au commit noté.
- Les libellés `__()` sont traduits par la CI de traduction de chaque plugin.

`hapNames.class.php` est généré par `tools/genHapNames.js` (workflow `hapnames`, le 1er de chaque mois).
Les libellés français sont gardés quand on le relance ; on peut les corriger à la main.

Tests : `for t in test/*.php; do php "$t"; done`
