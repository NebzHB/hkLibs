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
 * Méthodes communes des commandes de hkControl et reverseHomebridge (classe qui étend cmd) :
 *   class monPluginCmd extends cmd { use homekitCmdTrait; ... }
 * Dans un plugin, ce fichier est une copie (namespace du plugin) : le modifier dans NebzHB/hkLibs.
 */
trait homekitCmdTrait {
	/**
	 * Le cœur coupe le nom à 127 octets et peut casser un caractère accentué : on le raccourcit proprement avant lui
	 * (homekitMapper::fitName), à la création comme au renommage par l'utilisateur.
	 */
	public function setName($_name) {
		return parent::setName(homekitMapper::fitName($_name));
	}
}
