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


class airmon extends eqLogic {

  public static function deamon_info() {
    $return = array();
    $return['log'] = 'airmon_node';
    $return['state'] = 'nok';
    $pid = trim( shell_exec ('ps ax | grep "airmon/node/airmon.js" | grep -v "grep" | wc -l') );
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
    log::add('airmon', 'info', 'Lancement du démon airmon');

    if (!file_exists('/tmp/airmon')) {
      mkdir('/tmp/airmon');
    }

    $url = network::getNetworkAccess('internal') . '/plugins/airmon/core/api/jeeAirmon.php?apikey=' . config::byKey('api');
    $name = 'master';
    $sensor_path = realpath(dirname(__FILE__) . '/../../node');
    $cmd = 'sudo nice -n 19 nodejs ' . $sensor_path . '/airmon.js "' . $url . '" "' . $name . '"';

    log::add('airmon', 'debug', 'Lancement démon airmon : ' . $cmd);

    $result = exec('nohup ' . $cmd . ' >> ' . log::getPathToLog('airmon_node') . ' 2>&1 &');
    if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
      log::add('airmon', 'error', $result);
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
      log::add('airmon', 'error', 'Impossible de lancer le démon airmon, vérifiez le port', 'unableStartDeamon');
      return false;
    }
    message::removeAll('airmon', 'unableStartDeamon');
    log::add('airmon', 'info', 'Démon airmon lancé');
    return true;
  }

  public static function deamon_stop() {
    exec('kill $(ps aux | grep "airmon/node/airmon.js" | awk \'{print $2}\')');
    log::add('airmon', 'info', 'Arrêt du service airmon');
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('kill -9 $(ps aux | grep "airmon/node/airmon.js" | awk \'{print $2}\')');
    }
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('sudo kill -9 $(ps aux | grep "airmon/node/airmon.js" | awk \'{print $2}\')');
    }
  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'airmon_dep';
    $request = realpath(dirname(__FILE__) . '/../../node/node_modules/request');
    $return['progress_file'] = '/tmp/airmon_dep';
    if (is_dir($request)) {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    log::add('airmon','info','Installation des dépéndances nodejs');
    $resource_path = realpath(dirname(__FILE__) . '/../../resources');
    passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' > ' . log::getPathToLog('airmon_dep') . ' 2>&1 &');
  }

}

class airmonCmd extends cmd {
  public function execute($_options = null) {
    switch ($this->getType()) {
      case 'info' :
      return $this->getConfiguration('value');
      break;
      case 'action' :
      return true;
      break;
    }

  }
