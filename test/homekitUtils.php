<?php
// Usage : php test/homekitUtils.php — tests des fonctions de homekitUtils (sans Jeedom)
require __DIR__ . '/../homekitUtils.class.php';

$fails = 0;
function check($what, $got, $want) {
	global $fails;
	if ($got === $want) { echo "ok   $what\n"; return; }
	$fails++;
	echo "FAIL $what\n  attendu : " . var_export($want, true) . "\n  obtenu  : " . var_export($got, true) . "\n";
}
// référence : formule HSV → RGB de Wikipédia, en flottants
function refRGB($h, $s) {
	$h = fmod($h, 360); if ($h < 0) $h += 360;
	$c = $s / 100; $x = $c * (1 - abs(fmod($h / 60, 2) - 1)); $m = 1 - $c;
	$t = [[$c, $x, 0], [$x, $c, 0], [0, $c, $x], [0, $x, $c], [$x, 0, $c], [$c, 0, $x]][intdiv((int) floor($h), 60)];
	return [($t[0] + $m) * 255, ($t[1] + $m) * 255, ($t[2] + $m) * 255];
}

// HStoHTML
check('rouge', homekitUtils::HStoHTML(0, 100), '#ff0000');
check('vert', homekitUtils::HStoHTML(120, 100), '#00ff00');
check('bleu', homekitUtils::HStoHTML(240, 100), '#0000ff');
check('360 = rouge (était magenta ou noir)', homekitUtils::HStoHTML(360, 100), '#ff0000');
check('360 pastel', homekitUtils::HStoHTML(360, 50), homekitUtils::HStoHTML(0, 50));
check('teinte négative', homekitUtils::HStoHTML(-30, 100), homekitUtils::HStoHTML(330, 100));
check('saturation bornée', homekitUtils::HStoHTML(0, 150), '#ff0000');
check('saturation 0 = blanc', homekitUtils::HStoHTML(200, 0), '#ffffff');
check('valeurs non numériques', homekitUtils::HStoHTML(null, ''), '#ffffff');
$worst = 0;
for ($h = 0; $h <= 360; $h += 0.25) {
	for ($s = 0; $s <= 100; $s++) {
		$ref = refRGB($h, $s);
		$got = homekitUtils::HTMLtoRGB(homekitUtils::HStoHTML($h, $s));
		for ($i = 0; $i < 3; $i++) $worst = max($worst, abs($got[$i] - $ref[$i]));
	}
}
check('HStoHTML = formule de référence (arrondi près)', $worst <= 0.5 + 1e-9, true);

// HTMLtoRGB / HTMLtoHS
check('lecture #rrggbb', homekitUtils::HTMLtoRGB('#FF8000'), [255, 128, 0]);
check('lecture sans #', homekitUtils::HTMLtoRGB('ff8000'), [255, 128, 0]);
check('lecture courte', homekitUtils::HTMLtoRGB('#fff'), [255, 255, 255]);
check('lecture invalide', homekitUtils::HTMLtoRGB('rouge'), null);
check('HS rouge', homekitUtils::HTMLtoHS('#ff0000'), [0, 100, 100]);
check('HS blanc court (était teinte 4 saturation 100)', homekitUtils::HTMLtoHS('fff'), [0, 0, 100]);
check('HS noir', homekitUtils::HTMLtoHS('#000000'), [0, 0, 0]);
check('HS gris', homekitUtils::HTMLtoHS('#808080'), [0, 0, 50]);
check('HS invalide = blanc', homekitUtils::HTMLtoHS(''), [0, 0, 100]);
check('HS rouge rosé ne donne pas 360', homekitUtils::HTMLtoHS('#ff0001')[0] < 360, true);

// aller-retour Jeedom → HomeKit → Jeedom sur les couleurs du sélecteur (un canal à 255)
mt_srand(42);
$max = 0;
for ($i = 0; $i < 20000; $i++) {
	$c = [mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)];
	$c[mt_rand(0, 2)] = 255;
	list($h, $s) = homekitUtils::HTMLtoHS(vsprintf('#%02x%02x%02x', $c));
	$o = homekitUtils::HTMLtoRGB(homekitUtils::HStoHTML($h, $s));
	$max = max($max, abs($o[0] - $c[0]), abs($o[1] - $c[1]), abs($o[2] - $c[2]));
}
check('aller-retour : écart max 2/255 (précision HomeKit au degré et au %)', $max <= 2, true);

// HomeKit → Jeedom → HomeKit : la couleur produite se relit à l'identique
$stable = true;
for ($h = 0; $h < 360 && $stable; $h++) {
	for ($s = 0; $s <= 100; $s++) {
		$html = homekitUtils::HStoHTML($h, $s);
		list($h2, $s2) = homekitUtils::HTMLtoHS($html);
		if (homekitUtils::HStoHTML($h2, $s2) !== $html) { $stable = false; echo "  instable : $h,$s → $html → $h2,$s2\n"; break; }
	}
}
check('relecture stable de toutes les couleurs HomeKit', $stable, true);

if ($fails) { echo "$fails test(s) en échec\n"; exit(1); }
echo "homekitUtils : tout est bon\n";
