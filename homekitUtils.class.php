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
	
	public static function HStoHTML($hue, $saturation) {
		$brightness=100;
		
		$hue /= 60;
		$saturation /= 100;
		$brightness /= 100;
		
		$i = floor($hue);
		$f = $hue - $i;
		
		$p = $brightness * (1 - $saturation);
		$q = $brightness * (1 - $saturation * $f);
		$t = $brightness * (1 - $saturation * (1 - $f));
		
		switch ($i) {
		case 0:
		    $r = $brightness;
		    $g = $t;
		    $b = $p;
		    break;
		case 1:
		    $r = $q;
		    $g = $brightness;
		    $b = $p;
		    break;
		case 2:
		    $r = $p;
		    $g = $brightness;
		    $b = $t;
		    break;
		case 3:
		    $r = $p;
		    $g = $q;
		    $b = $brightness;
		    break;
		case 4:
		    $r = $t;
		    $g = $p;
		    $b = $brightness;
		    break;
		default:
		    $r = $brightness;
		    $g = $p;
		    $b = $q;
		    break;
		}
		
		$r = round($r * 255);
		$g = round($g * 255);
		$b = round($b * 255);
		
		return sprintf("#%02x%02x%02x", $r, $g, $b);
	}
	
	public static function HTMLtoHS($html)  {
		if($html[0] != "#") { $html="#".$html; }
		list($r, $g, $b) = sscanf($html, "#%02x%02x%02x");	
		$r /= 255.0;
		$g /= 255.0;
		$b /= 255.0;
		
		$max = max($r, $g, $b);
		$min = min($r, $g, $b);
		$delta = $max - $min;
		
		$brightness = $max;
		$saturation = ($max != 0) ? ($delta / $max) : 0;
		
		if ($saturation == 0) {
			$hue = 0;
		} else {
			if ($r == $max) {
			    $hue = ($g - $b) / $delta;
			} elseif ($g == $max) {
			    $hue = 2 + ($b - $r) / $delta;
			} else {
			    $hue = 4 + ($r - $g) / $delta;
			}
			$hue *= 60;
			if ($hue < 0) {
			    $hue += 360;
			}
		}
		
		return array(round($hue), round($saturation * 100), round($brightness * 100));
	}
	/***************************Instance Methods************************/
}
