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
	$sha = trim((string) @shell_exec('git -C ' . escapeshellarg($lib) . ' rev-parse HEAD 2>/dev/null'));
	if ($sha !== '') {
		file_put_contents($plugin . '/core/class/hkLibs.version', $sha . "\n");
		echo "hkLibs $sha\n";
	}
}
exit($bad ? 1 : 0);
