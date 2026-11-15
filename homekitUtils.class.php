<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

class homekitUtils {
	/***************************Attributs*******************************/	
	
	/***************************Static Methods**************************/	
	public static function transformUnit($label) {
		$units=[
			'celsius'=>"°C",
			'percentage'=>"%",
			'arcdegree'=>"°",
			'lux'=>"lux",
			'seconds'=>"s"
		];
		return $units[$label] ?? $label;
	}
	public static function expandUUID($uuid) {
		if(strlen($uuid) > 8) { return strtoupper($uuid); }
		$suffix = "-0000-1000-8000-0026BB765291";
		return str_pad(strtoupper($uuid), 8, "0", STR_PAD_LEFT).$suffix;	
	}	
	public static function sanitizeValue($currentValue,$c) {
		$val=0;
		if(!$c) {// just return the value if no characteristic
			return $val;
		} elseif(!$c['format']) { 
			return $val;
		}

		switch($c['format']) {
				case 'uint8':
				case 'uint16':
				case 'uint32':
				case 'uint64':
					$val = intval($currentValue);
					$val = abs($val); // unsigned
					if(!$val) { $val = 0; }
					if(isset($c['minValue']) && $val < intval($c['minValue'])) { $val = intval($c['minValue']); }
					if(isset($c['maxValue']) && $val > intval($c['maxValue'])) { $val = intval($c['maxValue']); }
				break;
				case 'int':
					$val = intval($currentValue);
					if(!$val) $val = 0;
					if(isset($c['minValue']) && $val < intval($c['minValue'])) { $val = intval($c['minValue']); }
					if(isset($c['maxValue']) && $val > intval($c['maxValue'])) { $val = intval($c['maxValue']); }
				break;
				case 'float' :
					$val = self::minStepRound(floatval($currentValue),$c);
					if(!$val) $val = 0.0;
					if(isset($c['minValue']) && $val < floatval($c['minValue'])) { $val = floatval($c['minValue']); }
					if(isset($c['maxValue']) && $val > floatval($c['maxValue'])) { $val = floatval($c['maxValue']); }
				break;
				case 'bool' :
					$val = self::toBool($currentValue);
					if(!$val) { $val = false; }
				break;
				case 'string' :
					//if(!$currentValue)
					$val = strval($currentValue);
					if(!$val) { $val = ''; }
				break;
				case 'tlv8' :
				case 'data' :
				default :
					$val = $currentValue;
				break;
		}
		return $val;
	}
	
	public static function minStepRound($val,$c) {
		if(!isset($c['minStep'])) {
			$c['minStep'] = 1;
		}
		$prec=explode('.',strval($c['minStep']));
		if(isset($prec[1])) { 
			$prec=$prec[1]; 
		} else { 
			$prec=''; 
		}
		$prec = strlen($prec);

		if($val) {
			$val = $val * pow(10, $prec);
			$val = round($val,0,PHP_ROUND_HALF_UP); // round to the minStep precision
			$val = $val / pow(10, $prec);
		}
		return $val;
	}
	
	public static function toBool($val) {
		if($val === 'false' || $val === '0') {
			return false;
		} else {
			return boolval($val);
		}
	}
	
	/**
	 * Teinte (0-360°) et saturation (0-100 %) HomeKit → couleur HTML (#rrggbb), luminosité à 100 %
	 * (la luminosité est une caractéristique séparée). La teinte boucle (360 = 0 = rouge), la saturation est bornée.
	 * Même calcul que homebridge-lib (hb-lib-tools Colour.hsvToRgb).
	 */
	public static function HStoHTML($hue, $saturation) {
		$h = fmod(is_numeric($hue) ? floatval($hue) : 0, 360);
		if ($h < 0) { $h += 360; }
		$s = max(0, min(100, is_numeric($saturation) ? floatval($saturation) : 0)) / 100;
		$channel = function ($n) use ($h, $s) {
			$k = fmod($n + $h / 60, 6);
			return 1 - $s * max(0, min($k, 4 - $k, 1));
		};
		return sprintf('#%02x%02x%02x', round($channel(5) * 255), round($channel(3) * 255), round($channel(1) * 255));
	}

	/**
	 * Couleur HTML → [r, g, b] (0-255), ou null si invalide. Accepte « #rrggbb », « rrggbb », « #rgb », « rgb ».
	 */
	public static function HTMLtoRGB($html) {
		$html = ltrim(trim((string) $html), '#');
		if (strlen($html) == 3) { $html = $html[0] . $html[0] . $html[1] . $html[1] . $html[2] . $html[2]; }
		if (!preg_match('/^[0-9a-f]{6}$/i', $html)) { return null; }
		return array_map('hexdec', str_split($html, 2));
	}

	/**
	 * Couleur HTML → [teinte 0-359, saturation 0-100, luminosité 0-100], en entiers comme HomeKit (pas de 1).
	 * Le couple teinte/saturation retenu est celui qui redonne la couleur la plus proche avec HStoHTML
	 * (au lieu d'arrondir chaque valeur séparément). Couleur invalide → blanc [0, 0, 100].
	 */
	public static function HTMLtoHS($html) {
		$rgb = self::HTMLtoRGB($html);
		if ($rgb === null) { return [0, 0, 100]; }
		list($r, $g, $b) = array_map(function ($v) { return $v / 255; }, $rgb);
		$max = max($r, $g, $b);
		$delta = $max - min($r, $g, $b);
		if ($delta == 0) { return [0, 0, (int) round($max * 100)]; }
		$s = $delta / $max * 100;
		if ($max == $r) {
			$h = fmod(($g - $b) / $delta + 6, 6);
		} elseif ($max == $g) {
			$h = ($b - $r) / $delta + 2;
		} else {
			$h = ($r - $g) / $delta + 4;
		}
		$h *= 60;
		// couleur visée à luminosité 100 % (HStoHTML ne gère pas la luminosité)
		$target = array_map(function ($v) use ($max) { return $v / $max * 255; }, [$r, $g, $b]);
		$best = null;
		foreach (array_unique([floor($h), ceil($h)]) as $hh) {
			foreach (array_unique([floor($s), ceil($s)]) as $ss) {
				$out = self::HTMLtoRGB(self::HStoHTML($hh, $ss));
				$err = 0;
				for ($i = 0; $i < 3; $i++) { $err += ($out[$i] - $target[$i]) ** 2; }
				if ($best === null || $err < $best[0]) { $best = [$err, (int) fmod($hh, 360), (int) $ss]; }
			}
		}
		return [$best[1], $best[2], (int) round($max * 100)];
	}
	/***************************Instance Methods************************/
}
