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

/**
 * Conversion de la description HomeKit d'un accessoire (JSON de /accessories) en commandes Jeedom,
 * commune à hkControl et reverseHomebridge. Le plugin garde : d'où vient la description, quels accessoires
 * importer, la configuration de ses équipements et leur création (createEq / createCmd).
 * Dans un plugin, ce fichier est une copie (namespace du plugin) : le modifier dans NebzHB/hkLibs.
 * Les classes de Jeedom s'écrivent avec un \ devant (\log::add) pour fonctionner dans le namespace.
 */
require_once __DIR__ . '/homekitUtils.class.php';
require_once __DIR__ . '/homekitEnums.class.php';
require_once __DIR__ . '/hapNames.class.php';

class homekitMapper {
	/**
	 * Texte venu du réseau (noms de la description d'un accessoire) : sans balises ni caractères dangereux, 120 caractères.
	 */
	public static function cleanNetworkName($name) {
		$name = strip_tags((string) $name);
		$name = str_replace(['<', '>', '"', '`'], '', $name);
		$name = trim(preg_replace('/\s+/u', ' ', $name));
		return mb_substr($name, 0, 120);
	}

	/**
	 * Nom tenant dans la colonne name du cœur (varchar(127), coupé en octets par setName) : on nettoie comme le cœur,
	 * puis on raccourcit le début sans couper un caractère accentué et en gardant la fin « (Info) », « (Allumé) »...
	 */
	public static function fitName($name, $max = 127) {
		$name = (string) $name;
		if (function_exists('cleanComponanteName')) $name = cleanComponanteName($name);
		$name = trim($name);
		if (strlen($name) <= $max) return $name;
		$head = $name;
		$suffix = '';
		if (preg_match('/^(.*\S)(\s\([^()]*\))$/u', $name, $m) && strlen($m[2]) < $max / 2) {
			$head = $m[1];
			$suffix = $m[2];
		}
		return rtrim(mb_strcut($head, 0, $max - strlen($suffix), 'UTF-8')) . $suffix;
	}

	/** Commande « Rafraichir » (noms historiques de hkControl si $legacy). */
	public static function refreshCmd($legacy = false) {
		return ['name' => ($legacy) ? 'Rafraichir' : __('Rafraichir', __FILE__), 'type' => 'action', 'subtype' => 'other', 'display' => ['generic_type' => 'DONT'], 'isVisible' => 1, 'logicalId' => 'refresh'];
	}

	/** Commande « En Ligne ». */
	public static function onlineCmd($legacy = false) {
		return ['name' => ($legacy) ? 'En Ligne' : __('En Ligne', __FILE__), 'type' => 'info', 'subtype' => 'binary', 'isVisible' => 1, 'logicalId' => 'online'];
	}

