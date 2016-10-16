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
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (init('apikey') != config::byKey('api') || config::byKey('api') == '') {
	connection::failed();
	echo 'Clef API non valide, vous n\'etes pas autorisé à effectuer cette action (jeeApi)';
	die();
}

$reader = init('name');
$json = file_get_contents('php://input');
log::add('btsniffer', 'debug', 'Body ' . print_r($json,true));
$body = json_decode($json, true);
//$rssi = $body['rssi'];

/*
$airmon = airmon::byLogicalId($mac, 'airmon');
if (!is_object($airmon)) {
	if (config::byKey('include_mode','airmon') != 1) {
		return false;
	}
	$airmon = new airmon();
	$airmon->setEqType_name('airmon');
	$airmon->setLogicalId($mac);
	$airmon->setName($mac);
	$airmon->setIsEnable(true);
	$airmon->setConfiguration('mac',$mac);
	$airmon->save();
	event::add('airmon::includeDevice',
	array(
		'state' => 1
	)
);
}
$airmon->setConfiguration('lastCommunication', date('Y-m-d H:i:s'));
$airmon->save();
$airmonCmd = airmonCmd::byEqLogicIdAndLogicalId($airmon->getId(),$reader);
if (!is_object($airmonCmd)) {
$airmonCmd = new airmonCmd();
$airmonCmd->setName($reader);
$airmonCmd->setEqLogic_id($airmon->getId());
$airmonCmd->setLogicalId($reader);
$airmonCmd->setType('info');
$airmonCmd->setSubType('binary');
$airmonCmd->setConfiguration('returnStateValue',0);
$airmonCmd->setConfiguration('returnStateTime',1);
}
$airmonCmd->setConfiguration('value', 1);
$airmonCmd->save();
$airmonCmd->event(1);
*/

return true;
?>
