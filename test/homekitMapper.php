<?php
// Usage : php test/homekitMapper.php [--update] — conversion description HomeKit → commandes Jeedom
// (sans Jeedom : __() renvoie le texte, log est simulé). --update réécrit les résultats attendus (fixtures/*.expected.json).
if (!function_exists('__')) { function __($text, $file = '') { return $text; } }
if (!class_exists('log')) { class log { public static $lines = []; public static function add($p, $l, $m) { self::$lines[] = "$p $l $m"; } } }
require __DIR__ . '/../homekitMapper.class.php';

$fails = 0;
function check($what, $got, $want) {
	global $fails;
	if ($got === $want) { echo "ok   $what\n"; return; }
	$fails++;
	echo "FAIL $what\n  attendu : " . var_export($want, true) . "\n  obtenu  : " . var_export($got, true) . "\n";
}
function names($r) { return array_column($r['cmds'], 'name', 'logicalId'); }
function byName($r, $name) { foreach ($r['cmds'] as $c) if ($c['name'] === $name) return $c; return null; }

$desc = json_decode(file_get_contents(__DIR__ . '/fixtures/synthetic.json'), true);
$acc = [];
foreach ($desc['accessories'] as $a) $acc[$a['aid']] = $a;
$opts = ['plugin' => 'test', 'protectedConf' => ['aid', 'type']];
$legacyOpts = $opts + ['legacy' => ['off' => ' (Eteint)']];

// TV + haut-parleur : le téléviseur garde les noms courts, le haut-parleur est préfixé
$tv = homekitMapper::accessory($acc[2], $opts);
check('nom de l\'accessoire', $tv['name'], 'TV Salon');
check('TV : Actif (Activer)', byName($tv, 'Actif (Activer)')['configuration']['valueToSet'] ?? null, 1);
check('TV : Actif (Désactiver)', byName($tv, 'Actif (Désactiver)')['configuration']['valueToSet'] ?? null, 0);
check('haut-parleur préfixé', byName($tv, 'Haut-parleur Actif (Activer)') !== null, true);
check('Actif info binaire avec libellés', [byName($tv, 'Actif (Info)')['subtype'], byName($tv, 'Actif (Info)')['configuration']['possibleValues']], ['binary', '0=Inactif<br/>1=Actif']);
check('liste déroulante traduite', byName($tv, 'Touche de télécommande')['configuration']['listValue'] ?? null, '0|Retour rapide;1|Avance rapide;2|Piste suivante;3|Piste précédente;4|Flèche haut;5|Flèche bas;6|Flèche gauche;7|Flèche droite;8|Sélection;9|Retour;10|Quitter;11|Lecture/Pause;15|Infos');
check('fiche : valeur nettoyée', array_column($tv['conf'], 1, 0)['manufacturer'] ?? null, 'Acme Corp');
check('identify', byName($tv, 'Identifier')['configuration']['aidiid'] ?? null, '2.2');

// lampe : couleur, préfixe sans le nom de l'accessoire
$lamp = homekitMapper::accessory($acc[3], $opts);
check('État (Allumé) / (Éteint)', [byName($lamp, 'État (Allumé)') !== null, byName($lamp, 'État (Éteint)') !== null], [true, true]);
check('interrupteur : préfixe raccourci', byName($lamp, 'Lumineux État (Allumé)') !== null && byName($lamp, 'Veilleuse État (Éteint)') !== null, true);
check('couleur info (teinte 360 = rouge)', byName($lamp, 'Couleur (Info)')['value'] ?? null, '#ff0000');
check('couleur action', [byName($lamp, 'Couleur (Action)')['subtype'], byName($lamp, 'Couleur (Action)')['configuration']['aidiid']], ['color', '3.14|3.15']);

// divers : volet, vanne, valeurs relues, données cachées, caractéristique inconnue nettoyée
$misc = homekitMapper::accessory($acc[4], $opts);
check('état du volet traduit', byName($misc, 'État de la position')['configuration']['possibleValues'] ?? null, '0=En fermeture<br/>1=En ouverture<br/>2=Arrêté');
check('bool relu : On / Off', [byName($misc, 'État (Allumé)') !== null, byName($misc, 'État (Info relue)')['type'] ?? null], [true, 'info']);
check('caractéristique inconnue nettoyée', byName($misc, 'Puissance') !== null, true);
check('3 états : Stop au milieu', byName($misc, 'Accès administrateur uniquement (Stop)')['configuration']['valueToSet'] ?? null, 50);
check('tlv8 caché', byName($misc, 'Configuration des points de terminaison (Action)')['isVisible'] ?? null, 0);

// noms historiques de hkControl (pour retrouver les commandes à renommer) : mêmes logicalId
$old = homekitMapper::accessory($acc[3], $legacyOpts);
check('historique : mêmes commandes', array_keys(names($old)), array_keys(names($lamp)));
check('historique : noms anglais sans préfixe', array_values(array_intersect_key(names($old), array_flip(['10_00000043-0000-1000-8000-0026BB765291_00000025-0000-1000-8000-0026BB765291_ON', '10_00000043-0000-1000-8000-0026BB765291_00000025-0000-1000-8000-0026BB765291_ON_OFF', '20_00000049-0000-1000-8000-0026BB765291_00000025-0000-1000-8000-0026BB765291_ON']))), ['On (Allumé)', 'On (Eteint)', 'On (Allumé)']);
check('historique : Color', byName($old, 'Color (Info)') !== null, true);

// résultat complet figé : toute différence doit être voulue (php test/homekitMapper.php --update)
$all = ['nouveaux noms' => [], 'noms historiques' => []];
foreach ($acc as $aid => $a) {
	$all['nouveaux noms'][$aid] = homekitMapper::accessory($a, $opts);
	$all['noms historiques'][$aid] = homekitMapper::accessory($a, $legacyOpts);
}
$file = __DIR__ . '/fixtures/synthetic.expected.json';
$json = json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
if (in_array('--update', $argv)) file_put_contents($file, $json);
check('résultat identique à fixtures/synthetic.expected.json', file_exists($file) && file_get_contents($file) === $json, true);

if ($fails) { echo "$fails test(s) en échec\n"; exit(1); }
echo "homekitMapper : tout est bon\n";