	/**
	 * Préfixe à mettre devant les commandes d'un service, par iid de service : seulement pour les services
	 * dont une caractéristique existe aussi dans un autre service du même accessoire (TV + haut-parleur,
	 * multiprise, sources d'entrée…). Le service principal garde les noms courts : celui marqué « primary »,
	 * sinon le premier dont le type n'est pas répété. Préfixe = nom propre du service (Configured Name, sinon
	 * Name) s'il diffère de celui de l'accessoire et des autres, sinon le type traduit, numéroté s'il se répète.
	 */
	public static function servicePrefixes(array $services) {
		$skip = [hapNames::expand('3E'), hapNames::expand('A2')]; // informations de l'accessoire, protocole
		$nameType = hapNames::expand('23');
		$configuredType = hapNames::expand('E3');
		$list = [];
		$accName = '';
		foreach ($services as $s) {
			if (!is_array($s) || !isset($s['iid'])) continue;
			$type = hapNames::expand($s['type'] ?? '');
			$names = [];
			$chars = [];
			foreach (($s['characteristics'] ?? []) as $c) {
				$ct = hapNames::expand($c['type'] ?? '');
				if ($ct == $nameType || $ct == $configuredType) $names[$ct] = self::cleanNetworkName($c['value'] ?? '');
				if ($ct != $nameType) $chars[$ct] = true;
			}
			$own = ($names[$configuredType] ?? '') ?: ($names[$nameType] ?? '');
			if ($type == $skip[0]) { $accName = $own; continue; }
			if (in_array($type, $skip)) continue;
			$list[$s['iid']] = ['type' => $type, 'chars' => array_keys($chars), 'own' => $own, 'primary' => !empty($s['primary']), 'label' => $s['typeLabel'] ?? $type];
		}
		$charCount = [];
		$typeCount = [];
		foreach ($list as $s) {
			foreach ($s['chars'] as $ct) $charCount[$ct] = ($charCount[$ct] ?? 0) + 1;
			$typeCount[$s['type']] = ($typeCount[$s['type']] ?? 0) + 1;
		}
		$primary = null;
		foreach ($list as $iid => $s) if ($s['primary']) { $primary = $iid; break; }
		if ($primary === null) foreach ($list as $iid => $s) if ($typeCount[$s['type']] == 1) { $primary = $iid; break; }

		$need = [];
		foreach ($list as $iid => $s) {
			if ($iid === $primary) continue;
			foreach ($s['chars'] as $ct) if ($charCount[$ct] > 1) { $need[] = $iid; break; }
		}
		$norm = function ($n) { return mb_strtolower(trim($n)); };
		// « Lumière zone canapé Lumineux » dans l'accessoire « Lumière zone canapé » : on garde « Lumineux »
		$accLen = mb_strlen($accName);
		foreach ($need as $iid) {
			$own = $list[$iid]['own'];
			if ($accLen && mb_strlen($own) > $accLen && $norm(mb_substr($own, 0, $accLen)) === $norm($accName)) {
				$rest = trim(ltrim(mb_substr($own, $accLen), " -_:"));
				if ($rest !== '') $list[$iid]['own'] = $rest;
			}
		}
		$ownCount = [];
		foreach ($need as $iid) { $n = $norm($list[$iid]['own']); if ($n !== '') $ownCount[$n] = ($ownCount[$n] ?? 0) + 1; }
		$prefixes = [];
		$byLabel = [];
		foreach ($need as $iid) {
			$s = $list[$iid];
			$n = $norm($s['own']);
			if ($n !== '' && $n !== $norm($accName) && $ownCount[$n] == 1) {
				$prefixes[$iid] = $s['own'];
			} else {
				$byLabel[hapNames::service($s['type']) ?? self::cleanNetworkName($s['label'])][] = $iid;
			}
		}
		foreach ($byLabel as $label => $iids) {
			foreach ($iids as $i => $iid) $prefixes[$iid] = (count($iids) > 1) ? $label . ' ' . ($i + 1) : $label;
		}
		return $prefixes;
	}

	/**
	 * Valeurs possibles d'une caractéristique (filtrées par valid-values, minValue, maxValue) : « 0=Inactif<br/>1=Actif »
	 * pour une commande info, « 0|Inactif;1|Actif » pour une liste déroulante.
	 */
	public static function valueList(array $labels, array $c, $glue, $sep) {
		$list = [];
		foreach ($labels as $val => $label) {
			if (isset($c['valid-values']) && !in_array($val, $c['valid-values'])) { continue; }
			if (isset($c['minValue']) && $val < $c['minValue']) { continue; }
			if (isset($c['maxValue']) && $val > $c['maxValue']) { continue; }
			$list[] = $val . $glue . $label;
		}
		return join($sep, $list);
	}

