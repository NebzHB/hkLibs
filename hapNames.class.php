<?php
/* Fichier généré par tools/genHapNames.js de NebzHB/hkLibs depuis @homebridge/hap-nodejs 2.3.0.
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
			'00000260-0000-1000-8000-0026BB765291' => __("Code d'accès", __FILE__), // Access Code
			'000000DA-0000-1000-8000-0026BB765291' => __("Contrôle d'accès", __FILE__), // Access Control
			'0000003E-0000-1000-8000-0026BB765291' => __("Informations de l'accessoire", __FILE__), // Accessory Information
			'00000270-0000-1000-8000-0026BB765291' => __("Métriques de l'accessoire", __FILE__), // Accessory Metrics
			'00000239-0000-1000-8000-0026BB765291' => __("Informations d'exécution de l'accessoire", __FILE__), // Accessory Runtime Information
			'000000BB-0000-1000-8000-0026BB765291' => __("Purificateur d'air", __FILE__), // Air Purifier
			'0000008D-0000-1000-8000-0026BB765291' => __("Capteur de qualité de l'air", __FILE__), // Air Quality Sensor
			'00000267-0000-1000-8000-0026BB765291' => __("Mise à jour des ressources", __FILE__), // Asset Update
			'0000026A-0000-1000-8000-0026BB765291' => __("Assistant", __FILE__), // Assistant
			'00000127-0000-1000-8000-0026BB765291' => __("Gestion du flux audio", __FILE__), // Audio Stream Management
			'00000096-0000-1000-8000-0026BB765291' => __("Batterie", __FILE__), // Battery
			'0000021A-0000-1000-8000-0026BB765291' => __("Mode de fonctionnement de la caméra", __FILE__), // Camera Operating Mode
			'00000204-0000-1000-8000-0026BB765291' => __("Gestion de l'enregistrement de la caméra", __FILE__), // Camera Recording Management
			'00000110-0000-1000-8000-0026BB765291' => __("Gestion du flux RTP de la caméra", __FILE__), // Camera RTP Stream Management
			'00000097-0000-1000-8000-0026BB765291' => __("Capteur de CO2", __FILE__), // Carbon Dioxide Sensor
			'0000007F-0000-1000-8000-0026BB765291' => __("Capteur de CO", __FILE__), // Carbon Monoxide Sensor
			'0000005A-0000-1000-8000-0026BB765291' => __("Relais cloud", __FILE__), // Cloud Relay
			'00000080-0000-1000-8000-0026BB765291' => __("Capteur d'ouverture", __FILE__), // Contact Sensor
			'00000129-0000-1000-8000-0026BB765291' => __("Gestion du transport de flux de données", __FILE__), // Data Stream Transport Management
			'00000237-0000-1000-8000-0026BB765291' => __("Diagnostic", __FILE__), // Diagnostics
			'00000081-0000-1000-8000-0026BB765291' => __("Porte", __FILE__), // Door
			'00000121-0000-1000-8000-0026BB765291' => __("Sonnette", __FILE__), // Doorbell
			'00000040-0000-1000-8000-0026BB765291' => __("Ventilateur", __FILE__), // Fan
			'000000B7-0000-1000-8000-0026BB765291' => __("Ventilateur", __FILE__), // Fanv2
			'000000D7-0000-1000-8000-0026BB765291' => __("Robinet", __FILE__), // Faucet
			'000000BA-0000-1000-8000-0026BB765291' => __("Entretien du filtre", __FILE__), // Filter Maintenance
			'00000236-0000-1000-8000-0026BB765291' => __("Mise à jour du firmware", __FILE__), // Firmware Update
			'00000041-0000-1000-8000-0026BB765291' => __("Porte de garage", __FILE__), // Garage Door Opener
			'000000BC-0000-1000-8000-0026BB765291' => __("Chauffage-Climatisation", __FILE__), // Heater-Cooler
			'000000BD-0000-1000-8000-0026BB765291' => __("Humidificateur-Déshumidificateur", __FILE__), // Humidifier-Dehumidifier
			'00000082-0000-1000-8000-0026BB765291' => __("Capteur d'humidité", __FILE__), // Humidity Sensor
			'000000D9-0000-1000-8000-0026BB765291' => __("Source d'entrée", __FILE__), // Input Source
			'000000CF-0000-1000-8000-0026BB765291' => __("Système d'arrosage", __FILE__), // Irrigation-System
			'00000083-0000-1000-8000-0026BB765291' => __("Détecteur de fuite", __FILE__), // Leak Sensor
			'00000084-0000-1000-8000-0026BB765291' => __("Capteur de luminosité", __FILE__), // Light Sensor
			'00000043-0000-1000-8000-0026BB765291' => __("Lampe", __FILE__), // Lightbulb
			'00000044-0000-1000-8000-0026BB765291' => __("Gestion de la serrure", __FILE__), // Lock Management
			'00000045-0000-1000-8000-0026BB765291' => __("Serrure", __FILE__), // Lock Mechanism
			'00000112-0000-1000-8000-0026BB765291' => __("Microphone", __FILE__), // Microphone
			'00000085-0000-1000-8000-0026BB765291' => __("Détecteur de mouvement", __FILE__), // Motion Sensor
			'00000266-0000-1000-8000-0026BB765291' => __("Accès NFC", __FILE__), // NFC Access
			'00000086-0000-1000-8000-0026BB765291' => __("Détecteur de présence", __FILE__), // Occupancy Sensor
			'00000047-0000-1000-8000-0026BB765291' => __("Prise", __FILE__), // Outlet
			'00000055-0000-1000-8000-0026BB765291' => __("Appairage", __FILE__), // Pairing
			'00000221-0000-1000-8000-0026BB765291' => __("Gestion de l'alimentation", __FILE__), // Power Management
			'000000A2-0000-1000-8000-0026BB765291' => __("Informations du protocole", __FILE__), // Protocol Information
			'00000315-0000-1000-8000-0026BB765291' => __("Contrôle de proximité", __FILE__), // Proximity Control
			'0000007E-0000-1000-8000-0026BB765291' => __("Système de sécurité", __FILE__), // Security System
			'000000CC-0000-1000-8000-0026BB765291' => __("Étiquette de service", __FILE__), // Service Label
			'00000133-0000-1000-8000-0026BB765291' => __("Siri", __FILE__), // Siri
			'00000253-0000-1000-8000-0026BB765291' => __("Point de terminaison Siri", __FILE__), // Siri Endpoint
			'000000B9-0000-1000-8000-0026BB765291' => __("Lamelles", __FILE__), // Slats
			'00000228-0000-1000-8000-0026BB765291' => __("Enceinte connectée", __FILE__), // Smart Speaker
			'00000087-0000-1000-8000-0026BB765291' => __("Détecteur de fumée", __FILE__), // Smoke Sensor
			'00000113-0000-1000-8000-0026BB765291' => __("Haut-parleur", __FILE__), // Speaker
			'00000088-0000-1000-8000-0026BB765291' => __("Interrupteur programmable avec état", __FILE__), // Stateful Programmable Switch
			'00000089-0000-1000-8000-0026BB765291' => __("Bouton programmable", __FILE__), // Stateless Programmable Switch
			'00000049-0000-1000-8000-0026BB765291' => __("Interrupteur", __FILE__), // Switch
			'0000022E-0000-1000-8000-0026BB765291' => __("Liste des Wi-Fi préférés", __FILE__), // Tap Management
			'00000125-0000-1000-8000-0026BB765291' => __("Télécommande", __FILE__), // Target Control
			'00000122-0000-1000-8000-0026BB765291' => __("Gestion de la télécommande", __FILE__), // Target Control Management
			'000000D8-0000-1000-8000-0026BB765291' => __("Téléviseur", __FILE__), // Television
			'0000008A-0000-1000-8000-0026BB765291' => __("Capteur de température", __FILE__), // Temperature Sensor
			'0000004A-0000-1000-8000-0026BB765291' => __("Thermostat", __FILE__), // Thermostat
			'00000701-0000-1000-8000-0026BB765291' => __("Transport Thread", __FILE__), // Thread Transport
			'00000203-0000-1000-8000-0026BB765291' => __("Gestion du transport de transfert", __FILE__), // Transfer Transport Management
			'00000056-0000-1000-8000-0026BB765291' => __("Tunnel", __FILE__), // Tunnel
			'000000D0-0000-1000-8000-0026BB765291' => __("Vanne", __FILE__), // Valve
			'0000020A-0000-1000-8000-0026BB765291' => __("Routeur Wi-Fi", __FILE__), // Wi-Fi Router
			'0000020F-0000-1000-8000-0026BB765291' => __("Satellite Wi-Fi", __FILE__), // Wi-Fi Satellite
			'0000022A-0000-1000-8000-0026BB765291' => __("Transport Wi-Fi", __FILE__), // Wi-Fi Transport
			'0000008B-0000-1000-8000-0026BB765291' => __("Fenêtre", __FILE__), // Window
			'0000008C-0000-1000-8000-0026BB765291' => __("Volet", __FILE__), // Window Covering
		];
		return $names;
	}

	private static function characteristics() {
		static $names = null;
		if ($names === null) $names = [
			'00000262-0000-1000-8000-0026BB765291' => __("Point de contrôle du code d'accès", __FILE__), // Access Code Control Point
			'00000261-0000-1000-8000-0026BB765291' => __("Configuration prise en charge du code d'accès", __FILE__), // Access Code Supported Configuration
			'000000E5-0000-1000-8000-0026BB765291' => __("Niveau de contrôle d'accès", __FILE__), // Access Control Level
			'000000A6-0000-1000-8000-0026BB765291' => __("Indicateurs de l'accessoire", __FILE__), // Accessory Flags
			'00000057-0000-1000-8000-0026BB765291' => __("Identifiant de l'accessoire", __FILE__), // Accessory Identifier
			'000000B0-0000-1000-8000-0026BB765291' => __("Actif", __FILE__), // Active
			'000000E7-0000-1000-8000-0026BB765291' => __("Identifiant actif", __FILE__), // Active Identifier
			'0000023B-0000-1000-8000-0026BB765291' => __("Intervalle d'activité", __FILE__), // Activity Interval
			'00000001-0000-1000-8000-0026BB765291' => __("Accès administrateur uniquement", __FILE__), // Administrator Only Access
			'00000064-0000-1000-8000-0026BB765291' => __("Densité de particules", __FILE__), // Air Particulate Density
			'00000065-0000-1000-8000-0026BB765291' => __("Taille des particules", __FILE__), // Air Particulate Size
			'00000095-0000-1000-8000-0026BB765291' => __("Qualité de l'air", __FILE__), // Air Quality
			'0000025B-0000-1000-8000-0026BB765291' => __("AirPlay activé", __FILE__), // AirPlay Enable
			'000000A4-0000-1000-8000-0026BB765291' => __("Identifiant de l'application associée", __FILE__), // App Matching Identifier
			'00000269-0000-1000-8000-0026BB765291' => __("Disponibilité de la mise à jour des ressources", __FILE__), // Asset Update Readiness
			'00000005-0000-1000-8000-0026BB765291' => __("Retour audio", __FILE__), // Audio Feedback
			'00000068-0000-1000-8000-0026BB765291' => __("Niveau de batterie", __FILE__), // Battery Level
			'00000008-0000-1000-8000-0026BB765291' => __("Luminosité", __FILE__), // Brightness
			'00000126-0000-1000-8000-0026BB765291' => __("Événement bouton", __FILE__), // Button Event
			'0000021D-0000-1000-8000-0026BB765291' => __("Voyant de fonctionnement de la caméra", __FILE__), // Camera Operating Mode Indicator
			'00000092-0000-1000-8000-0026BB765291' => __("CO2 détecté", __FILE__), // Carbon Dioxide Detected
			'00000093-0000-1000-8000-0026BB765291' => __("Niveau de CO2", __FILE__), // Carbon Dioxide Level
			'00000094-0000-1000-8000-0026BB765291' => __("Pic de CO2", __FILE__), // Carbon Dioxide Peak Level
			'00000069-0000-1000-8000-0026BB765291' => __("CO détecté", __FILE__), // Carbon Monoxide Detected
			'00000090-0000-1000-8000-0026BB765291' => __("Niveau de CO", __FILE__), // Carbon Monoxide Level
			'00000091-0000-1000-8000-0026BB765291' => __("Pic de CO", __FILE__), // Carbon Monoxide Peak Level
			'00000246-0000-1000-8000-0026BB765291' => __("Seuil de détection d'énergie CCA", __FILE__), // CCA Energy Detect Threshold
			'00000245-0000-1000-8000-0026BB765291' => __("Seuil de détection de signal CCA", __FILE__), // CCA Signal Detect Threshold
			'0000024B-0000-1000-8000-0026BB765291' => __("Nombre de transitions actives", __FILE__), // Characteristic Value Active Transition Count
			'00000143-0000-1000-8000-0026BB765291' => __("Contrôle des transitions", __FILE__), // Characteristic Value Transition Control
			'0000008F-0000-1000-8000-0026BB765291' => __("État de charge", __FILE__), // Charging State
			'000000DD-0000-1000-8000-0026BB765291' => __("Sous-titres", __FILE__), // Closed Captions
			'000000CE-0000-1000-8000-0026BB765291' => __("Température de couleur", __FILE__), // Color Temperature
			'00000263-0000-1000-8000-0026BB765291' => __("État de la configuration", __FILE__), // Configuration State
			'000000E3-0000-1000-8000-0026BB765291' => __("Nom configuré", __FILE__), // Configured Name
			'00000273-0000-1000-8000-0026BB765291' => __("Surveillance de la connexion", __FILE__), // Connection Health Monitor
			'0000006A-0000-1000-8000-0026BB765291' => __("État du contact", __FILE__), // Contact Sensor State
			'0000000D-0000-1000-8000-0026BB765291' => __("Seuil de climatisation", __FILE__), // Cooling Threshold Temperature
			'00000250-0000-1000-8000-0026BB765291' => __("Empreinte cryptographique", __FILE__), // Crypto Hash
			'000000A9-0000-1000-8000-0026BB765291' => __("État actuel du purificateur", __FILE__), // Current Air Purifier State
			'0000006B-0000-1000-8000-0026BB765291' => __("Luminosité ambiante", __FILE__), // Current Ambient Light Level
			'0000000E-0000-1000-8000-0026BB765291' => __("État actuel de la porte", __FILE__), // Current Door State
			'000000AF-0000-1000-8000-0026BB765291' => __("État actuel du ventilateur", __FILE__), // Current Fan State
			'000000B1-0000-1000-8000-0026BB765291' => __("État actuel du chauffage-climatisation", __FILE__), // Current Heater-Cooler State
			'0000000F-0000-1000-8000-0026BB765291' => __("Mode de chauffage actuel", __FILE__), // Current Heating Cooling State
			'0000006C-0000-1000-8000-0026BB765291' => __("Inclinaison horizontale actuelle", __FILE__), // Current Horizontal Tilt Angle
			'000000B3-0000-1000-8000-0026BB765291' => __("État actuel de l'humidificateur", __FILE__), // Current Humidifier-Dehumidifier State
			'000000E0-0000-1000-8000-0026BB765291' => __("État actuel de la lecture", __FILE__), // Current Media State
			'0000006D-0000-1000-8000-0026BB765291' => __("Position actuelle", __FILE__), // Current Position
			'00000010-0000-1000-8000-0026BB765291' => __("Humidité relative", __FILE__), // Current Relative Humidity
			'000000AA-0000-1000-8000-0026BB765291' => __("État actuel des lamelles", __FILE__), // Current Slat State
			'00000011-0000-1000-8000-0026BB765291' => __("Température", __FILE__), // Current Temperature
			'000000C1-0000-1000-8000-0026BB765291' => __("Inclinaison actuelle", __FILE__), // Current Tilt Angle
			'0000022B-0000-1000-8000-0026BB765291' => __("Transport actuel", __FILE__), // Current Transport
			'0000006E-0000-1000-8000-0026BB765291' => __("Inclinaison verticale actuelle", __FILE__), // Current Vertical Tilt Angle
			'00000135-0000-1000-8000-0026BB765291' => __("Visibilité actuelle", __FILE__), // Current Visibility State
			'00000138-0000-1000-8000-0026BB765291' => __("Transport HAP du flux de données", __FILE__), // Data Stream HAP Transport
			'00000139-0000-1000-8000-0026BB765291' => __("Interruption du transport HAP du flux de données", __FILE__), // Data Stream HAP Transport Interrupt
			'00000224-0000-1000-8000-0026BB765291' => __("Champ de vision diagonal", __FILE__), // Diagonal Field Of View
			'0000011D-0000-1000-8000-0026BB765291' => __("Zoom numérique", __FILE__), // Digital Zoom
			'00000136-0000-1000-8000-0026BB765291' => __("Ordre d'affichage", __FILE__), // Display Order
			'0000023D-0000-1000-8000-0026BB765291' => __("Retransmissions d'événements maximum", __FILE__), // Event Retransmission Maximum
			'00000223-0000-1000-8000-0026BB765291' => __("Captures sur événement actives", __FILE__), // Event Snapshots Active
			'0000023E-0000-1000-8000-0026BB765291' => __("Compteurs de transmission d'événements", __FILE__), // Event Transmission Counters
			'000000AC-0000-1000-8000-0026BB765291' => __("Filtre à changer", __FILE__), // Filter Change Indication
			'000000AB-0000-1000-8000-0026BB765291' => __("Durée de vie du filtre", __FILE__), // Filter Life Level
			'00000052-0000-1000-8000-0026BB765291' => __("Version du firmware", __FILE__), // Firmware Revision
			'0000026F-0000-1000-8000-0026BB765291' => __("Protocoles de mise à jour du firmware", __FILE__), // Firmware Update Protocol List
			'00000234-0000-1000-8000-0026BB765291' => __("Disponibilité de la mise à jour du firmware", __FILE__), // Firmware Update Readiness
			'00000235-0000-1000-8000-0026BB765291' => __("État de la mise à jour du firmware", __FILE__), // Firmware Update Status
			'0000026C-0000-1000-8000-0026BB765291' => __("Finition matérielle", __FILE__), // Hardware Finish
			'00000053-0000-1000-8000-0026BB765291' => __("Version matérielle", __FILE__), // Hardware Revision
			'0000024A-0000-1000-8000-0026BB765291' => __("Battement de cœur", __FILE__), // Heart Beat
			'00000012-0000-1000-8000-0026BB765291' => __("Seuil de chauffage", __FILE__), // Heating Threshold Temperature
			'0000006F-0000-1000-8000-0026BB765291' => __("Maintenir la position", __FILE__), // Hold Position
			'0000021B-0000-1000-8000-0026BB765291' => __("Caméra HomeKit active", __FILE__), // HomeKit Camera Active
			'00000013-0000-1000-8000-0026BB765291' => __("Teinte", __FILE__), // Hue
			'000000E6-0000-1000-8000-0026BB765291' => __("Identifiant", __FILE__), // Identifier
			'00000014-0000-1000-8000-0026BB765291' => __("Identifier", __FILE__), // Identify
			'0000011F-0000-1000-8000-0026BB765291' => __("Image miroir", __FILE__), // Image Mirroring
			'0000011E-0000-1000-8000-0026BB765291' => __("Rotation de l'image", __FILE__), // Image Rotation
			'000000D2-0000-1000-8000-0026BB765291' => __("En cours d'utilisation", __FILE__), // In Use
			'000000DC-0000-1000-8000-0026BB765291' => __("Type d'appareil d'entrée", __FILE__), // Input Device Type
			'000000DB-0000-1000-8000-0026BB765291' => __("Type de source d'entrée", __FILE__), // Input Source Type
			'000000D6-0000-1000-8000-0026BB765291' => __("Configuré", __FILE__), // Is Configured
			'00000070-0000-1000-8000-0026BB765291' => __("Fuite détectée", __FILE__), // Leak Detected
			'00000050-0000-1000-8000-0026BB765291' => __("Liste des appairages", __FILE__), // List Pairings
			'00000019-0000-1000-8000-0026BB765291' => __("Point de contrôle de la serrure", __FILE__), // Lock Control Point
			'0000001D-0000-1000-8000-0026BB765291' => __("État actuel de la serrure", __FILE__), // Lock Current State
			'0000001C-0000-1000-8000-0026BB765291' => __("Dernière action de la serrure", __FILE__), // Lock Last Known Action
			'0000001A-0000-1000-8000-0026BB765291' => __("Délai de verrouillage automatique", __FILE__), // Lock Management Auto Security Timeout
			'000000A7-0000-1000-8000-0026BB765291' => __("Verrouillage des commandes physiques", __FILE__), // Lock Physical Controls
			'0000001E-0000-1000-8000-0026BB765291' => __("État cible de la serrure", __FILE__), // Lock Target State
			'0000001F-0000-1000-8000-0026BB765291' => __("Journaux", __FILE__), // Logs
			'00000247-0000-1000-8000-0026BB765291' => __("Retransmissions MAC maximum", __FILE__), // MAC Retransmission Maximum
			'00000248-0000-1000-8000-0026BB765291' => __("Compteurs de transmission MAC", __FILE__), // MAC Transmission Counters
			'00000215-0000-1000-8000-0026BB765291' => __("Réseau géré activé", __FILE__), // Managed Network Enable
			'00000227-0000-1000-8000-0026BB765291' => __("Désactivé manuellement", __FILE__), // Manually Disabled
			'00000020-0000-1000-8000-0026BB765291' => __("Fabricant", __FILE__), // Manufacturer
			'0000026D-0000-1000-8000-0026BB765291' => __("Numéro de version du firmware Matter", __FILE__), // Matter Firmware Revision Number
			'0000026E-0000-1000-8000-0026BB765291' => __("État de la mise à jour du firmware Matter", __FILE__), // Matter Firmware Update Status
			'00000243-0000-1000-8000-0026BB765291' => __("Puissance d'émission maximale", __FILE__), // Maximum Transmit Power
			'000000EE-0000-1000-8000-0026BB765291' => __("Niveau de contrôle d'accès aux médias", __FILE__), // Media Access Control Level
			'00000272-0000-1000-8000-0026BB765291' => __("Tampon de métriques plein", __FILE__), // Metrics Buffer Full State
			'00000021-0000-1000-8000-0026BB765291' => __("Modèle", __FILE__), // Model
			'00000022-0000-1000-8000-0026BB765291' => __("Mouvement détecté", __FILE__), // Motion Detected
			'0000026B-0000-1000-8000-0026BB765291' => __("Bouton multifonction", __FILE__), // Multifunction Button
			'0000011A-0000-1000-8000-0026BB765291' => __("Muet", __FILE__), // Mute
			'00000023-0000-1000-8000-0026BB765291' => __("Nom", __FILE__), // Name
			'0000021F-0000-1000-8000-0026BB765291' => __("Contrôle des violations d'accès réseau", __FILE__), // Network Access Violation Control
			'0000020C-0000-1000-8000-0026BB765291' => __("Contrôle des profils clients réseau", __FILE__), // Network Client Profile Control
			'0000020D-0000-1000-8000-0026BB765291' => __("Contrôle de l'état des clients réseau", __FILE__), // Network Client Status Control
			'00000264-0000-1000-8000-0026BB765291' => __("Point de contrôle d'accès NFC", __FILE__), // NFC Access Control Point
			'00000265-0000-1000-8000-0026BB765291' => __("Configuration prise en charge de l'accès NFC", __FILE__), // NFC Access Supported Configuration
			'0000011B-0000-1000-8000-0026BB765291' => __("Vision nocturne", __FILE__), // Night Vision
			'000000C4-0000-1000-8000-0026BB765291' => __("Densité de dioxyde d'azote", __FILE__), // Nitrogen Dioxide Density
			'00000024-0000-1000-8000-0026BB765291' => __("Obstruction détectée", __FILE__), // Obstruction Detected
			'00000071-0000-1000-8000-0026BB765291' => __("Présence détectée", __FILE__), // Occupancy Detected
			'00000025-0000-1000-8000-0026BB765291' => __("État", __FILE__), // On
			'00000232-0000-1000-8000-0026BB765291' => __("Réponse d'état de fonctionnement", __FILE__), // Operating State Response
			'0000011C-0000-1000-8000-0026BB765291' => __("Zoom optique", __FILE__), // Optical Zoom
			'00000026-0000-1000-8000-0026BB765291' => __("Prise utilisée", __FILE__), // Outlet In Use
			'000000C3-0000-1000-8000-0026BB765291' => __("Densité d'ozone", __FILE__), // Ozone Density
			'0000004C-0000-1000-8000-0026BB765291' => __("Configuration de l'appairage", __FILE__), // Pair Setup
			'0000004E-0000-1000-8000-0026BB765291' => __("Vérification de l'appairage", __FILE__), // Pair Verify
			'0000004F-0000-1000-8000-0026BB765291' => __("Fonctions d'appairage", __FILE__), // Pairing Features
			'000000E4-0000-1000-8000-0026BB765291' => __("Réglage du mot de passe", __FILE__), // Password Setting
			'00000225-0000-1000-8000-0026BB765291' => __("Captures périodiques actives", __FILE__), // Periodic Snapshots Active
			'000000E2-0000-1000-8000-0026BB765291' => __("Mode d'image", __FILE__), // Picture Mode
			'0000023C-0000-1000-8000-0026BB765291' => __("Ping", __FILE__), // Ping
			'000000C7-0000-1000-8000-0026BB765291' => __("Densité PM10", __FILE__), // PM10 Density
			'000000C6-0000-1000-8000-0026BB765291' => __("Densité PM2.5", __FILE__), // PM2.5 Density
			'00000072-0000-1000-8000-0026BB765291' => __("État de la position", __FILE__), // Position State
			'000000DF-0000-1000-8000-0026BB765291' => __("Sélection du mode d'alimentation", __FILE__), // Power Mode Selection
			'0000022E-0000-1000-8000-0026BB765291' => __("Liste des Wi-Fi préférés", __FILE__), // Preferred Wi-Fi List
			'00000220-0000-1000-8000-0026BB765291' => __("Données produit", __FILE__), // Product Data
			'000000D1-0000-1000-8000-0026BB765291' => __("Mode programme", __FILE__), // Program Mode
			'00000073-0000-1000-8000-0026BB765291' => __("Événement du bouton", __FILE__), // Programmable Switch Event
			'00000074-0000-1000-8000-0026BB765291' => __("État de sortie de l'interrupteur", __FILE__), // Programmable Switch Output State
			'00000316-0000-1000-8000-0026BB765291' => __("Contrôle de proximité activé", __FILE__), // Proximity Control Enabled
			'0000023F-0000-1000-8000-0026BB765291' => __("Puissance du signal reçu", __FILE__), // Received Signal Strength Indication
			'00000244-0000-1000-8000-0026BB765291' => __("Sensibilité du récepteur", __FILE__), // Receiver Sensitivity
			'00000226-0000-1000-8000-0026BB765291' => __("Enregistrement audio actif", __FILE__), // Recording Audio Active
			'000000C9-0000-1000-8000-0026BB765291' => __("Seuil de déshumidification", __FILE__), // Relative Humidity Dehumidifier Threshold
			'000000CA-0000-1000-8000-0026BB765291' => __("Seuil d'humidification", __FILE__), // Relative Humidity Humidifier Threshold
			'0000005E-0000-1000-8000-0026BB765291' => __("Point de contrôle du relais", __FILE__), // Relay Control Point
			'0000005B-0000-1000-8000-0026BB765291' => __("Relais activé", __FILE__), // Relay Enabled
			'0000005C-0000-1000-8000-0026BB765291' => __("État du relais", __FILE__), // Relay State
			'000000D4-0000-1000-8000-0026BB765291' => __("Durée restante", __FILE__), // Remaining Duration
			'000000E1-0000-1000-8000-0026BB765291' => __("Touche de télécommande", __FILE__), // Remote Key
			'000000AD-0000-1000-8000-0026BB765291' => __("Réinitialiser l'indicateur du filtre", __FILE__), // Reset Filter Indication
			'00000028-0000-1000-8000-0026BB765291' => __("Sens de rotation", __FILE__), // Rotation Direction
			'00000029-0000-1000-8000-0026BB765291' => __("Vitesse de rotation", __FILE__), // Rotation Speed
			'0000020E-0000-1000-8000-0026BB765291' => __("État du routeur", __FILE__), // Router Status
			'0000002F-0000-1000-8000-0026BB765291' => __("Saturation", __FILE__), // Saturation
			'0000008E-0000-1000-8000-0026BB765291' => __("Type d'alarme", __FILE__), // Security System Alarm Type
			'00000066-0000-1000-8000-0026BB765291' => __("État actuel de l'alarme", __FILE__), // Security System Current State
			'00000067-0000-1000-8000-0026BB765291' => __("État cible de l'alarme", __FILE__), // Security System Target State
			'00000128-0000-1000-8000-0026BB765291' => __("Configuration du flux audio sélectionnée", __FILE__), // Selected Audio Stream Configuration
			'00000209-0000-1000-8000-0026BB765291' => __("Configuration d'enregistrement sélectionnée", __FILE__), // Selected Camera Recording Configuration
			'0000024D-0000-1000-8000-0026BB765291' => __("Modes de diagnostic sélectionnés", __FILE__), // Selected Diagnostics Modes
			'00000117-0000-1000-8000-0026BB765291' => __("Configuration du flux RTP sélectionnée", __FILE__), // Selected RTP Stream Configuration
			'00000275-0000-1000-8000-0026BB765291' => __("Configuration de maintien de session sélectionnée", __FILE__), // Selected Session Keep-alive Configuration List
			'00000252-0000-1000-8000-0026BB765291' => __("Configuration de veille sélectionnée", __FILE__), // Selected Sleep Configuration
			'00000030-0000-1000-8000-0026BB765291' => __("Numéro de série", __FILE__), // Serial Number
			'000000CB-0000-1000-8000-0026BB765291' => __("Index de l'étiquette de service", __FILE__), // Service Label Index
			'000000CD-0000-1000-8000-0026BB765291' => __("Espace de noms de l'étiquette de service", __FILE__), // Service Label Namespace
			'000000D3-0000-1000-8000-0026BB765291' => __("Durée définie", __FILE__), // Set Duration
			'00000131-0000-1000-8000-0026BB765291' => __("Configuration du transport de flux de données", __FILE__), // Setup Data Stream Transport
			'00000118-0000-1000-8000-0026BB765291' => __("Configuration des points de terminaison", __FILE__), // Setup Endpoints
			'00000201-0000-1000-8000-0026BB765291' => __("Configuration du transport de transfert", __FILE__), // Setup Transfer Transport
			'00000241-0000-1000-8000-0026BB765291' => __("Rapport signal sur bruit", __FILE__), // Signal To Noise Ratio
			'00000255-0000-1000-8000-0026BB765291' => __("Siri activé", __FILE__), // Siri Enable
			'00000254-0000-1000-8000-0026BB765291' => __("État de la session Siri", __FILE__), // Siri Endpoint Session Status
			'0000025A-0000-1000-8000-0026BB765291' => __("Version du moteur Siri", __FILE__), // Siri Engine Version
			'00000132-0000-1000-8000-0026BB765291' => __("Type d'entrée Siri", __FILE__), // Siri Input Type
			'00000258-0000-1000-8000-0026BB765291' => __("Voyant Siri en cours d'utilisation", __FILE__), // Siri Light On Use
			'00000256-0000-1000-8000-0026BB765291' => __("Siri à l'écoute", __FILE__), // Siri Listening
			'00000257-0000-1000-8000-0026BB765291' => __("Toucher pour utiliser Siri", __FILE__), // Siri Touch To Use
			'000000C0-0000-1000-8000-0026BB765291' => __("Type de lamelles", __FILE__), // Slat Type
			'000000E8-0000-1000-8000-0026BB765291' => __("Mode de découverte en veille", __FILE__), // Sleep Discovery Mode
			'0000023A-0000-1000-8000-0026BB765291' => __("Intervalle de veille", __FILE__), // Sleep Interval
			'00000076-0000-1000-8000-0026BB765291' => __("Fumée détectée", __FILE__), // Smoke Detected
			'00000054-0000-1000-8000-0026BB765291' => __("Version du logiciel", __FILE__), // Software Revision
			'00000249-0000-1000-8000-0026BB765291' => __("Version du firmware en attente", __FILE__), // Staged Firmware Version
			'00000075-0000-1000-8000-0026BB765291' => __("Statut actif", __FILE__), // Status Active
			'00000077-0000-1000-8000-0026BB765291' => __("Défaut", __FILE__), // Status Fault
			'00000078-0000-1000-8000-0026BB765291' => __("Bloqué", __FILE__), // Status Jammed
			'00000079-0000-1000-8000-0026BB765291' => __("Batterie faible", __FILE__), // Status Low Battery
			'0000007A-0000-1000-8000-0026BB765291' => __("Sabotage", __FILE__), // Status Tampered
			'00000120-0000-1000-8000-0026BB765291' => __("État du streaming", __FILE__), // Streaming Status
			'000000C5-0000-1000-8000-0026BB765291' => __("Densité de dioxyde de soufre", __FILE__), // Sulphur Dioxide Density
			'00000268-0000-1000-8000-0026BB765291' => __("Types de ressources pris en charge", __FILE__), // Supported Asset Types
			'00000207-0000-1000-8000-0026BB765291' => __("Configuration d'enregistrement audio prise en charge", __FILE__), // Supported Audio Recording Configuration
			'00000115-0000-1000-8000-0026BB765291' => __("Configuration du flux audio prise en charge", __FILE__), // Supported Audio Stream Configuration
			'00000205-0000-1000-8000-0026BB765291' => __("Configuration d'enregistrement de la caméra prise en charge", __FILE__), // Supported Camera Recording Configuration
			'00000144-0000-1000-8000-0026BB765291' => __("Configuration des transitions prise en charge", __FILE__), // Supported Characteristic Value Transition Configuration
			'00000130-0000-1000-8000-0026BB765291' => __("Configuration du transport de flux de données prise en charge", __FILE__), // Supported Data Stream Transport Configuration
			'0000024C-0000-1000-8000-0026BB765291' => __("Modes de diagnostic pris en charge", __FILE__), // Supported Diagnostics Modes
			'00000238-0000-1000-8000-0026BB765291' => __("Capture de diagnostic prise en charge", __FILE__), // Supported Diagnostics Snapshot
			'00000233-0000-1000-8000-0026BB765291' => __("Configuration de mise à jour du firmware prise en charge", __FILE__), // Supported Firmware Update Configuration
			'00000271-0000-1000-8000-0026BB765291' => __("Métriques prises en charge", __FILE__), // Supported Metrics
			'00000210-0000-1000-8000-0026BB765291' => __("Configuration du routeur prise en charge", __FILE__), // Supported Router Configuration
			'00000116-0000-1000-8000-0026BB765291' => __("Configuration RTP prise en charge", __FILE__), // Supported RTP Configuration
			'00000274-0000-1000-8000-0026BB765291' => __("Configurations de maintien de session prises en charge", __FILE__), // Supported Session Keep-alive Configuration List
			'00000251-0000-1000-8000-0026BB765291' => __("Configuration de veille prise en charge", __FILE__), // Supported Sleep Configuration
			'00000202-0000-1000-8000-0026BB765291' => __("Configuration du transport de transfert prise en charge", __FILE__), // Supported Transfer Transport Configuration
			'00000206-0000-1000-8000-0026BB765291' => __("Configuration d'enregistrement vidéo prise en charge", __FILE__), // Supported Video Recording Configuration
			'00000114-0000-1000-8000-0026BB765291' => __("Configuration du flux vidéo prise en charge", __FILE__), // Supported Video Stream Configuration
			'000000B6-0000-1000-8000-0026BB765291' => __("Oscillation", __FILE__), // Swing Mode
			'0000022F-0000-1000-8000-0026BB765291' => __("Type de tapotement", __FILE__), // Tap Type
			'000000A8-0000-1000-8000-0026BB765291' => __("Mode du purificateur", __FILE__), // Target Air Purifier State
			'00000124-0000-1000-8000-0026BB765291' => __("Liste des télécommandes", __FILE__), // Target Control List
			'00000123-0000-1000-8000-0026BB765291' => __("Configuration de télécommande prise en charge", __FILE__), // Target Control Supported Configuration
			'00000032-0000-1000-8000-0026BB765291' => __("État cible de la porte", __FILE__), // Target Door State
			'000000BF-0000-1000-8000-0026BB765291' => __("Mode du ventilateur", __FILE__), // Target Fan State
			'000000B2-0000-1000-8000-0026BB765291' => __("Mode du chauffage-climatisation", __FILE__), // Target Heater-Cooler State
			'00000033-0000-1000-8000-0026BB765291' => __("Mode de chauffage cible", __FILE__), // Target Heating Cooling State
			'0000007B-0000-1000-8000-0026BB765291' => __("Inclinaison horizontale cible", __FILE__), // Target Horizontal Tilt Angle
			'000000B4-0000-1000-8000-0026BB765291' => __("Mode de l'humidificateur", __FILE__), // Target Humidifier-Dehumidifier State
			'00000137-0000-1000-8000-0026BB765291' => __("État de lecture cible", __FILE__), // Target Media State
			'0000007C-0000-1000-8000-0026BB765291' => __("Position cible", __FILE__), // Target Position
			'00000034-0000-1000-8000-0026BB765291' => __("Humidité cible", __FILE__), // Target Relative Humidity
			'00000035-0000-1000-8000-0026BB765291' => __("Température cible", __FILE__), // Target Temperature
			'000000C2-0000-1000-8000-0026BB765291' => __("Inclinaison cible", __FILE__), // Target Tilt Angle
			'0000007D-0000-1000-8000-0026BB765291' => __("Inclinaison verticale cible", __FILE__), // Target Vertical Tilt Angle
			'00000134-0000-1000-8000-0026BB765291' => __("Visibilité cible", __FILE__), // Target Visibility State
			'00000036-0000-1000-8000-0026BB765291' => __("Unité de température", __FILE__), // Temperature Display Units
			'0000021C-0000-1000-8000-0026BB765291' => __("Caméra tierce active", __FILE__), // Third Party Camera Active
			'00000704-0000-1000-8000-0026BB765291' => __("Point de contrôle Thread", __FILE__), // Thread Control Point
			'00000702-0000-1000-8000-0026BB765291' => __("Capacités du nœud Thread", __FILE__), // Thread Node Capabilities
			'00000706-0000-1000-8000-0026BB765291' => __("Version OpenThread", __FILE__), // Thread OpenThread Version
			'00000703-0000-1000-8000-0026BB765291' => __("État Thread", __FILE__), // Thread Status
			'00000231-0000-1000-8000-0026BB765291' => __("Jeton", __FILE__), // Token
			'00000242-0000-1000-8000-0026BB765291' => __("Puissance d'émission", __FILE__), // Transmit Power
			'00000061-0000-1000-8000-0026BB765291' => __("Délai de connexion du tunnel", __FILE__), // Tunnel Connection Timeout
			'00000060-0000-1000-8000-0026BB765291' => __("Annonce de l'accessoire tunnelisé", __FILE__), // Tunneled Accessory Advertising
			'00000059-0000-1000-8000-0026BB765291' => __("Accessoire tunnelisé connecté", __FILE__), // Tunneled Accessory Connected
			'00000058-0000-1000-8000-0026BB765291' => __("Numéro d'état de l'accessoire tunnelisé", __FILE__), // Tunneled Accessory State Number
			'000000D5-0000-1000-8000-0026BB765291' => __("Type de vanne", __FILE__), // Valve Type
			'00000037-0000-1000-8000-0026BB765291' => __("Version", __FILE__), // Version
			'00000229-0000-1000-8000-0026BB765291' => __("Analyse vidéo active", __FILE__), // Video Analysis Active
			'000000C8-0000-1000-8000-0026BB765291' => __("Densité de COV", __FILE__), // VOC Density
			'00000119-0000-1000-8000-0026BB765291' => __("Volume", __FILE__), // Volume
			'000000E9-0000-1000-8000-0026BB765291' => __("Type de contrôle du volume", __FILE__), // Volume Control Type
			'000000EA-0000-1000-8000-0026BB765291' => __("Sélecteur de volume", __FILE__), // Volume Selector
			'00000222-0000-1000-8000-0026BB765291' => __("Configuration de réveil", __FILE__), // Wake Configuration
			'00000211-0000-1000-8000-0026BB765291' => __("Configuration WAN", __FILE__), // WAN Configuration List
			'00000212-0000-1000-8000-0026BB765291' => __("État WAN", __FILE__), // WAN Status List
			'000000B5-0000-1000-8000-0026BB765291' => __("Niveau d'eau", __FILE__), // Water Level
			'0000022C-0000-1000-8000-0026BB765291' => __("Capacités Wi-Fi", __FILE__), // Wi-Fi Capabilities
			'0000022D-0000-1000-8000-0026BB765291' => __("Contrôle de la configuration Wi-Fi", __FILE__), // Wi-Fi Configuration Control
			'0000021E-0000-1000-8000-0026BB765291' => __("État du satellite Wi-Fi", __FILE__), // Wi-Fi Satellite Status
		];
		return $names;
	}

	private static function valueLabels() {
		static $values = null;
		if ($values === null) $values = [
			'000000A6-0000-1000-8000-0026BB765291' => [ // Accessory Flags
				1 => __("Configuration supplémentaire requise", __FILE__), // REQUIRES_ADDITIONAL_SETUP_BIT_MASK
			],
			'000000B0-0000-1000-8000-0026BB765291' => [ // Active
				0 => __("Inactif", __FILE__), // INACTIVE
				1 => __("Actif", __FILE__), // ACTIVE
			],
			'00000065-0000-1000-8000-0026BB765291' => [ // Air Particulate Size
				0 => __("2,5 µm", __FILE__), // _2_5_M
				1 => __("10 µm", __FILE__), // _10_M
			],
			'00000095-0000-1000-8000-0026BB765291' => [ // Air Quality
				0 => __("Inconnu", __FILE__), // UNKNOWN
				1 => __("Excellente", __FILE__), // EXCELLENT
				2 => __("Bonne", __FILE__), // GOOD
				3 => __("Moyenne", __FILE__), // FAIR
				4 => __("Médiocre", __FILE__), // INFERIOR
				5 => __("Mauvaise", __FILE__), // POOR
			],
			'0000021D-0000-1000-8000-0026BB765291' => [ // Camera Operating Mode Indicator
				0 => __("Désactiver", __FILE__), // DISABLE
				1 => __("Activer", __FILE__), // ENABLE
			],
			'00000092-0000-1000-8000-0026BB765291' => [ // Carbon Dioxide Detected
				0 => __("CO2 normal", __FILE__), // CO2_LEVELS_NORMAL
				1 => __("CO2 anormal", __FILE__), // CO2_LEVELS_ABNORMAL
			],
			'00000069-0000-1000-8000-0026BB765291' => [ // Carbon Monoxide Detected
				0 => __("CO normal", __FILE__), // CO_LEVELS_NORMAL
				1 => __("CO anormal", __FILE__), // CO_LEVELS_ABNORMAL
			],
			'0000008F-0000-1000-8000-0026BB765291' => [ // Charging State
				0 => __("Pas en charge", __FILE__), // NOT_CHARGING
				1 => __("En charge", __FILE__), // CHARGING
				2 => __("Non rechargeable", __FILE__), // NOT_CHARGEABLE
			],
			'000000DD-0000-1000-8000-0026BB765291' => [ // Closed Captions
				0 => __("Désactivé", __FILE__), // DISABLED
				1 => __("Activé", __FILE__), // ENABLED
			],
			'0000006A-0000-1000-8000-0026BB765291' => [ // Contact Sensor State
				0 => __("Contact détecté", __FILE__), // CONTACT_DETECTED
				1 => __("Contact non détecté", __FILE__), // CONTACT_NOT_DETECTED
			],
			'000000A9-0000-1000-8000-0026BB765291' => [ // Current Air Purifier State
				0 => __("Inactif", __FILE__), // INACTIVE
				1 => __("Au repos", __FILE__), // IDLE
				2 => __("Purification", __FILE__), // PURIFYING_AIR
			],
			'0000000E-0000-1000-8000-0026BB765291' => [ // Current Door State
				0 => __("Ouvert", __FILE__), // OPEN
				1 => __("Fermé", __FILE__), // CLOSED
				2 => __("Ouverture", __FILE__), // OPENING
				3 => __("Fermeture", __FILE__), // CLOSING
				4 => __("Arrêté", __FILE__), // STOPPED
			],
			'000000AF-0000-1000-8000-0026BB765291' => [ // Current Fan State
				0 => __("Inactif", __FILE__), // INACTIVE
				1 => __("Au repos", __FILE__), // IDLE
				2 => __("Ventilation", __FILE__), // BLOWING_AIR
			],
			'000000B1-0000-1000-8000-0026BB765291' => [ // Current Heater-Cooler State
				0 => __("Inactif", __FILE__), // INACTIVE
				1 => __("Au repos", __FILE__), // IDLE
				2 => __("Chauffage", __FILE__), // HEATING
				3 => __("Refroidissement", __FILE__), // COOLING
			],
			'0000000F-0000-1000-8000-0026BB765291' => [ // Current Heating Cooling State
				0 => __("Arrêt", __FILE__), // OFF
				1 => __("Chaud", __FILE__), // HEAT
				2 => __("Froid", __FILE__), // COOL
			],
			'000000B3-0000-1000-8000-0026BB765291' => [ // Current Humidifier-Dehumidifier State
				0 => __("Inactif", __FILE__), // INACTIVE
				1 => __("Au repos", __FILE__), // IDLE
				2 => __("Humidification", __FILE__), // HUMIDIFYING
				3 => __("Déshumidification", __FILE__), // DEHUMIDIFYING
			],
			'000000E0-0000-1000-8000-0026BB765291' => [ // Current Media State
				0 => __("Lecture", __FILE__), // PLAY
				1 => __("Pause", __FILE__), // PAUSE
				2 => __("Stop", __FILE__), // STOP
				4 => __("Chargement", __FILE__), // LOADING
				5 => __("Interrompu", __FILE__), // INTERRUPTED
			],
			'000000AA-0000-1000-8000-0026BB765291' => [ // Current Slat State
				0 => __("Fixe", __FILE__), // FIXED
				1 => __("Bloqué", __FILE__), // JAMMED
				2 => __("Oscillation", __FILE__), // SWINGING
			],
			'00000135-0000-1000-8000-0026BB765291' => [ // Current Visibility State
				0 => __("Affiché", __FILE__), // SHOWN
				1 => __("Masqué", __FILE__), // HIDDEN
			],
			'00000223-0000-1000-8000-0026BB765291' => [ // Event Snapshots Active
				0 => __("Désactiver", __FILE__), // DISABLE
				1 => __("Activer", __FILE__), // ENABLE
			],
			'000000AC-0000-1000-8000-0026BB765291' => [ // Filter Change Indication
				0 => __("Filtre OK", __FILE__), // FILTER_OK
				1 => __("Filtre à changer", __FILE__), // CHANGE_FILTER
			],
			'0000021B-0000-1000-8000-0026BB765291' => [ // HomeKit Camera Active
				0 => __("Arrêt", __FILE__), // OFF
				1 => __("Marche", __FILE__), // ON
			],
			'000000D2-0000-1000-8000-0026BB765291' => [ // In Use
				0 => __("Non utilisé", __FILE__), // NOT_IN_USE
				1 => __("En cours d'utilisation", __FILE__), // IN_USE
			],
			'000000DC-0000-1000-8000-0026BB765291' => [ // Input Device Type
				0 => __("Autre", __FILE__), // OTHER
				1 => __("TV", __FILE__), // TV
				2 => __("Enregistrement", __FILE__), // RECORDING
				3 => __("Tuner", __FILE__), // TUNER
				4 => __("Lecture", __FILE__), // PLAYBACK
				5 => __("Système audio", __FILE__), // AUDIO_SYSTEM
			],
			'000000DB-0000-1000-8000-0026BB765291' => [ // Input Source Type
				0 => __("Autre", __FILE__), // OTHER
				1 => __("Écran d'accueil", __FILE__), // HOME_SCREEN
				2 => __("Tuner", __FILE__), // TUNER
				3 => __("HDMI", __FILE__), // HDMI
				4 => __("Vidéo composite", __FILE__), // COMPOSITE_VIDEO
				5 => __("S-Vidéo", __FILE__), // S_VIDEO
				6 => __("Vidéo composante", __FILE__), // COMPONENT_VIDEO
				7 => __("DVI", __FILE__), // DVI
				8 => __("AirPlay", __FILE__), // AIRPLAY
				9 => __("USB", __FILE__), // USB
				10 => __("Application", __FILE__), // APPLICATION
			],
			'000000D6-0000-1000-8000-0026BB765291' => [ // Is Configured
				0 => __("Non configuré", __FILE__), // NOT_CONFIGURED
				1 => __("Configuré", __FILE__), // CONFIGURED
			],
			'00000070-0000-1000-8000-0026BB765291' => [ // Leak Detected
				0 => __("Pas de fuite", __FILE__), // LEAK_NOT_DETECTED
				1 => __("Fuite détectée", __FILE__), // LEAK_DETECTED
			],
			'0000001D-0000-1000-8000-0026BB765291' => [ // Lock Current State
				0 => __("Déverrouillé", __FILE__), // UNSECURED
				1 => __("Verrouillé", __FILE__), // SECURED
				2 => __("Bloqué", __FILE__), // JAMMED
				3 => __("Inconnu", __FILE__), // UNKNOWN
			],
			'0000001C-0000-1000-8000-0026BB765291' => [ // Lock Last Known Action
				0 => __("Verrouillé manuellement (intérieur)", __FILE__), // SECURED_PHYSICALLY_INTERIOR
				1 => __("Déverrouillé manuellement (intérieur)", __FILE__), // UNSECURED_PHYSICALLY_INTERIOR
				2 => __("Verrouillé manuellement (extérieur)", __FILE__), // SECURED_PHYSICALLY_EXTERIOR
				3 => __("Déverrouillé manuellement (extérieur)", __FILE__), // UNSECURED_PHYSICALLY_EXTERIOR
				4 => __("Verrouillé au clavier", __FILE__), // SECURED_BY_KEYPAD
				5 => __("Déverrouillé au clavier", __FILE__), // UNSECURED_BY_KEYPAD
				6 => __("Verrouillé à distance", __FILE__), // SECURED_REMOTELY
				7 => __("Déverrouillé à distance", __FILE__), // UNSECURED_REMOTELY
				8 => __("Verrouillé automatiquement", __FILE__), // SECURED_BY_AUTO_SECURE_TIMEOUT
				9 => __("Verrouillé manuellement", __FILE__), // SECURED_PHYSICALLY
				10 => __("Déverrouillé manuellement", __FILE__), // UNSECURED_PHYSICALLY
			],
			'000000A7-0000-1000-8000-0026BB765291' => [ // Lock Physical Controls
				0 => __("Verrouillage désactivé", __FILE__), // CONTROL_LOCK_DISABLED
				1 => __("Verrouillage activé", __FILE__), // CONTROL_LOCK_ENABLED
			],
			'0000001E-0000-1000-8000-0026BB765291' => [ // Lock Target State
				0 => __("Déverrouillé", __FILE__), // UNSECURED
				1 => __("Verrouillé", __FILE__), // SECURED
			],
			'00000215-0000-1000-8000-0026BB765291' => [ // Managed Network Enable
				0 => __("Désactivé", __FILE__), // DISABLED
				1 => __("Activé", __FILE__), // ENABLED
			],
			'00000227-0000-1000-8000-0026BB765291' => [ // Manually Disabled
				0 => __("Activé", __FILE__), // ENABLED
				1 => __("Désactivé", __FILE__), // DISABLED
			],
			'00000071-0000-1000-8000-0026BB765291' => [ // Occupancy Detected
				0 => __("Pas de présence", __FILE__), // OCCUPANCY_NOT_DETECTED
				1 => __("Présence détectée", __FILE__), // OCCUPANCY_DETECTED
			],
			'00000225-0000-1000-8000-0026BB765291' => [ // Periodic Snapshots Active
				0 => __("Désactiver", __FILE__), // DISABLE
				1 => __("Activer", __FILE__), // ENABLE
			],
			'000000E2-0000-1000-8000-0026BB765291' => [ // Picture Mode
				0 => __("Autre", __FILE__), // OTHER
				1 => __("Standard", __FILE__), // STANDARD
				2 => __("Calibré", __FILE__), // CALIBRATED
				3 => __("Calibré sombre", __FILE__), // CALIBRATED_DARK
				4 => __("Vif", __FILE__), // VIVID
				5 => __("Jeu", __FILE__), // GAME
				6 => __("Ordinateur", __FILE__), // COMPUTER
				7 => __("Personnalisé", __FILE__), // CUSTOM
			],
			'00000072-0000-1000-8000-0026BB765291' => [ // Position State
				0 => __("En fermeture", __FILE__), // DECREASING
				1 => __("En ouverture", __FILE__), // INCREASING
				2 => __("Arrêté", __FILE__), // STOPPED
			],
			'000000DF-0000-1000-8000-0026BB765291' => [ // Power Mode Selection
				0 => __("Afficher", __FILE__), // SHOW
				1 => __("Masquer", __FILE__), // HIDE
			],
			'000000D1-0000-1000-8000-0026BB765291' => [ // Program Mode
				0 => __("Aucun programme", __FILE__), // NO_PROGRAM_SCHEDULED
				1 => __("Programme planifié", __FILE__), // PROGRAM_SCHEDULED
				2 => __("Programme planifié (mode manuel)", __FILE__), // PROGRAM_SCHEDULED_MANUAL_MODE
			],
			'00000073-0000-1000-8000-0026BB765291' => [ // Programmable Switch Event
				0 => __("Appui simple", __FILE__), // SINGLE_PRESS
				1 => __("Double appui", __FILE__), // DOUBLE_PRESS
				2 => __("Appui long", __FILE__), // LONG_PRESS
			],
			'00000226-0000-1000-8000-0026BB765291' => [ // Recording Audio Active
				0 => __("Désactiver", __FILE__), // DISABLE
				1 => __("Activer", __FILE__), // ENABLE
			],
			'000000E1-0000-1000-8000-0026BB765291' => [ // Remote Key
				0 => __("Retour rapide", __FILE__), // REWIND
				1 => __("Avance rapide", __FILE__), // FAST_FORWARD
				2 => __("Piste suivante", __FILE__), // NEXT_TRACK
				3 => __("Piste précédente", __FILE__), // PREVIOUS_TRACK
				4 => __("Flèche haut", __FILE__), // ARROW_UP
				5 => __("Flèche bas", __FILE__), // ARROW_DOWN
				6 => __("Flèche gauche", __FILE__), // ARROW_LEFT
				7 => __("Flèche droite", __FILE__), // ARROW_RIGHT
				8 => __("Sélection", __FILE__), // SELECT
				9 => __("Retour", __FILE__), // BACK
				10 => __("Quitter", __FILE__), // EXIT
				11 => __("Lecture/Pause", __FILE__), // PLAY_PAUSE
				15 => __("Infos", __FILE__), // INFORMATION
			],
			'00000028-0000-1000-8000-0026BB765291' => [ // Rotation Direction
				0 => __("Sens horaire", __FILE__), // CLOCKWISE
				1 => __("Sens antihoraire", __FILE__), // COUNTER_CLOCKWISE
			],
			'0000020E-0000-1000-8000-0026BB765291' => [ // Router Status
				0 => __("Prêt", __FILE__), // READY
				1 => __("Pas prêt", __FILE__), // NOT_READY
			],
			'0000008E-0000-1000-8000-0026BB765291' => [ // Security System Alarm Type
				0 => __("Pas d'alarme", __FILE__), // NO_ALARM
				1 => __("Inconnu", __FILE__), // UNKNOWN
			],
			'00000066-0000-1000-8000-0026BB765291' => [ // Security System Current State
				0 => __("Armé présent", __FILE__), // STAY_ARM
				1 => __("Armé absent", __FILE__), // AWAY_ARM
				2 => __("Armé nuit", __FILE__), // NIGHT_ARM
				3 => __("Désarmé", __FILE__), // DISARMED
				4 => __("Alarme déclenchée", __FILE__), // ALARM_TRIGGERED
			],
			'00000067-0000-1000-8000-0026BB765291' => [ // Security System Target State
				0 => __("Armé présent", __FILE__), // STAY_ARM
				1 => __("Armé absent", __FILE__), // AWAY_ARM
				2 => __("Armé nuit", __FILE__), // NIGHT_ARM
				3 => __("Désarmer", __FILE__), // DISARM
			],
			'000000CD-0000-1000-8000-0026BB765291' => [ // Service Label Namespace
				0 => __("Points", __FILE__), // DOTS
				1 => __("Chiffres", __FILE__), // ARABIC_NUMERALS
			],
			'00000132-0000-1000-8000-0026BB765291' => [ // Siri Input Type
				0 => __("Bouton de l'Apple TV", __FILE__), // PUSH_BUTTON_TRIGGERED_APPLE_TV
			],
			'000000C0-0000-1000-8000-0026BB765291' => [ // Slat Type
				0 => __("Horizontal", __FILE__), // HORIZONTAL
				1 => __("Vertical", __FILE__), // VERTICAL
			],
			'000000E8-0000-1000-8000-0026BB765291' => [ // Sleep Discovery Mode
				0 => __("Non détectable", __FILE__), // NOT_DISCOVERABLE
				1 => __("Toujours détectable", __FILE__), // ALWAYS_DISCOVERABLE
			],
			'00000076-0000-1000-8000-0026BB765291' => [ // Smoke Detected
				0 => __("Pas de fumée", __FILE__), // SMOKE_NOT_DETECTED
				1 => __("Fumée détectée", __FILE__), // SMOKE_DETECTED
			],
			'00000077-0000-1000-8000-0026BB765291' => [ // Status Fault
				0 => __("Pas de défaut", __FILE__), // NO_FAULT
				1 => __("Défaut", __FILE__), // GENERAL_FAULT
			],
			'00000078-0000-1000-8000-0026BB765291' => [ // Status Jammed
				0 => __("Non bloqué", __FILE__), // NOT_JAMMED
				1 => __("Bloqué", __FILE__), // JAMMED
			],
			'00000079-0000-1000-8000-0026BB765291' => [ // Status Low Battery
				0 => __("Batterie normale", __FILE__), // BATTERY_LEVEL_NORMAL
				1 => __("Batterie faible", __FILE__), // BATTERY_LEVEL_LOW
			],
			'0000007A-0000-1000-8000-0026BB765291' => [ // Status Tampered
				0 => __("Pas de sabotage", __FILE__), // NOT_TAMPERED
				1 => __("Sabotage", __FILE__), // TAMPERED
			],
			'000000B6-0000-1000-8000-0026BB765291' => [ // Swing Mode
				0 => __("Oscillation désactivée", __FILE__), // SWING_DISABLED
				1 => __("Oscillation activée", __FILE__), // SWING_ENABLED
			],
			'000000A8-0000-1000-8000-0026BB765291' => [ // Target Air Purifier State
				0 => __("Manuel", __FILE__), // MANUAL
				1 => __("Auto", __FILE__), // AUTO
			],
			'00000032-0000-1000-8000-0026BB765291' => [ // Target Door State
				0 => __("Ouvert", __FILE__), // OPEN
				1 => __("Fermé", __FILE__), // CLOSED
			],
			'000000BF-0000-1000-8000-0026BB765291' => [ // Target Fan State
				0 => __("Manuel", __FILE__), // MANUAL
				1 => __("Auto", __FILE__), // AUTO
			],
			'000000B2-0000-1000-8000-0026BB765291' => [ // Target Heater-Cooler State
				0 => __("Auto", __FILE__), // AUTO
				1 => __("Chaud", __FILE__), // HEAT
				2 => __("Froid", __FILE__), // COOL
			],
			'00000033-0000-1000-8000-0026BB765291' => [ // Target Heating Cooling State
				0 => __("Arrêt", __FILE__), // OFF
				1 => __("Chaud", __FILE__), // HEAT
				2 => __("Froid", __FILE__), // COOL
				3 => __("Auto", __FILE__), // AUTO
			],
			'000000B4-0000-1000-8000-0026BB765291' => [ // Target Humidifier-Dehumidifier State
				0 => __("Humidificateur ou déshumidificateur", __FILE__), // HUMIDIFIER_OR_DEHUMIDIFIER
				1 => __("Humidificateur", __FILE__), // HUMIDIFIER
				2 => __("Déshumidificateur", __FILE__), // DEHUMIDIFIER
			],
			'00000137-0000-1000-8000-0026BB765291' => [ // Target Media State
				0 => __("Lecture", __FILE__), // PLAY
				1 => __("Pause", __FILE__), // PAUSE
				2 => __("Stop", __FILE__), // STOP
			],
			'00000134-0000-1000-8000-0026BB765291' => [ // Target Visibility State
				0 => __("Affiché", __FILE__), // SHOWN
				1 => __("Masqué", __FILE__), // HIDDEN
			],
			'00000036-0000-1000-8000-0026BB765291' => [ // Temperature Display Units
				0 => __("Celsius", __FILE__), // CELSIUS
				1 => __("Fahrenheit", __FILE__), // FAHRENHEIT
			],
			'0000021C-0000-1000-8000-0026BB765291' => [ // Third Party Camera Active
				0 => __("Arrêt", __FILE__), // OFF
				1 => __("Marche", __FILE__), // ON
			],
			'000000D5-0000-1000-8000-0026BB765291' => [ // Valve Type
				0 => __("Vanne", __FILE__), // GENERIC_VALVE
				1 => __("Arrosage", __FILE__), // IRRIGATION
				2 => __("Douche", __FILE__), // SHOWER_HEAD
				3 => __("Robinet", __FILE__), // WATER_FAUCET
			],
			'000000E9-0000-1000-8000-0026BB765291' => [ // Volume Control Type
				0 => __("Aucun", __FILE__), // NONE
				1 => __("Relatif", __FILE__), // RELATIVE
				2 => __("Relatif avec valeur actuelle", __FILE__), // RELATIVE_WITH_CURRENT
				3 => __("Absolu", __FILE__), // ABSOLUTE
			],
			'000000EA-0000-1000-8000-0026BB765291' => [ // Volume Selector
				0 => __("Augmenter", __FILE__), // INCREMENT
				1 => __("Diminuer", __FILE__), // DECREMENT
			],
			'0000021E-0000-1000-8000-0026BB765291' => [ // Wi-Fi Satellite Status
				0 => __("Inconnu", __FILE__), // UNKNOWN
				1 => __("Connecté", __FILE__), // CONNECTED
				2 => __("Non connecté", __FILE__), // NOT_CONNECTED
			],
			'000000AE-0000-1000-8000-0026BB765291' => [ // ancienne caractéristique (hors HAP-NodeJS)
				0 => __("Excellente", __FILE__), // V0
				1 => __("Bonne", __FILE__), // V1
				2 => __("Moyenne", __FILE__), // V2
			],
			'000000BE-0000-1000-8000-0026BB765291' => [ // ancienne caractéristique (hors HAP-NodeJS)
				0 => __("Manuel", __FILE__), // V0
				1 => __("Auto", __FILE__), // V1
			],
			'0000009E-0000-1000-8000-0026BB765291' => [ // ancienne caractéristique (hors HAP-NodeJS)
				0 => __("Démarrer la découverte", __FILE__), // V0
				1 => __("Arrêter la découverte", __FILE__), // V1
			],
			'627EA399-29D9-5DC8-9A02-08AE928F73D8' => [ // propriétaire (bruit) (hors HAP-NodeJS)
				0 => __("Inconnu", __FILE__), // V0
				1 => __("Silencieux", __FILE__), // V1
				2 => __("Calme", __FILE__), // V2
				3 => __("Légèrement bruyant", __FILE__), // V3
				4 => __("Bruyant", __FILE__), // V4
				5 => __("Trop bruyant", __FILE__), // V5
			],
			'36158AC8-5191-4AE2-9EF5-1D6722E88E3D' => [ // propriétaire (hors HAP-NodeJS)
				0 => __("Trop bas", __FILE__), // V0
				1 => __("OK", __FILE__), // V1
			],
		];
		return $values;
	}
}
