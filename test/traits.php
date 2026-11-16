<?php
// Usage : php test/traits.php — fitName et traits (sans Jeedom : classes eqLogic / cmd simulées)
if (!function_exists('__')) { function __($text, $file = '') { return $text; } }
if (!class_exists('log')) { class log { public static function add($p, $l, $m) {} } }
if (!class_exists('eqLogic')) { class eqLogic {} }
if (!class_exists('cmd')) { class cmd { public $name; public function setName($_name) { $this->name = substr($_name, 0, 127); return $this; } } }
require __DIR__ . '/../homekitEqLogicTrait.class.php';
require __DIR__ . '/../homekitCmdTrait.class.php';

$fails = 0;
function check($what, $got, $want) {
	global $fails;
	if ($got === $want) { echo "ok   $what\n"; return; }
	$fails++;
	echo "FAIL $what\n  attendu : " . var_export($want, true) . "\n  obtenu  : " . var_export($got, true) . "\n";
}

// longueur : 127 octets maximum, sans couper un « é », en gardant la fin entre parenthèses
$long = str_repeat('é', 80) . ' État (Allumé)';
$fit = homekitMapper::fitName($long);
check('nom tronqué à 127 octets', strlen($fit) <= 127, true);
check('nom tronqué en UTF-8 valide', mb_check_encoding($fit, 'UTF-8'), true);
check('fin du nom gardée', substr($fit, -strlen(' (Allumé)')), ' (Allumé)');
check('nom court inchangé', homekitMapper::fitName('État (Info)'), 'État (Info)');
check('place pour _2', strlen(homekitMapper::fitName($long, 125) . '_2') <= 127, true);

// les traits se compilent dans une classe d'équipement et de commande
class testPlugin extends eqLogic { use homekitEqLogicTrait; private static $_freshDescription = false; }
class testPluginCmd extends cmd { use homekitCmdTrait; }
$c = new testPluginCmd();
$c->setName($long);
check('setName de la commande : UTF-8 valide (le cœur coupe à 127 octets)', mb_check_encoding($c->name, 'UTF-8'), true);
check('méthodes de l\'équipement', [method_exists('testPlugin', 'createCmd'), method_exists('testPlugin', 'cmdNameExist'), method_exists('testPlugin', 'nameExists')], [true, true, true]);

if ($fails) { echo "$fails test(s) en échec\n"; exit(1); }
echo "traits : tout est bon\n";