	/**
	 * Commandes d'un accessoire. Retour : ['name' => nom de l'accessoire ou null, 'cmds' => [commandes au format
	 * createCmd], 'conf' => [[clé, valeur]...] (fabricant, modèle... du service « informations »)].
	 * Les commandes Rafraichir / En Ligne ne sont pas incluses (refreshCmd(), onlineCmd()).
	 * $opts :
	 *   - plugin : canal des logs ;
	 *   - protectedConf : clés de configuration que l'accessoire ne peut pas écrire ;
	 *   - legacy : false, ou ['off' => suffixe] pour les noms historiques de hkControl (anglais, sans préfixe),
	 *     utilisés pour retrouver les commandes à renommer.
	 */
	public static function accessory(array $a, array $opts = []) {
		$plugin = $opts['plugin'] ?? 'homekit';
		$protectedConf = $opts['protectedConf'] ?? [];
		$legacy = $opts['legacy'] ?? false;
		$aid = $a['aid'] ?? 0;
		$knownUUID = homekitEnums::$_knownUUID;
		// libellés des valeurs possibles : traduits (hapNames, d'après HAP-NodeJS), sinon ceux de homekitEnums
		$valueLabels = hapNames::allValues() + homekitEnums::$_homekitValues;
		// listes qui décident du type de commande (liste déroulante...) : celles de homekitEnums, comme avant hapNames ;
		// une liste connue seulement de hapNames (Actif, Type d'alarme...) ne donne que des libellés à la commande info,
		// sans changer ses commandes (Actif garde Activer / Désactiver)
		$homekitValues = array_intersect_key($valueLabels, homekitEnums::$_homekitValues);
		$identifyCmd = ['name' => ($legacy) ? 'Identifier' : __('Identifier', __FILE__), 'type' => 'action', 'subtype' => 'other', 'isVisible' => 1, 'logicalId' => 'identify'];
		$thisName = null;
		$cmds = [];
		$conf = [];
		// préfixe de service pour les commandes qui existent dans plusieurs services (multiprise, TV + haut-parleur…)
		$prefixes = self::servicePrefixes($a['services'] ?? []);

		foreach(($a['services'] ?? []) as $s){
			if($s['typeLabel']=='protocol.information.service') continue;
			$LightElement=[];
			// service inconnu : son libellé vient de l'accessoire (caractéristique name) → nettoyé (XSS)
			$ServiceName=((isset($knownUUID['services'][$s['typeLabel']]))?$knownUUID['services'][$s['typeLabel']]:self::cleanNetworkName($s['typeLabel']));
			$ServiceOriginalName=((isset($s['serviceOriginalName']))?self::cleanNetworkName($s['serviceOriginalName']):'unset');
			$ServiceType=homekitUtils::expandUUID($s['type']);
			$prefix=(!$legacy && isset($prefixes[$s['iid']])) ? $prefixes[$s['iid']].' ' : '';
			
			// CHARACTERISTIC
			foreach(($s['characteristics'] ?? []) as $c){
				$infoCmd=null;
				$actionCmd=null;
				$infoManCmd=null;
				$infoCmd=null; // avant : gardait la commande info de la caractéristique précédente
				$CisHidden=in_array('hd',$c['perms']);
				$CisAction=in_array('pw',$c['perms']);
				$CisInfo=in_array('ev',$c['perms']);
				$CisManualInfo=in_array('pr',$c['perms']);
				// caractéristique inconnue : libellé = sa description, fournie par l'accessoire → nettoyé (XSS)
				$CharName=((isset($knownUUID['characteristics'][$c['typeLabel']]))?$knownUUID['characteristics'][$c['typeLabel']]:self::cleanNetworkName($c['typeLabel']));
				$CharType=homekitUtils::expandUUID($c['type']);
				// nom affiché : libellé HomeKit traduit (le libellé technique $CharName sert à la logique)
				$CharLabel=($legacy) ? ucfirst($CharName) : (hapNames::characteristic($CharType) ?? ucfirst($CharName));
				
				if($ServiceName == 'accessory-information') {
					/***
					Create Identify CMD & configuration things
									***/
					switch($CharName) {
						case 'identify':
							if($identifyCmd !== false) {
								$identifyCmd['configuration']=[];
								$identifyCmd['configuration']['aidiid']=$aid.'.'.$c['iid'];
								$identifyCmd['configuration']['service']=$ServiceName;
								$identifyCmd['configuration']['serviceType']=$ServiceType;
								array_push($cmds,$identifyCmd);
							}
							else \log::add($plugin,'warning',__("Commande identify non trouvée", __FILE__));
						break;
						case 'name':
							$thisName=$c['value'];
						break;
						default:
							// affiché dans la fiche (fabricant, modèle, n° de série...) : valeur nettoyée ; clé limitée aux
							// libellés HAP et UUID, pour qu'un accessoire ne puisse pas écrire hbExpose, address...
							if (preg_match('/^([a-z][a-z0-9.\-]*|[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12})$/', $CharName) && !in_array($CharName, $protectedConf)) {
								array_push($conf,[$CharName,(is_string($c['value']) ? self::cleanNetworkName($c['value']) : $c['value'])]);
							} else {
								\log::add($plugin,'debug',__("Information d'accessoire ignorée : ", __FILE__).json_encode($CharName));
							}
						break;
					}
					
				} else {
					/***
					Create other CMD
								***/
					if($CharName == 'name') continue;
					if($CharName == 'hue' || $CharName == 'saturation') {
						if($CharName == 'hue') $LightElement['hue']=$c;
						if($CharName == 'saturation') $LightElement['sat']=$c;
						if(count($LightElement) != 2) {
							continue;
						}
					}
								
					$template=[
						"name"=>$prefix.$CharLabel,
						"logicalId"=>$s['iid'].'_'.$ServiceType.'_'.$CharType,
						"configuration"=>[
							"aidiid"=>$aid.'.'.$c['iid'],
							"service"=>$ServiceName,
							"serviceOriginalName"=>$ServiceOriginalName,
							"serviceType"=>$ServiceType,
							"serviceIID"=>$s['iid'],
							"hkFormat"=>$c['format']
						],
						"display"=>[]
					];
					if($CisHidden || (isset($s['hidden']) && $s['hidden']=='true')) $template['isVisible']=0;
					else $template['isVisible']=1;
					
					if(isset($c['maxValue'])) 		$template['configuration']['maxValue'] = $c['maxValue'];
					if(isset($c['minValue'])) 		$template['configuration']['minValue'] = $c['minValue'];
					if(isset($c['minStep']))  		$template['configuration']['step'] = $c['minStep'];
					if(isset($c['maxLen']))   		$template['configuration']['maxLen'] = $c['maxLen'];
					if(isset($c['valid-values']))	$template['configuration']['valid-values'] = $c['valid-values'];
					if(isset($c['unit']))			$template['unite']=homekitUtils::transformUnit($c['unit']);
					
					/***
					Create INFO CMD
									***/
					if($CisInfo) {
						$infoCmd=$template;
						$infoCmd['logicalId'].='_INFO';
						$infoCmd['name'].=(($CisAction)?" (Info)":'');
						$infoCmd['type']="info";
						
						switch($c['format']) {
							case 'bool':
								$infoCmd['subtype']="binary";
								//$infoCmd['value']=(($c['value']=="1")?1:0);
								$infoCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
							case 'float':
								$infoCmd['subtype']="numeric";
								$infoCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
							case 'uint8':
							case 'uint16':
							case 'uint32':
							case 'uint64':
							case 'int':
								$infoCmd['subtype']="numeric";
								$infoCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
								if ($CharName == 'input-event') {
									$infoCmd['configuration']['repeatEventManagement']='always';
								}
								if(isset($homekitValues[$CharType])) {
									$list=[];
									foreach($homekitValues[$CharType] as $val => $label) {
										if(isset($c['valid-values']) && !in_array($val,$c['valid-values'])) {continue;}
										if(isset($c['minValue']) && $val < $c['minValue']) {continue;}
										if(isset($c['maxValue']) && $val > $c['maxValue']) {continue;}
										array_push($list,$val.'='.$label);
									}
									$infoCmd['configuration']['possibleValues']=join('<br/>',$list);
									if($CharName == "occupancy-detected" && (isset($c['minStep']) && $c['minStep'] == 1) && (isset($c['minValue']) && $c['minValue'] == 0) && (isset($c['maxValue']) && $c['maxValue'] == 1)) {
										$infoCmd['subtype']="binary";
										$infoCmd['value']=((homekitUtils::sanitizeValue($c['value'],$c))?1:0);
									}
								} else {
									if(isset($valueLabels[$CharType])) {
										$infoCmd['configuration']['possibleValues']=self::valueList($valueLabels[$CharType], $c, '=', '<br/>');
									}
									if(isset($c['minStep']) && intval($c['minStep']) !== 0 && isset($c['minValue']) && isset($c['maxValue']) && ((intval($c['maxValue'])-intval($c['minValue']))/intval($c['minStep']) == 1)) {	
										$infoCmd['subtype']="binary";
										$infoCmd['value']=((homekitUtils::sanitizeValue($c['value'],$c) == $c['maxValue'])?true:false);
									} elseif (isset($c['minStep']) && intval($c['minStep']) !== 0 && isset($c['minValue']) && isset($c['maxValue']) && ((intval($c['maxValue'])-intval($c['minValue']))/intval($c['minStep']) == 2)) {
										// si min=0, max=100, step=50 (50 is stop)
										$infoCmd['subtype']="binary";
										$infoCmd['value']=((homekitUtils::sanitizeValue($c['value'],$c) == $c['maxValue'])?true:((homekitUtils::sanitizeValue($c['value'],$c) == $c['minValue'])?false:null));
										if($infoCmd['value'] === null) unset($infoCmd['value']);
									}
								}
							break;
							case 'data':
							case 'tlv8':
								$infoCmd['isVisible']=0;
							case 'string':
								$infoCmd['subtype']="string";
								$infoCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
						}
						if(is_array($LightElement) && count($LightElement) == 2 && ($CharName == 'hue' || $CharName == 'saturation')) {
							$infoCmd['name']=($legacy) ? "Color (Info)" : $prefix.__("Couleur", __FILE__)." (Info)";
							$infoCmd['subtype']="string";

							$infoCmd['logicalId']=$s['iid'].'_'.$ServiceType.'_';
							$aidiids=[];
							ksort($LightElement);
							foreach($LightElement as $le) {
								$infoCmd['logicalId'].=homekitUtils::expandUUID($le['type']).'_';
								array_push($aidiids,$aid.'.'.$le['iid']);
							}
							$infoCmd['logicalId'].="INFO";
							$infoCmd['configuration']["aidiid"]=join('|',$aidiids);
							$infoCmd['value']=homekitUtils::HStoHTML($LightElement['hue']['value'],$LightElement['sat']['value']);
							$infoCmd['configuration']['hkFormat']='hue-saturation';
							unset($infoCmd['configuration']['maxValue']);
							unset($infoCmd['configuration']['minValue']);
							unset($infoCmd['configuration']['step']);
							unset($infoCmd['configuration']['maxLen']);
							unset($infoCmd['configuration']['valid-values']);
						}
						array_push($cmds,$infoCmd);
					}
					
					/***
					Create MANUAL INFO CMD
									***/
					if($CisManualInfo && !$CisInfo) {
						$infoManCmd=$template;
						$infoManCmd['logicalId'].='_MANUALINFO';
						$infoManCmd['name'].=(($CisAction)?(($legacy) ? " (Manual Info)" : __(" (Info relue)", __FILE__)):'');
						$infoManCmd['type']="info";
						
						switch($c['format']) {
							case 'bool':
								$infoManCmd['subtype']="binary";
								//$infoManCmd['value']=(($c['value']=="1")?1:0);
								$infoManCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
							case 'float':
								$infoManCmd['subtype']="numeric";
								$infoManCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
							case 'uint8':
							case 'uint16':
							case 'uint32':
							case 'uint64':
							case 'int':
								$infoManCmd['subtype']="numeric";
								$infoManCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
								
								if(isset($homekitValues[$CharType])) {
									$list=[];
									foreach($homekitValues[$CharType] as $val => $label) {
										if(isset($c['valid-values']) && !in_array($val,$c['valid-values'])) {continue;}
										if(isset($c['minValue']) && $val < $c['minValue']) {continue;}
										if(isset($c['maxValue']) && $val > $c['maxValue']) {continue;}
										array_push($list,$val.'='.$label);
									}
									$infoManCmd['configuration']['possibleValues']=join('<br/>',$list);
								} elseif(isset($valueLabels[$CharType])) {
									$infoManCmd['configuration']['possibleValues']=self::valueList($valueLabels[$CharType], $c, '=', '<br/>');
								}
							break;
							case 'data':
							case 'tlv8':
								$infoManCmd['isVisible']=0;
							case 'string':
								$infoManCmd['subtype']="string";
								$infoManCmd['value']=homekitUtils::sanitizeValue($c['value'],$c);
							break;
						}
						array_push($cmds,$infoManCmd);
					}
					
					/***
					Create ACTION CMD
									***/
					if($CisAction) {
						$actionCmd=$template;
						// commande info liée aux actions secondaires (Eteint, Fermer, Stop, Désactiver) : info par
						// événement, sinon info relue (pr sans ev), sinon aucune
						$linkedLogicalId = ($infoCmd) ? $infoCmd['logicalId'] : (($infoManCmd) ? $infoManCmd['logicalId'] : null);
						
						if($CisInfo) {
							$actionCmd['name'].=" (Action)";
							$actionCmd['value']=$infoCmd['logicalId'];
						}
						if($infoManCmd) {
							$actionCmd['name'].=" (Action)";
							$actionCmd['value']=$infoManCmd['logicalId'];
						}
						$actionCmd['logicalId'].='_ACTION';
						$actionCmd['type']="action";
						
						switch($c['format']) {
							case 'bool':
								// double command on (et off si l'état est lisible : par événement ou relu)
								if($CisInfo || $CisManualInfo) {
									$actionCmd['name']=$template['name'].(($legacy) ? " (Allumé)" : (($CharType == hapNames::expand('25')) ? __(" (Allumé)", __FILE__) : __(" (On)", __FILE__)));
								} else {
									$actionCmd['name']=$template['name']." (Action)";
								}
								$actionCmd['logicalId']=$template['logicalId'].='_ON';
								$actionCmd['subtype']="other";
								$actionCmd['configuration']['valueToSet']=1;
								
								
								// create a second command : off (avant : seulement avec ev, une caractéristique pr+pw ne
								// pouvait jamais être remise à 0)
								if($CisInfo || $CisManualInfo) {
									array_push($cmds,$actionCmd);
									$actionCmd=$template;
									$actionCmd['name'].=($legacy) ? $legacy['off'] : (($CharType == hapNames::expand('25')) ? __(" (Éteint)", __FILE__) : __(" (Off)", __FILE__));
									if ($linkedLogicalId) {$actionCmd['value']=$linkedLogicalId;}
									$actionCmd['logicalId'].='_OFF';
									$actionCmd['type']="action";
									$actionCmd['subtype']="other";
									$actionCmd['configuration']['valueToSet']=0;
								}
								
							break;
							case 'float':
							case 'uint8':
							case 'uint16':
							case 'uint32':
							case 'uint64':
							case 'int':
								if(isset($homekitValues[$CharType])) {
									$actionCmd['subtype']="select";
									$list=[];
									foreach($homekitValues[$CharType] as $val => $label) {
										if(isset($c['valid-values']) && !in_array($val,$c['valid-values'])) {continue;}
										if(isset($c['minValue']) && $val < $c['minValue']) {continue;}
										if(isset($c['maxValue']) && $val > $c['maxValue']) {continue;}
										array_push($list,$val.'|'.$label);
									}
									$actionCmd['configuration']['listValue']=join(';',$list);
								} else {
									if($CharName == "active") {
										$actionCmd['name']=$template['name'].__(" (Activer)", __FILE__);
										$actionCmd['logicalId']=$template['logicalId'].='_Active';
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=1;

										array_push($cmds,$actionCmd);
										$actionCmd=$template;
										$actionCmd['name'].=__(" (Désactiver)", __FILE__);
										if ($linkedLogicalId) {$actionCmd['value']=$linkedLogicalId;}
										$actionCmd['logicalId'].='_Inactive';
										$actionCmd['type']="action";
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=0;
									} elseif(isset($c['minStep']) && intval($c['minStep']) !== 0 && isset($c['minValue']) && isset($c['maxValue']) && ((intval($c['maxValue'])-intval($c['minValue']))/intval($c['minStep']) == 1)) {
										$actionCmd['name']=$template['name'].__(" (Ouvrir)", __FILE__);
										$actionCmd['logicalId']=$template['logicalId'].='_OPEN';
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=$c['maxValue']; // et non 100 : 1/2, 0/2...

										array_push($cmds,$actionCmd);
										$actionCmd=$template;
										$actionCmd['name'].=__(" (Fermer)", __FILE__);
										if ($linkedLogicalId) {$actionCmd['value']=$linkedLogicalId;}
										$actionCmd['logicalId'].='_CLOSE';
										$actionCmd['type']="action";
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=$c['minValue'];
									} elseif(isset($c['minStep']) && intval($c['minStep']) !== 0 && isset($c['minValue']) && isset($c['maxValue']) && ((intval($c['maxValue'])-intval($c['minValue']))/intval($c['minStep']) == 2)) {
										$actionCmd['name']=$template['name'].__(" (Ouvrir)", __FILE__);
										$actionCmd['logicalId']=$template['logicalId'].='_OPEN';
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=$c['maxValue']; // et non 100 : 1/2, 0/2...

										array_push($cmds,$actionCmd);
										$actionCmd=$template;
										$actionCmd['name'].=__(" (Fermer)", __FILE__);
										if ($linkedLogicalId) {$actionCmd['value']=$linkedLogicalId;}
										$actionCmd['logicalId'].='_CLOSE';
										$actionCmd['type']="action";
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=$c['minValue'];
										
										array_push($cmds,$actionCmd);
										$actionCmd=$template;
										$actionCmd['name'].=__(" (Stop)", __FILE__);
										if ($linkedLogicalId) {$actionCmd['value']=$linkedLogicalId;}
										$actionCmd['logicalId'].='_STOP';
										$actionCmd['type']="action";
										$actionCmd['subtype']="other";
										$actionCmd['configuration']['valueToSet']=$c['minValue']+$c['minStep']; // état du milieu (50 pour 0/50/100, 1 pour 0/1/2)
									} else {
										$actionCmd['subtype']="slider";
									}
								}
							break;
							case 'data':
							case 'tlv8':
								$actionCmd['isVisible']=0;
							case 'string':
								$actionCmd['subtype']="message";
								$actionCmd['display']['title_disable']=1;
								$actionCmd['display']['message_disable']=0;
								$actionCmd['display']['title_placeholder']=__('Non Utilisé', __FILE__);
								$actionCmd['display']['message_placeholder']=__('Chaîne à envoyer', __FILE__);
							break;
						}
						if(is_array($LightElement) && count($LightElement) == 2 && ($CharName == 'hue' || $CharName == 'saturation')) {
							$actionCmd['name']=($legacy) ? "Color (Action)" : $prefix.__("Couleur", __FILE__)." (Action)";
							$actionCmd['subtype']="color";
							$actionCmd['logicalId']=$s['iid'].'_'.$ServiceType.'_';
							$aidiids=[];
							ksort($LightElement); // teinte puis saturation, comme la commande info et execute()
							foreach($LightElement as $le) {
								$actionCmd['logicalId'].=homekitUtils::expandUUID($le['type']).'_';
								array_push($aidiids,$aid.'.'.$le['iid']);
							}
							$actionCmd['configuration']["aidiid"]=join('|',$aidiids);
							$actionCmd['logicalId'].="ACTION";
							
							$actionCmd['configuration']['hkFormat']='hue-saturation';
							unset($actionCmd['configuration']['maxValue']);
							unset($actionCmd['configuration']['minValue']);
							unset($actionCmd['configuration']['step']);
							$actionCmd['configuration']['maxLen']=7;
							unset($actionCmd['configuration']['valid-values']);
						}
						array_push($cmds,$actionCmd);
					}
				}
			}
		}

		return ['name' => $thisName, 'cmds' => $cmds, 'conf' => $conf];
	}
}
