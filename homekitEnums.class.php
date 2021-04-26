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

/* * ***************************Includes********************************/

class homekitEnums {
	public static $_knownUUID = [
	  'services' => [
		'4AAAF930-0DEC-11E5-B939-0800200C9A66' => 'koogeek',
		'C635EF5C-5BBC-4F96-B7DA-6669069A4B32' => 'VOCOlinc rssi',
		'961EBB65-A1E3-4F34-BD31-86552706FE40' => 'VOCOlinc time',
		'3138B537-E830-4F52-90A7-D6FDB000BF97' => 'VOCOlinc firmware',
		'9715BF53-AB63-4449-8DC7-2785D617390A' => 'MIIO Gateway Service',
		'F3963625-878E-4170-89CE-0C0D821A1635' => 'Aqara Energy',
		'E863F007-079E-48FF-8F27-9C2605A29F52' => 'Eve Logging Service',
		'0000008D-0000-1000-8000-0026BB765291' => 'Eve Room Service',
		'E863F00B-079E-48FF-8F27-9C2605A29F52' => 'Eve Extend Link Service'
	  ],
	  'characteristics' => [
		'4AAAF931-0DEC-11E5-B939-0800200C9A66' => 'realtime_energy',
		'4AAAF932-0DEC-11E5-B939-0800200C9A66' => 'current_hour_data',
		'4AAAF933-0DEC-11E5-B939-0800200C9A66' => 'hour_data_today',
		'4AAAF934-0DEC-11E5-B939-0800200C9A66' => 'hour_data_yesterday',
		'4AAAF935-0DEC-11E5-B939-0800200C9A66' => 'hour_data_2_days_before',
		'4AAAF936-0DEC-11E5-B939-0800200C9A66' => 'hour_data_3_days_before',
		'4AAAF937-0DEC-11E5-B939-0800200C9A66' => 'hour_data_4_days_before',
		'4AAAF938-0DEC-11E5-B939-0800200C9A66' => 'hour_data_5_days_before',
		'4AAAF939-0DEC-11E5-B939-0800200C9A66' => 'hour_data_6_days_before',
		'4AAAF93A-0DEC-11E5-B939-0800200C9A66' => 'hour_data_7_days_before',
		'4AAAF93B-0DEC-11E5-B939-0800200C9A66' => 'day_data_this_month',
		'4AAAF93C-0DEC-11E5-B939-0800200C9A66' => 'day_data_last_month',
		'4AAAF93D-0DEC-11E5-B939-0800200C9A66' => 'month_data_this_year',
		'4AAAF93E-0DEC-11E5-B939-0800200C9A66' => 'month_data_last_year',
		'4AAAF93F-0DEC-11E5-B939-0800200C9A66' => 'running_time',
		'36158AC8-5191-4AE2-9EF5-1D6722E88E3D' => 'Water Level',
		'E863F10A-079E-48FF-8F27-9C2605A29F52' => 'Volt',
		'E863F126-079E-48FF-8F27-9C2605A29F52' => 'Ampere',
		'E863F10D-079E-48FF-8F27-9C2605A29F52' => 'CurrentPowerConsumption',
		'E863F10C-079E-48FF-8F27-9C2605A29F52' => 'TotalPowerConsumption',
		'2ACF6D35-4FBF-4689-8787-6D5C4BA3A263' => 'AQI',
		'E863F10B-079E-48FF-8F27-9C2605A29F52' => 'PPM',
		'E863F129-079E-48FF-8F27-9C2605A29F52' => 'TimesOpened',
		'E863F10F-079E-48FF-8F27-9C2605A29F52' => 'AirPressure',
		'E863F11A-079E-48FF-8F27-9C2605A29F52' => 'LastActivation',
		'E863F118-079E-48FF-8F27-9C2605A29F52' => 'Char118',
		'E863F119-079E-48FF-8F27-9C2605A29F52' => 'Char119',
		'E863F112-079E-48FF-8F27-9C2605A29F52' => 'ResetTotal',
		'E863F120-079E-48FF-8F27-9C2605A29F52' => 'Sensitivity',
		'E863F12D-079E-48FF-8F27-9C2605A29F52' => 'Duration',
		'E863F132-079E-48FF-8F27-9C2605A29F52' => 'AQX2',
		'49C8AE5A-A3A5-41AB-BF1F-12D5654F9F41' => 'WindSpeed',
		'46f1284c-1912-421b-82f5-eb75008b167e' => 'WindDirection',
		'CD65A9AB-85AD-494A-B2BD-2F380084134D' => 'WeatherCondition',
		'D24ECC1E-6FAD-4FB5-8137-5AF88BD5E857' => 'Visibility',
		'B3BBFABC-D78C-5B8D-948C-5DAC1EE2CDE5' => 'NoiseLevel',
		'627EA399-29D9-5DC8-9A02-08AE928F73D8' => 'NoiseQuality',
		'E863F155-079E-48FF-8F27-9C2605A29F52' => 'Eve Child ID?',
		'E863F157-079E-48FF-8F27-9C2605A29F52' => 'Eve Child Online?',
		'E863F130-079E-48FF-8F27-9C2605A29F52' => 'Elevation'
	  ]
	];
	
