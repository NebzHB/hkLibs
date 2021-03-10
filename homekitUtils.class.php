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
		if(array_key_exists($label,$units)) {
			return $units[$label];
		} else {
			return $label;
		}
	}
	public static function expandUUID($uuid) {
		if(strlen($uuid) > 8) return strtoupper($uuid);	
		$suffix = "-0000-1000-8000-0026BB765291";
		return str_pad(strtoupper($uuid), 8, "0", STR_PAD_LEFT).$suffix;	
	}	
	public static function sanitizeValue($currentValue,$c) {
		$val=0;
		if(!$c) // just return the value if no characteristic
			return $val;
		else
			if(!$c['format']) 
				return $val;

		switch($c['format']) {
				case 'uint8':
				case 'uint16':
				case 'uint32':
				case 'uint64':
					$val = intval($currentValue);
					$val = abs($val); // unsigned
					if(!$val) $val = 0;
					if(isset($c['minValue']) && $val < intval($c['minValue'])) $val = intval($c['minValue']);
					if(isset($c['maxValue']) && $val > intval($c['maxValue'])) $val = intval($c['maxValue']);
				break;
				case 'int':
					$val = intval($currentValue);
					if(!$val) $val = 0;
					if(isset($c['minValue']) && $val < intval($c['minValue'])) $val = intval($c['minValue']);
					if(isset($c['maxValue']) && $val > intval($c['maxValue'])) $val = intval($c['maxValue']);
				break;
				case 'float' :
					$val = self::minStepRound(floatval($currentValue),$c);
					if(!$val) $val = 0.0;
					if(isset($c['minValue']) && $val < floatval($c['minValue'])) $val = floatval($c['minValue']);
					if(isset($c['maxValue']) && $val > floatval($c['maxValue'])) $val = floatval($c['maxValue']);
				break;
				case 'bool' :
					$val = self::toBool($currentValue);
					if(!$val) $val = false;
				break;
				case 'string' :
					//if(!$currentValue)
					$val = strval($currentValue);
					if(!$val) $val = '';
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
		if(isset($prec[1])) $prec=$prec[1];
		else $prec='';
		$prec = strlen($prec);

		if($val) {
			$val = $val * pow(10, $prec);
			$val = round($val,0,PHP_ROUND_HALF_UP); // round to the minStep precision
			$val = $val / pow(10, $prec);
		}
		return $val;
	}
	
	public static function toBool($val) {
		if ($val === 'false' || $val === '0') {
			return false;
		} else {
			return boolval($val);
		}
	}
	
	public static function HStoHTML($iH, $iS) {
		$iV=100;
		if($iH < 0)   $iH = 0;   // Hue:
		if($iH > 360) $iH = 360; //   0-360
		if($iS < 0)   $iS = 0;   // Saturation:
		if($iS > 100) $iS = 100; //   0-100
		if($iV < 0)   $iV = 0;   // Lightness:
		if($iV > 100) $iV = 100; //   0-100

		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness:  0.0-1.0
		$dC = $dV*$dS;   // Chroma:     0.0-1.0
		$dH = $iH/60.0;  // H-Prime:    0.0-6.0
		$dT = $dH;       // Temp variable

		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link

		switch(floor($dH)) {
		    case 0:
			$dR = $dC; $dG = $dX; $dB = 0.0; break;
		    case 1:
			$dR = $dX; $dG = $dC; $dB = 0.0; break;
		    case 2:
			$dR = 0.0; $dG = $dC; $dB = $dX; break;
		    case 3:
			$dR = 0.0; $dG = $dX; $dB = $dC; break;
		    case 4:
			$dR = $dX; $dG = 0.0; $dB = $dC; break;
		    case 5:
			$dR = $dC; $dG = 0.0; $dB = $dX; break;
		    default:
			$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}

		$dM  = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;

		return '#'.sprintf("%02X",round($dR)).sprintf("%02X",round($dG)).sprintf("%02X",round($dB));
	}
	
	public static function HTMLtoHS($html)  {

		if($html[0] != "#") $html="#".$html;
		list($R, $G, $B) = sscanf($html, "#%02x%02x%02x");	

		$HSL = array();

		$var_R = ($R / 255);
		$var_G = ($G / 255);
		$var_B = ($B / 255);

		$var_Min = min($var_R, $var_G, $var_B);
		$var_Max = max($var_R, $var_G, $var_B);
		$del_Max = $var_Max - $var_Min;

		$V = $var_Max;

		if ($del_Max == 0)
		{
			$H = 0;
			$S = 0;
		}
		else
		{
			$S = $del_Max / $var_Max;

			$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

			if      ($var_R == $var_Max) $H = $del_B - $del_G;
			else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

			if ($H<0) $H++;
			if ($H>1) $H--;
		}

		return array( round($H*360),round($S*100) );
	}
	/***************************Instance Methods************************/
}
