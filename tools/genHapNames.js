#!/usr/bin/env node
/* Génère hapNames.class.php (racine de hkLibs, copié ensuite dans core/class/ des plugins) : nom lisible de chaque service et caractéristique HomeKit, par UUID,
 * et libellé de chaque valeur possible des caractéristiques à liste de valeurs (0=Non détectable, 1=Toujours détectable…).
 *
 * Usage : node tools/genHapNames.js [dossier de @homebridge/hap-nodejs] [--seed fichier.json] [--seed-values fichier.json]
 *   (lancé chaque mois par .github/workflows/hapnames.yml, qui ouvre une PR si la table change)
 *   - la liste (UUID + nom anglais) vient des définitions de HAP-NodeJS, la bibliothèque de Homebridge ;
 *   - les libellés français déjà présents dans hapNames.class.php sont gardés : on peut les corriger à la main ;
 *   - --seed : fichier JSON { "nom anglais": "libellé français" } pour remplir les entrées absentes ;
 *   - --seed-values : { "consts": { "NOM_DE_CONSTANTE": "libellé français" },
 *     "extra": { "UUID": { "name": "…", "values": { "0": "libellé français" } } } } pour les valeurs ; « extra » ajoute
 *     des caractéristiques absentes de HAP-NodeJS (anciennes ou propriétaires), gardées ensuite telles quelles ;
 *   - une entrée sans libellé français garde le nom anglais, hors de __() et marquée « À TRADUIRE » :
 *     pour la traduire, écrire __("libellé français", __FILE__) à la place de la chaîne.
 * Les libellés sont écrits en dur dans __("…", __FILE__) pour que la CI de traduction (plugins-translations)
 * les trouve et les traduise dans les autres langues.
 */
'use strict';
const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '..', 'hapNames.class.php');

function hapDir(arg) {
	if (arg) { return arg; }
	try {
		return path.dirname(require.resolve('@homebridge/hap-nodejs/package.json'));
	} catch (e) {
		console.error('@homebridge/hap-nodejs introuvable. Par exemple : npm pack @homebridge/hap-nodejs && tar xzf homebridge-hap-nodejs-*.tgz, puis node tools/genHapNames.js package');
		process.exit(1);
	}
}

