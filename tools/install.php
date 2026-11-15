<?php
/* Copie les classes de hkLibs dans un plugin Jeedom, ou vérifie que la copie est intacte.
 *
 * Usage : php tools/install.php <dossier de hkLibs> <dossier du plugin> [--check]
 *
 * Chaque *.php de la racine de hkLibs est copié dans <plugin>/core/class/, dans le namespace <id du plugin>\hkLibs
 * (id lu dans plugin_info/info.json) : deux plugins peuvent ainsi avoir des versions différentes de hkLibs
 * dans le même processus PHP sans conflit de classe. Le plugin utilise les classes avec
 *   require_once __DIR__ . '/homekitUtils.class.php';
 *   use monPlugin\hkLibs\homekitUtils;
 * Les fichiers des libellés __() gardent leur chemin dans le plugin : la CI de traduction du plugin les traduit.
 * Le commit de hkLibs copié est noté dans core/class/hkLibs.version.
 *
 * --check : ne copie rien, compare les fichiers du plugin à ce que donnerait la copie (code de sortie 1 si différent).
 * --i18n-from <dossier d'un autre plugin> : cherche aussi les traductions manquantes dans le core/i18n de cet autre plugin.
 *
 * À la copie, les textes __() des fichiers de hkLibs déjà traduits ailleurs dans core/i18n (même texte français,
 * sous un autre fichier, ou dans le plugin --i18n-from) sont repris pour le fichier copié : pas de nouvelle
 * traduction DeepL quand du code passe d'un plugin à hkLibs.
 */
if ($argc < 3) {
	fwrite(STDERR, "Usage : php tools/install.php <dossier de hkLibs> <dossier du plugin> [--check]\n");
	exit(2);
}
$lib = rtrim($argv[1], '/');
$plugin = rtrim($argv[2], '/');
$check = in_array('--check', $argv);

$info = json_decode((string) @file_get_contents($plugin . '/plugin_info/info.json'), true);
$id = $info['id'] ?? '';
if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $id)) {
	fwrite(STDERR, "Id du plugin introuvable dans $plugin/plugin_info/info.json\n");
	exit(2);
}

function hkLibsCopy($src, $id) {
	$php = file_get_contents($src);
	if (strpos($php, "<?php\n") !== 0) {
		throw new Exception(basename($src) . " doit commencer par « <?php » suivi d'un retour à la ligne");
	}
	return "<?php\nnamespace $id\\hkLibs; // copie de NebzHB/hkLibs : la modifier là-bas, pas ici\n" . substr($php, 6);
}

$files = glob($lib . '/*.php');
if (!$files) {
	fwrite(STDERR, "Aucun fichier PHP dans $lib\n");
	exit(2);
}
$bad = 0;
foreach ($files as $src) {
	$dest = $plugin . '/core/class/' . basename($src);
	$want = hkLibsCopy($src, $id);
	if ($check) {
		if (!file_exists($dest) || file_get_contents($dest) !== $want) {
			echo "::error file=core/class/" . basename($src) . "::diffère de NebzHB/hkLibs : modifiez le fichier dans hkLibs puis lancez le workflow sync-hklibs\n";
			$bad++;
		} else {
			echo "ok   core/class/" . basename($src) . "\n";
		}
	} else {
		file_put_contents($dest, $want);
		echo "copié core/class/" . basename($src) . "\n";
	}
}
if (!$check) {
	$from = null;
	$i = array_search('--i18n-from', $argv);
	if ($i !== false && isset($argv[$i + 1])) { $from = rtrim($argv[$i + 1], '/'); }
	hkLibsTranslations($files, $plugin, $id, $from);
}

/** Reprend les traductions connues des textes __() des fichiers copiés. */
function hkLibsTranslations($files, $plugin, $id, $from) {
	foreach (glob($plugin . '/core/i18n/*.json') as $i18nFile) {
		$raw = file_get_contents($i18nFile);
		$i18n = json_decode($raw, true);
		if (!is_array($i18n)) { continue; }
		$known = [];
		$sources = [$i18n];
		if ($from && file_exists($from . '/core/i18n/' . basename($i18nFile))) {
			$sources[] = json_decode(file_get_contents($from . '/core/i18n/' . basename($i18nFile)), true) ?: [];
		}
		foreach ($sources as $src) {
			foreach ($src as $strings) {
				if (!is_array($strings)) { continue; }
				foreach ($strings as $fr => $tr) { if ($tr !== '' && !isset($known[$fr])) { $known[$fr] = $tr; } }
			}
		}
		$added = 0;
		foreach ($files as $f) {
			$key = 'plugins/' . $id . '/core/class/' . basename($f);
			preg_match_all('/__\(\s*(["\'])((?:\\\\.|(?!\1).)*)\1\s*,\s*__FILE__\s*\)/', file_get_contents($f), $m);
			foreach ($m[2] as $k => $text) {
				$text = stripcslashes($text);
				if (isset($i18n[$key][$text]) && $i18n[$key][$text] !== '') { continue; }
				if (!isset($known[$text])) { continue; }
				$i18n[$key][$text] = $known[$text];
				$added++;
			}
		}
		if ($added) {
			ksort($i18n);
			$out = json_encode($i18n, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			file_put_contents($i18nFile, $out . ((substr($raw, -1) === "\n") ? "\n" : ''));
			echo "traductions reprises : $added dans " . basename($i18nFile) . "\n";
		}
	}
}

if (!$check) {
	$sha = trim((string) @shell_exec('git -C ' . escapeshellarg($lib) . ' rev-parse HEAD 2>/dev/null'));
	if ($sha !== '') {
		file_put_contents($plugin . '/core/class/hkLibs.version', $sha . "\n");
		echo "hkLibs $sha\n";
	}
}
exit($bad ? 1 : 0);
