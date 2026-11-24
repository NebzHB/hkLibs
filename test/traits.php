<?php
// Usage : php test/traits.php — fitName et traits (sans Jeedom : classes eqLogic / cmd simulées)
if (!function_exists('__')) { function __($text, $file = '') { return $text; } }
if (!class_exists('log')) { class log { public static function add($p, $l, $m) {} } }
if (!class_exists('eqLogic')) { class eqLogic {} }
if (!class_exists('cmd')) { class cmd { public $name; public function setName($_name) { $this->name = substr($_name, 0, 127); return $this; } } }
// base simulée : table eqLogic comparée comme la collation utf8mb4_unicode_ci (sans casse ni accents)
if (!class_exists('DB')) {
	class DB {
		const FETCH_TYPE_ROW = 'row';
		public static $eqLogic = [];
		public static $last = '';
		public static function ci($s) { return strtolower(strtr((string) $s, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'É' => 'E', 'à' => 'a', 'ç' => 'c', 'ï' => 'i', 'ô' => 'o'])); }
		public static function Prepare($sql, $values, $type) {
			self::$last = $sql;
			foreach (self::$eqLogic as $row) {
				if (self::ci($row['name']) !== self::ci($values['name'])) continue;
				if (isset($values['object_id']) ? $row['object_id'] != $values['object_id'] : $row['object_id'] !== null) continue;
				if (isset($values['id']) && $row['id'] == $values['id']) continue;
				return ['id' => $row['id']];
			}
			return [];
		}
	}
}
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

// doublons de noms d'équipement : la base compare sans casse ni accents (index unique name, object_id)
DB::$eqLogic = [
	['id' => 1, 'name' => 'Clim Véranda', 'object_id' => null],
	['id' => 2, 'name' => 'Salon', 'object_id' => 5],
	['id' => 3, 'name' => 'Clim veranda_AA:BB', 'object_id' => null],
];
check('accents et casse : doublon vu', testPlugin::nameExists('clim veranda'), true);
check('autre pièce : pas de doublon', testPlugin::nameExists('Salon'), false);
check('même pièce : doublon', testPlugin::nameExists('SALON', 5), true);
check('renommage du même équipement', testPlugin::nameExists('Salon', 5, 2), false);
check('requête comme l\'index', DB::$last, 'SELECT id FROM eqLogic WHERE name=:name AND object_id=:object_id AND id<>:id LIMIT 1');
check('nom libre inchangé', testPlugin::freeEqName('Cuisine', 'AA:BB'), 'Cuisine');
check('nom pris : suffixe, puis _2', testPlugin::freeEqName('Clim veranda', 'AA:BB'), 'Clim veranda_AA:BB_2');
check('nom pris (accents) : suffixe', testPlugin::freeEqName('CLIM VÉRANDA', 'CC:DD'), 'CLIM VÉRANDA_CC:DD');
check('nom long : 127 octets avec le suffixe', strlen(testPlugin::freeEqName(str_repeat('é', 70), 'AA:BB')) <= 127, true);

if ($fails) { echo "$fails test(s) en échec\n"; exit(1); }
echo "traits : tout est bon\n";
