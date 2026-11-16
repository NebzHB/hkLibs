<?php
// Usage : php test/hapNames.php — tables de noms HomeKit (sans Jeedom : __() renvoie le texte)
if (!function_exists('__')) { function __($text, $file = '') { return $text; } }
require __DIR__ . '/../hapNames.class.php';

$fails = 0;
function check($what, $got, $want) {
	global $fails;
	if ($got === $want) { echo "ok   $what\n"; return; }
	$fails++;
	echo "FAIL $what\n  attendu : " . var_export($want, true) . "\n  obtenu  : " . var_export($got, true) . "\n";
}
check('UUID court', hapNames::characteristic('B0'), 'Actif');
check('UUID long minuscule', hapNames::service('00000113-0000-1000-8000-0026bb765291'), 'Haut-parleur');
check('On = État', hapNames::characteristic('25'), 'État');
check('valeurs traduites', hapNames::values('E8'), [0 => 'Non détectable', 1 => 'Toujours détectable']);
check('valeurs hors HAP-NodeJS', hapNames::values('36158AC8-5191-4AE2-9EF5-1D6722E88E3D'), [0 => 'Trop bas', 1 => 'OK']);
check('pas de valeurs', hapNames::values('25'), null);
check('UUID inconnu', hapNames::characteristic('E863F10D-079E-48FF-8F27-9C2605A29F52'), null);
check('expand', hapNames::expand('8c'), '0000008C-0000-1000-8000-0026BB765291');

if ($fails) { echo "$fails test(s) en échec\n"; exit(1); }
echo "hapNames : tout est bon\n";