// Définitions de HAP-NodeJS, en CommonJS (var X = …; X.UUID = "…") ou en module ES (export class X { static UUID = '…' })
function parseDefinitions(dir, file, kind) {
	const src = fs.readFileSync(path.join(dir, 'dist', 'lib', 'definitions', file), 'utf8');
	const re = new RegExp('\\* ' + kind + ' "([^"]+)"[\\s\\S]*?\\*/\\s*(?:var|export class) (\\w+)', 'g');
	const seen = new Set();
	const out = [];
	let m;
	while ((m = re.exec(src))) {
		const u = /UUID = ["']([0-9A-F-]{36})["']/.exec(src.slice(m.index));
		if (!u) { throw new Error(kind + ' ' + m[1] + ' : UUID introuvable'); }
		if (seen.has(u[1])) { continue; } // ex. Speaker et Television Speaker ont le même UUID : le premier gagne
		seen.add(u[1]);
		out.push({uuid: u[1], en: m[1]});
	}
	if (!out.length) { throw new Error(file + ' : aucune définition trouvée, le format de HAP-NodeJS a changé ?'); }
	return out.sort((a, b) => a.en.localeCompare(b.en));
}

// libellés déjà dans le fichier généré : UUID → { label, todo }
function readExisting(file) {
	const known = {};
	if (!fs.existsSync(file)) { return known; }
	const re = /'([0-9A-F-]{36})' => (?:__\("([^"]*)", __FILE__\)|"([^"]*)"),(.*)$/gm;
	let m;
	const src = fs.readFileSync(file, 'utf8');
	while ((m = re.exec(src))) { known[m[1]] = (m[2] !== undefined) ? {label: m[2], todo: false} : {label: m[3], todo: true}; }
	return known;
}

// valeurs nommées de chaque caractéristique (static NOT_DISCOVERABLE = 0; …), en module ES ou en CommonJS
function parseValues(dir) {
	const src = fs.readFileSync(path.join(dir, 'dist', 'lib', 'definitions', 'CharacteristicDefinitions.js'), 'utf8');
	const re = /\* Characteristic "([^"]+)"[\s\S]*?\*\/\s*(?:var|export class) (\w+)/g;
	const seen = new Set();
	const out = [];
	let m;
	while ((m = re.exec(src))) {
		const rest = src.slice(m.index + m[0].length);
		const end = rest.search(/\* Characteristic "/);
		const block = (end >= 0) ? rest.slice(0, end) : rest;
		const u = /UUID = ["']([0-9A-F-]{36})["']/.exec(block);
		if (!u || seen.has(u[1])) { continue; }
		seen.add(u[1]);
		const vals = [];
		const done = new Set();
		const r2 = new RegExp('(?:static |' + m[2] + '\\.)([A-Z0-9_]+) = (-?\\d+);', 'g');
		let v;
		while ((v = r2.exec(block))) {
			if (v[1] === 'UUID' || done.has(v[2])) { continue; } // deux noms pour une valeur : le premier gagne
			done.add(v[2]);
			vals.push({v: Number(v[2]), cst: v[1]});
		}
		if (vals.length) { out.push({uuid: u[1], en: m[1], vals}); }
	}
	return out.sort((a, b) => a.en.localeCompare(b.en));
}

// blocs de valeurs déjà dans le fichier généré : UUID → { comment, vals: { valeur → { label, todo, cst } } }
function readExistingValues(file) {
	const known = {};
	if (!fs.existsSync(file)) { return known; }
	const src = fs.readFileSync(file, 'utf8');
	const re = /^\t\t\t'([0-9A-F-]{36})' => \[ \/\/ (.*)\n([\s\S]*?)^\t\t\t\],/gm;
	let m;
	while ((m = re.exec(src))) {
		const vals = {};
		const r2 = /^\t\t\t\t(-?\d+) => (?:__\("([^"]*)", __FILE__\)|"([^"]*)"), \/\/ (\S+)/gm;
		let v;
		while ((v = r2.exec(m[3]))) { vals[v[1]] = (v[2] !== undefined) ? {label: v[2], todo: false, cst: v[4]} : {label: v[3], todo: true, cst: v[4]}; }
		known[m[1]] = {comment: m[2], vals};
	}
	return known;
}

function humanize(cst) {
	return cst.replace(/^_/, '').toLowerCase().split('_').map((w) => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
}

function valueLine(v, label, cst, todo, where) {
	if (todo) { return '\t\t\t\t' + v + ' => "' + phpLabel(label, where) + '", // ' + cst + ' — À TRADUIRE : mettre le libellé français dans __()'; }
	return '\t\t\t\t' + v + ' => __("' + phpLabel(label, where) + '", __FILE__), // ' + cst;
}

function valueEntries(list, existing, seed) {
	const todo = [];
	const blocks = list.map(({uuid, en, vals}) => {
		const old = (existing[uuid] || {vals: {}}).vals;
		const lines = vals.map(({v, cst}) => {
			if (old[v] && !old[v].todo && old[v].cst === cst) { return valueLine(v, old[v].label, cst, false, en); }
			if ((seed.consts || {})[cst]) { return valueLine(v, seed.consts[cst], cst, false, en); }
			todo.push(en + ' ' + v + '=' + cst);
			return valueLine(v, humanize(cst), cst, true, en);
		});
		return "\t\t\t'" + uuid + "' => [ // " + en + '\n' + lines.join('\n') + '\n\t\t\t],';
	});
	// absentes de HAP-NodeJS (anciennes ou propriétaires) : gardées telles quelles, ou prises dans --seed-values
	const known = new Set(list.map((c) => c.uuid));
	const extra = [];
	for (const [uuid, e] of Object.entries(existing)) {
		if (known.has(uuid)) { continue; }
		known.add(uuid);
		extra.push("\t\t\t'" + uuid + "' => [ // " + e.comment + '\n' + Object.entries(e.vals).map(([v, x]) => valueLine(v, x.label, x.cst, x.todo, uuid)).join('\n') + '\n\t\t\t],');
	}
	for (const [uuid, e] of Object.entries(seed.extra || {})) {
		if (known.has(uuid)) { continue; }
		extra.push("\t\t\t'" + uuid + "' => [ // " + e.name + ' (hors HAP-NodeJS)\n' + Object.entries(e.values).map(([v, label]) => valueLine(v, label, 'V' + v, false, uuid)).join('\n') + '\n\t\t\t],');
	}
	return {lines: blocks.concat(extra).join('\n'), count: blocks.length + extra.length, todo};
}

function phpLabel(label, en) {
	if (/["$\\]/.test(label)) { throw new Error('Libellé de « ' + en + ' » : pas de " $ ou \\ (chaîne PHP entre guillemets doubles)'); }
	return label;
}

function entries(list, existing, seed) {
	const todo = [];
	const lines = list.map(({uuid, en}) => {
		let label = en;
		let isTodo = true;
		if (existing[uuid] && !existing[uuid].todo) { label = existing[uuid].label; isTodo = false; } else if (seed[en]) { label = seed[en]; isTodo = false; }
		if (isTodo) { todo.push(en); }
		// pas encore traduite : hors de __(), pour que la CI de traduction ne traite pas l'anglais comme du français
		if (isTodo) { return "\t\t\t'" + uuid + "' => \"" + phpLabel(label, en) + '", // ' + en + ' — À TRADUIRE : mettre le libellé français dans __()'; }
		return "\t\t\t'" + uuid + "' => __(\"" + phpLabel(label, en) + '", __FILE__), // ' + en;
	});
	return {lines: lines.join('\n'), todo};
}

function main() {
	const args = process.argv.slice(2);
	const seedIdx = args.indexOf('--seed');
	const seed = (seedIdx >= 0) ? JSON.parse(fs.readFileSync(args.splice(seedIdx, 2)[1], 'utf8')) : {};
	const seedValIdx = args.indexOf('--seed-values');
	const seedValues = (seedValIdx >= 0) ? JSON.parse(fs.readFileSync(args.splice(seedValIdx, 2)[1], 'utf8')) : {};
	const dir = hapDir(args[0]);
	const version = JSON.parse(fs.readFileSync(path.join(dir, 'package.json'), 'utf8')).version;
	const existing = readExisting(OUT);
	const s = entries(parseDefinitions(dir, 'ServiceDefinitions.js', 'Service'), existing, seed);
	const c = entries(parseDefinitions(dir, 'CharacteristicDefinitions.js', 'Characteristic'), existing, seed);
	const v = valueEntries(parseValues(dir), readExistingValues(OUT), seedValues);

	const php = `<?php
/* Fichier généré par tools/genHapNames.js de NebzHB/hkLibs depuis @homebridge/hap-nodejs ${version}.
 * Nom lisible de chaque service et caractéristique HomeKit, et des valeurs possibles, par UUID. Les libellés peuvent être corrigés ici :
 * le script les garde quand on le relance. Ils sont écrits en dur dans __() pour que la CI de traduction de chaque plugin les trouve.
 * Dans un plugin, ce fichier est une copie : le modifier dans NebzHB/hkLibs.
 */
class hapNames {
	const BASE = '-0000-1000-8000-0026BB765291';

	public static function service($uuid) {
		return self::services()[self::expand($uuid)] ?? null;
	}

	public static function characteristic($uuid) {
		return self::characteristics()[self::expand($uuid)] ?? null;
	}

	// libellés des valeurs possibles d'une caractéristique : [valeur => libellé], ou null
	public static function values($uuid) {
		return self::valueLabels()[self::expand($uuid)] ?? null;
	}

	// toutes les listes de valeurs, par UUID (même forme que homekitEnums::$_homekitValues)
	public static function allValues() {
		return self::valueLabels();
	}

	// UUID court (« 8C ») ou long, en majuscules
	public static function expand($uuid) {
		$uuid = strtoupper(trim((string) $uuid));
		if (preg_match('/^[0-9A-F]{1,8}$/', $uuid)) return str_pad($uuid, 8, '0', STR_PAD_LEFT) . self::BASE;
		return $uuid;
	}

	private static function services() {
		static $names = null;
		if ($names === null) $names = [
${s.lines}
		];
		return $names;
	}

	private static function characteristics() {
		static $names = null;
		if ($names === null) $names = [
${c.lines}
		];
		return $names;
	}

	private static function valueLabels() {
		static $values = null;
		if ($values === null) $values = [
${v.lines}
		];
		return $values;
	}
}
`;
	// seulement la version de HAP-NodeJS qui change : on garde le fichier tel quel (pas de PR pour rien en CI)
	const body = (txt) => txt.replace(/^\/\* Fichier généré .*$/m, '');
	const unchanged = fs.existsSync(OUT) && body(fs.readFileSync(OUT, 'utf8')) === body(php);
	if (!unchanged) { fs.writeFileSync(OUT, php); }
	const todo = s.todo.concat(c.todo, v.todo);
	console.log(path.relative(process.cwd(), OUT) + (unchanged ? ' inchangé' : ' mis à jour') + ' : ' + (s.lines.split('\n').length) + ' services, ' + (c.lines.split('\n').length) + ' caractéristiques, ' + v.count + ' listes de valeurs (HAP-NodeJS ' + version + ')');
	if (todo.length) { console.log('À traduire (' + todo.length + ') : ' + todo.join(', ')); }
}

main();