	public static $_homekitValues = [
	  '00000065-0000-1000-8000-0026BB765291' => [
		0 => '2.5',
		1 => '10',
	  ],
	  '00000095-0000-1000-8000-0026BB765291' => [
		0 => 'Unknown',
		1 => 'Excellent',
		2 => 'Good',
		3 => 'Fair',
		4 => 'Inferior',
		5 => 'Poor',
	  ],
	  '00000092-0000-1000-8000-0026BB765291' => [
		0 => 'CO2 Levels Normal',
		1 => 'CO2 Levels Abnormal',
	  ],
	  '00000069-0000-1000-8000-0026BB765291' => [
		0 => 'CO Levels Normal',
		1 => 'CO Levels Abnormal',
	  ],
	  '0000008F-0000-1000-8000-0026BB765291' => [
		0 => 'Not Charging',
		1 => 'Charging',
		2 => 'Not Chargeable',
	  ],
	  '0000006A-0000-1000-8000-0026BB765291' => [
		0 => 'Contact Detected',
		1 => 'Contact Not Detected',
	  ],
	  '000000A9-0000-1000-8000-0026BB765291' => [
		0 => 'Inactive',
		1 => 'Idle',
		2 => 'Purifying Air',
	  ],
	  '0000000E-0000-1000-8000-0026BB765291' => [
		0 => 'Open',
		1 => 'Closed',
		2 => 'Opening',
		3 => 'Closing',
		4 => 'Stopped',
	  ],
	  '000000AF-0000-1000-8000-0026BB765291' => [
		0 => 'Inactive',
		1 => 'Idle',
		2 => 'Blowing Air',
	  ],
	  '000000B1-0000-1000-8000-0026BB765291' => [
		0 => 'Inactive',
		1 => 'Idle',
		2 => 'Heating',
		3 => 'Cooling',
	  ],
	  '0000000F-0000-1000-8000-0026BB765291' => [
		0 => 'Off',
		1 => 'Heat',
		2 => 'Cool',
	  ],
	  '000000B3-0000-1000-8000-0026BB765291' => [
		0 => 'Inactive',
		1 => 'Idle',
		2 => 'Humidifying',
		3 => 'Dehumidifying',
	  ],
	  '000000AA-0000-1000-8000-0026BB765291' => [
		0 => 'Fixed',
		1 => 'Jammed',
		2 => 'Swinging',
	  ],
	  '000000AC-0000-1000-8000-0026BB765291' => [
		0 => 'Filter OK',
		1 => 'Change Filter',
	  ],
	  '000000D2-0000-1000-8000-0026BB765291' => [
		0 => 'Not In Use',
		1 => 'In Use',
	  ],
	  '000000D6-0000-1000-8000-0026BB765291' => [
		0 => 'Not Configured',
		1 => 'Configured',
	  ],
	  '00000070-0000-1000-8000-0026BB765291' => [
		0 => 'Leak Not Detected',
		1 => 'Leak Detected',
	  ],
	  '0000001D-0000-1000-8000-0026BB765291' => [
		0 => 'Unsecured',
		1 => 'Secured',
		2 => 'Jammed',
		3 => 'Unknown',
	  ],
	  '0000001C-0000-1000-8000-0026BB765291' => [
		0 => 'Secured Physically Interior',
		1 => 'Unsecured Physically Interior',
		2 => 'Secured Physically Exterior',
		3 => 'Unsecured Physically Exterior',
		4 => 'Secured By Keypad',
		5 => 'Unsecured By Keypad',
		6 => 'Secured Remotely',
		7 => 'Unsecured Remotely',
		8 => 'Secured By Auto Secure Timeout',
	  ],
	  '000000A7-0000-1000-8000-0026BB765291' => [
		0 => 'Control Lock Disabled',
		1 => 'Control Lock Enabled',
	  ],
	  '0000001E-0000-1000-8000-0026BB765291' => [
		0 => 'Unsecured',
		1 => 'Secured',
	  ],
	  '00000071-0000-1000-8000-0026BB765291' => [
		0 => 'Occupancy Not Detected',
		1 => 'Occupancy Detected',
	  ],
	  '00000072-0000-1000-8000-0026BB765291' => [
		0 => 'Decreasing',
		1 => 'Increasing',
		2 => 'Stopped',
	  ],
	  '000000D1-0000-1000-8000-0026BB765291' => [
		0 => 'No Program Scheduled',
		1 => 'Program Scheduled',
		2 => 'Program Scheduled Manual Mode',
	  ],
	  '00000073-0000-1000-8000-0026BB765291' => [
		0 => 'Single Press',
		1 => 'Double Press',
		2 => 'Long Press',
	  ],
	  '00000028-0000-1000-8000-0026BB765291' => [
		0 => 'Clockwise',
		1 => 'Counter Clockwise',
	  ],
	  '00000066-0000-1000-8000-0026BB765291' => [
		0 => 'Stay Arm',
		1 => 'Away Arm',
		2 => 'Night Arm',
		3 => 'Disarmed',
		4 => 'Alarm Triggered',
	  ],
	  '00000067-0000-1000-8000-0026BB765291' => [
		0 => 'Stay Arm',
		1 => 'Away Arm',
		2 => 'Night Arm',
		3 => 'Disarm',
	  ],
	  '000000CD-0000-1000-8000-0026BB765291' => [
		0 => 'Dots',
		1 => 'Arabic Numerals',
	  ],
	  '000000C0-0000-1000-8000-0026BB765291' => [
		0 => 'Horizontal',
		1 => 'Vertical',
	  ],
	  '00000076-0000-1000-8000-0026BB765291' => [
		0 => 'Smoke Not Detected',
		1 => 'Smoke Detected',
	  ],
	  '00000077-0000-1000-8000-0026BB765291' => [
		0 => 'No Fault',
		1 => 'General Fault',
	  ],
	  '00000078-0000-1000-8000-0026BB765291' => [
		0 => 'Not Jammed',
		1 => 'Jammed',
	  ],
	  '00000079-0000-1000-8000-0026BB765291' => [
		0 => 'Battery Level Normal',
		1 => 'Battery Level Low',
	  ],
	  '0000007A-0000-1000-8000-0026BB765291' => [
		0 => 'Not Tampered',
		1 => 'Tampered',
	  ],
	  '000000B6-0000-1000-8000-0026BB765291' => [
		0 => 'Swing Disabled',
		1 => 'Swing Enabled',
	  ],
	  '000000A8-0000-1000-8000-0026BB765291' => [
		0 => 'Manual',
		1 => 'Auto',
	  ],
	  '000000AE-0000-1000-8000-0026BB765291' => [
		0 => 'Excellent',
		1 => 'Good',
		2 => 'Fair',
	  ],
	  '00000032-0000-1000-8000-0026BB765291' => [
		0 => 'Open',
		1 => 'Closed',
	  ],
	  '000000BF-0000-1000-8000-0026BB765291' => [
		0 => 'Manual',
		1 => 'Auto',
	  ],
	  '000000B2-0000-1000-8000-0026BB765291' => [
		0 => 'Auto',
		1 => 'Heat',
		2 => 'Cool',
	  ],
	  '00000033-0000-1000-8000-0026BB765291' => [
		0 => 'Off',
		1 => 'Heat',
		2 => 'Cool',
		3 => 'Auto',
	  ],
	  '000000B4-0000-1000-8000-0026BB765291' => [
		0 => 'Humidifier Or Dehumidifier',
		1 => 'Humidifier',
		2 => 'Dehumidifier',
	  ],
	  '000000BE-0000-1000-8000-0026BB765291' => [
		0 => 'Manual',
		1 => 'Auto',
	  ],
	  '00000036-0000-1000-8000-0026BB765291' => [
		0 => 'Celsius',
		1 => 'Fahrenheit',
	  ],
	  '000000D5-0000-1000-8000-0026BB765291' => [
		0 => 'Generic Valve',
		1 => 'Irrigation',
		2 => 'Shower Head',
		3 => 'Water Faucet',
	  ],
	  '00000226-0000-1000-8000-0026BB765291' => [
		0 => 'Disable',
		1 => 'Enable',
	  ],
	  '0000021D-0000-1000-8000-0026BB765291' => [
		0 => 'Disable',
		1 => 'Enable',
	  ],
	  '00000223-0000-1000-8000-0026BB765291' => [
		0 => 'Disable',
		1 => 'Enable',
	  ],
	  '0000021B-0000-1000-8000-0026BB765291' => [
		0 => 'Off',
		1 => 'On',
	  ],
	  '00000227-0000-1000-8000-0026BB765291' => [
		0 => 'Enabled',
		1 => 'Disabled',
	  ],
	  '0000021C-0000-1000-8000-0026BB765291' => [
		0 => 'Off',
		1 => 'On',
	  ],
	  '00000225-0000-1000-8000-0026BB765291' => [
		0 => 'Disable',
		1 => 'Enable',
	  ],
	  '0000020E-0000-1000-8000-0026BB765291' => [
		0 => 'Ready',
		1 => 'Not Ready',
	  ],
	  '00000215-0000-1000-8000-0026BB765291' => [
		0 => 'Disabled',
		1 => 'Enabled',
		2 => 'Unknown',
	  ],
	  '0000021E-0000-1000-8000-0026BB765291' => [
		0 => 'Unknown',
		1 => 'Connected',
		2 => 'Not Connected',
	  ],
	  '000000E8-0000-1000-8000-0026BB765291' => [
		0 => 'Not Discoverable',
		1 => 'Always Discoverable',
	  ],
	  '000000DD-0000-1000-8000-0026BB765291' => [
		0 => 'Disabled',
		1 => 'Enabled',
	  ],
	  '00000137-0000-1000-8000-0026BB765291' => [
		0 => 'Play',
		1 => 'Pause',
		2 => 'Stop',
	  ],
	  '000000E2-0000-1000-8000-0026BB765291' => [
		0 => 'Other',
		1 => 'Standard',
		2 => 'Calibrated',
		3 => 'Calibrated Dark',
		4 => 'Vivid',
		5 => 'Game',
		6 => 'Computer',
		7 => 'Custom',
	  ],
	  '000000DF-0000-1000-8000-0026BB765291' => [
		0 => 'Show',
		1 => 'Hide',
	  ],
	  '000000E1-0000-1000-8000-0026BB765291' => [
		0 => 'Rewind',
		1 => 'Fast Forward',
		2 => 'Next Track',
		3 => 'Previous Track',
		4 => 'Arrow Up',
		5 => 'Arrow Down',
		6 => 'Arrow Left',
		7 => 'Arrow Right',
		8 => 'Select',
		9 => 'Back',
		10 => 'Exit',
		11 => 'Play Pause',
		15 => 'Information',
	  ],
	  '000000DB-0000-1000-8000-0026BB765291' => [
		0 => 'Other',
		1 => 'Home Screen',
		2 => 'Tuner',
		3 => 'HDMI',
		4 => 'Composite Video',
		5 => 'S Video',
		6 => 'Component Video',
		7 => 'DVI',
		8 => 'Airplay',
		9 => 'USB',
		10 => 'Application',
	  ],
	  '000000DC-0000-1000-8000-0026BB765291' => [
		0 => 'Other',
		1 => 'TV',
		2 => 'Recording',
		3 => 'Tuner',
		4 => 'Playback',
		5 => 'Audio System',
	  ],
	  '00000135-0000-1000-8000-0026BB765291' => [
		0 => 'Shown',
		1 => 'Hidden',
	  ],
	  '00000134-0000-1000-8000-0026BB765291' => [
		0 => 'Shown',
		1 => 'Hidden',
	  ],
	  '000000E9-0000-1000-8000-0026BB765291' => [
		0 => 'None',
		1 => 'Relative',
		2 => 'Relative With Current',
		3 => 'Absolute',
	  ],
	  '000000EA-0000-1000-8000-0026BB765291' => [
		0 => 'Increment',
		1 => 'Decrement',
	  ],
	  '00000132-0000-1000-8000-0026BB765291' => [
		0 => 'Push Button Triggered Apple TV',
	  ],
	  '0000009E-0000-1000-8000-0026BB765291' => [
		0 => 'Start Discovery',
		1 => 'Stop Discovery',
	  ],
	  '627EA399-29D9-5DC8-9A02-08AE928F73D8' => [
		0 => 'Unknown',
		1 => 'Silent',
		2 => 'Calm',
		3 => 'Lightly Noisy',
		4 => 'Noisy',
		5 => 'Too Noisy',
	  ],
	  '36158AC8-5191-4AE2-9EF5-1D6722E88E3D' => [
		0 => 'Too Low',
		1 => 'Ok',
	  ],
	];
}
