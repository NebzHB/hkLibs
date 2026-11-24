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

require_once __DIR__ . '/homekitMapper.class.php';

/**
 * Méthodes communes des équipements de hkControl et reverseHomebridge (classe qui étend eqLogic) :
 *   class monPlugin extends eqLogic { use homekitEqLogicTrait; ... }
 * La classe doit avoir private static $_freshDescription (vrai pendant le traitement d'une description fraîchement
 * lue sur l'accessoire) et sa classe de commandes doit s'appeler <classe>Cmd.
 * Dans un plugin, ce fichier est une copie (namespace du plugin) : le modifier dans NebzHB/hkLibs.
 */
trait homekitEqLogicTrait {
	/**
	 * Nom déjà pris dans la pièce, tous plugins confondus. Comparé par la base, comme son index unique
	 * (name, object_id) en collation utf8mb4_unicode_ci : sans tenir compte de la casse ni des accents
	 * (« Clim veranda » = « Clim Véranda »). Une comparaison en PHP laisserait passer ces doublons et
	 * save() échouerait (SQLSTATE[23000] 1062 Duplicate entry).
	 * $objectId vide (null, '' ou 0) = sans pièce ; $excludeId : équipement à ignorer (renommage).
	 */
	public static function nameExists($name, $objectId = null, $excludeId = null) {
		$values = ['name' => $name];
		$sql = 'SELECT id FROM eqLogic WHERE name=:name AND ';
		if ($objectId) {
			$sql .= 'object_id=:object_id';
			$values['object_id'] = $objectId;
		} else {
			$sql .= 'object_id IS NULL';
		}
		if ($excludeId) {
			$sql .= ' AND id<>:id';
			$values['id'] = $excludeId;
		}
		return !empty(\DB::Prepare($sql . ' LIMIT 1', $values, \DB::FETCH_TYPE_ROW));
	}

	/**
	 * Nom libre pour un nouvel équipement : le nom (raccourci et nettoyé comme le cœur), sinon
	 * « nom_<suffixe> », sinon « nom_<suffixe>_2 », « _3 »... (doublons vérifiés par la base, voir nameExists).
	 */
	public static function freeEqName($name, $suffix, $objectId = null) {
		$name = homekitMapper::fitName($name);
		if (!static::nameExists($name, $objectId)) return $name;
		$base = $name;
		$i = 1;
		do {
			$end = '_' . $suffix . (($i > 1) ? '_' . $i : '');
			$name = homekitMapper::fitName($base, 127 - strlen($end)) . $end;
			$i++;
		} while (static::nameExists($name, $objectId) && $i < 100);
		return $name;
	}

	/**
	 * Nom de commande déjà pris dans cet équipement : cmd::byEqLogicIdCmdName compare en SQL, donc comme l'index
	 * unique (eqLogic_id, name) de la base (sans casse ni accents).
	 */
	public function cmdNameExist($name) {
		return is_object(\cmd::byEqLogicIdCmdName($this->getId(), $name));
	}

	/**
	 * Crée la commande (nom, unité et affichage seulement à la création), ou met à jour une commande existante
	 * d'après la description (type, configuration, valeur liée...). $cmd : format de homekitMapper::accessory().
	 */
	public function createCmd($cmd) {
		$changed=false;
		$order=intval($this->getConfiguration('orderCmd',1));
		
		$isNew=false;
		
		$newCmd = $this->getCmd(null, $cmd['logicalId']);
		if (!is_object($newCmd)) {
			$isNew=true;
			\log::add(__CLASS__,'info',__("Création commande ", __FILE__).$order.':'.$cmd['name']);
			$cmdClass = __CLASS__ . 'Cmd';
			$newCmd = new $cmdClass();
			$newCmd->setLogicalId($cmd['logicalId']);
			$newCmd->setIsVisible($cmd['isVisible']);
			$newCmd->setOrder($order);
			
			$origName=homekitMapper::cleanNetworkName($cmd['name']); // nom venu de la description de l'accessoire
			$cmd['name']=homekitMapper::fitName($origName);
			$c=2;
			while($this->cmdNameExist($cmd['name'])) {
				$cmd['name']=homekitMapper::fitName($origName, 127 - strlen('_'.$c)).'_'.$c;
				$c++;
			}
			
			$newCmd->setName($cmd['name']);
			$newCmd->setEqLogic_id($this->getId());
		} else {
			\log::add(__CLASS__,'debug',__("Modification commande ", __FILE__).$cmd['name']);
		}
		// unité et affichage : seulement à la création, pour ne pas écraser les réglages de l'utilisateur
		// (type générique, unité...) à chaque sauvegarde ; le reste suit toujours la description
		if($isNew && isset($cmd['unite'])) {
			$newCmd->setUnite( $cmd['unite'] );
		}
		$newCmd->setType($cmd['type']);
		if(isset($cmd['configuration'])) {
			foreach($cmd['configuration'] as $configuration_type=>$configuration_value) {
				$newCmd->setConfiguration($configuration_type, $configuration_value);
			}
		} 
		if(isset($cmd['template'])) {
			foreach($cmd['template'] as $template_type=>$template_value) {
				$newCmd->setTemplate($template_type, $template_value);
			}

		} 
		if($isNew && isset($cmd['display'])) {
			foreach($cmd['display'] as $display_type=>$display_value) {
				$newCmd->setDisplay($display_type, $display_value);
			}
		}
		$newCmd->setSubType($cmd['subtype']);
		if($cmd['type'] == 'action' && isset($cmd['value'])) {
			$linkStatus = $this->getCmd(null, $cmd['value']);
			if(is_object($linkStatus))
				$newCmd->setValue($linkStatus->getId());
		}
		$newCmd->save();
		if($isNew) {
			$this->setConfiguration('orderCmd',++$order);
			$this->save(true);		
			// « En Ligne » créé après l'événement 'online' du démon (premier appairage) : le démon ne le renvoie
			// qu'au prochain changement, reprendre l'état mémorisé
			if($cmd['logicalId'] == 'online') {
				$online = $this->getCache('hkOnline', null);
				if($online !== null && $online !== '') $this->checkAndUpdateCmd('online', intval($online) ? 1 : 0);
			}
		}
		// valeur de la description : à la création de la commande, ou si la description vient d'être lue sur
		// l'accessoire (avant : chaque sauvegarde de l'équipement remettait les valeurs de la dernière lecture)
		if ($cmd['type'] == 'info' && isset($cmd['value']) && ($isNew || self::$_freshDescription)) {
			$cmdParts=explode('_',$cmd['logicalId']);
			if($cmdParts[2] == '00000068-0000-1000-8000-0026BB765291') { // if battery-level
				$this->batteryStatus($cmd['value']);
			}
			if($cmdParts[2] != '00000073-0000-1000-8000-0026BB765291') { // if ! input-event
				$changed=$this->checkAndUpdateCmd($cmd['logicalId'],$cmd['value']);
			}
			if ($changed) 
				$this->refreshWidget();
		}
	}
}
