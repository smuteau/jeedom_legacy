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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


class btsniffer extends eqLogic {


  public static function deamon_info() {
    $return = array();
    $return['log'] = 'btsniffer_node';
    $return['state'] = 'nok';
    $pid = trim( shell_exec ('ps ax | grep "btsniffer/node/btsniffer.js" | grep -v "grep" | wc -l') );
    if ($pid != '' && $pid != '0') {
      $return['state'] = 'ok';
    }
    $return['launchable'] = 'ok';
    return $return;
  }

  public static function deamon_start($_debug = false) {
    self::deamon_stop();
    $deamon_info = self::deamon_info();
    if ($deamon_info['launchable'] != 'ok') {
      throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
    }
    log::add('btsniffer', 'info', 'Lancement du démon btsniffer');

    $service_path = realpath(dirname(__FILE__) . '/../../node/');

    $port = str_replace('hci', '', jeedom::getBluetoothMapping(config::byKey('port', 'btsniffer',0)));

    if (!config::byKey('internalPort')) {
      $url = config::byKey('internalProtocol') . '127.0.0.1' . config::byKey('internalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api');
    } else {
      $url = config::byKey('internalProtocol') . '127.0.0.1' . ':' . config::byKey('internalPort') . config::byKey('internalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api');
    }
    $name = 'master';

    $cmd = 'nodejs ' . $service_path . '/btsniffer.js "' . $url . '" "' . $name . '"';
    $cmd = 'NOBLE_HCI_DEVICE_ID=' . $port . ' ' . $cmd;

    log::add('btsniffer', 'debug', $cmd);
    $result = exec('sudo ' . $cmd . ' >> ' . log::getPathToLog('btsniffer_node') . ' 2>&1 &');
    if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
      log::add('btsniffer', 'error', $result);
      return false;
    }

    $i = 0;
    while ($i < 30) {
      $deamon_info = self::deamon_info();
      if ($deamon_info['state'] == 'ok') {
        break;
      }
      sleep(1);
      $i++;
    }
    if ($i >= 30) {
      log::add('btsniffer', 'error', 'Impossible de lancer le démon btsniffer, vérifiez le port', 'unableStartDeamon');
      return false;
    }
    message::removeAll('btsniffer', 'unableStartDeamon');
    log::add('btsniffer', 'info', 'Démon btsniffer lancé');
    return true;

  }

  public static function deamon_stop() {
    exec('kill $(ps aux | grep "btsniffer/node/btsniffer.js" | awk \'{print $2}\')');
    log::add('btsniffer', 'info', 'Arrêt du service btsniffer');
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('kill -9 $(ps aux | grep "btsniffer/node/btsniffer.js" | awk \'{print $2}\')');
    }
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('sudo kill -9 $(ps aux | grep "btsniffer/node/btsniffer.js" | awk \'{print $2}\')');
    }
  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'btsniffer_dep';
    $return['progress_file'] = '/tmp/btsniffer_dep';
    $noble = realpath(dirname(__FILE__) . '/../../node/node_modules/noble');
    $request = realpath(dirname(__FILE__) . '/../../node/node_modules/request');
    $return['progress_file'] = '/tmp/btsniffer_dep';
    if (is_dir($noble) && is_dir($request)) {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    log::add('btsniffer','info','Installation des dépéndances nodejs');
    $resource_path = realpath(dirname(__FILE__) . '/../../resources');
    passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' > ' . log::getPathToLog('btsniffer_dep') . ' 2>&1 &');
  }

  public static function event() {
    $reader = init('name');
    $id = init('id');
    $json = file_get_contents('php://input');
    log::add('btsniffer', 'debug', 'Body ' . print_r($json,true));
    $body = json_decode($json, true);
    $rssi = $body['rssi'];
    if (!isset($body['device'])) {
      log::add('btsniffer', 'debug', 'Equipement sans nom, pas de création');
      die;
    }
    $device = $body['device'];
    $btsniffer = self::byLogicalId($id, 'btsniffer');
    if (!is_object($btsniffer)) {
      if (config::byKey('include_mode','btsniffer') != 1) {
        return false;
      }
      $btsniffer = new btsniffer();
      $btsniffer->setEqType_name('btsniffer');
      $btsniffer->setLogicalId($id);
      $btsniffer->setConfiguration('addr', $id);
      $btsniffer->setName($device);
      $btsniffer->setIsEnable(true);
      event::add('btsniffer::includeDevice',
      array(
        'state' => $state
      )
    );
  }
  $btsniffer->setConfiguration('lastCommunication', date('Y-m-d H:i:s'));
  if ($device != $btsniffer->getConfiguration('device')) {
    $btsniffer->setConfiguration('device', $device);
  }
  $btsniffer->save();
  $btsnifferCmd = btsnifferCmd::byEqLogicIdAndLogicalId($btsniffer->getId(),$reader);
  if ($rssi != "off") {
    $value = 1;
  } else {
    $value = 0;
  }
  if (!is_object($btsnifferCmd)) {
    $btsnifferCmd = new btsnifferCmd();
    $btsnifferCmd->setName($reader);
    $btsnifferCmd->setEqLogic_id($btsniffer->getId());
    $btsnifferCmd->setLogicalId($reader);
    $btsnifferCmd->setType('info');
    $btsnifferCmd->setSubType('binary');
  }
  $btsnifferCmd->setConfiguration('value', $value);
  $btsnifferCmd->setConfiguration('rssi', $rssi);
  $btsnifferCmd->setConfiguration('reader', $reader);
  $btsnifferCmd->save();
  $btsnifferCmd->event($value);

}

}


class btsnifferCmd extends cmd {

}
