# hkLibs

Code PHP commun aux plugins Jeedom **hkControl** et **reverseHomebridge** (HomeKit / HAP).

| Fichier | Contenu |
|---|---|
| `homekitUtils.class.php` | Conversions de valeurs : unités, UUID, bornes min/max/pas, couleurs HomeKit (teinte/saturation) ↔ HTML |
| `homekitEnums.class.php` | Libellés techniques des services et caractéristiques, valeurs possibles |
| `homekitMapper.class.php` | Description HomeKit d'un accessoire → commandes Jeedom (noms traduits, préfixes de service, couleur, listes) |
| `homekitEqLogicTrait.class.php` | Trait des équipements : `createCmd`, `cmdNameExist`, `nameExists` |
| `homekitCmdTrait.class.php` | Trait des commandes : `setName` qui coupe proprement à 127 octets |
| `hapNames.class.php` | Noms traduits des services, caractéristiques et valeurs possibles, par UUID (généré depuis HAP-NodeJS) |

Les fichiers sont **copiés** dans `core/class/` de chaque plugin (les archives du market n'incluent pas les sous-modules git).
Ne les modifiez pas dans les plugins : modifiez-les ici.

- Chaque plugin a un workflow `sync-hklibs` (chaque semaine et à la demande) qui lance
  `php tools/install.php <hkLibs> <plugin>` : copie des `*.php` de la racine dans `core/class/`, dans le namespace
  `<id du plugin>\hkLibs`, commit noté dans `core/class/hkLibs.version`, puis PR vers `beta`.
- Le namespace évite les conflits quand les deux plugins, avec des versions différentes, tournent dans le même
  processus PHP. Dans le plugin : `require_once __DIR__ . '/homekitUtils.class.php';` puis `use monPlugin\hkLibs\homekitUtils;`.
  Le code de hkLibs écrit les classes de Jeedom avec un `\` devant (`\log::add`, `\cmd::byId`…).
- La CI des plugins vérifie avec `--check` que les copies sont identiques à hkLibs au commit noté.
- Les libellés `__()` sont traduits par la CI de traduction de chaque plugin.

`hapNames.class.php` est généré par `tools/genHapNames.js` (workflow `hapnames`, le 1er de chaque mois).
Les libellés français sont gardés quand on le relance ; on peut les corriger à la main.

Tests : `for t in test/*.php; do php "$t"; done`
