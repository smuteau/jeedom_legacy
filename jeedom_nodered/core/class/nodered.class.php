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


class nodered extends eqLogic {
  /*     * *************************Attributs****************************** */
  public static function health() {
    $return = array();
    $pid = trim( shell_exec ('ps ax | grep "node-red" | grep -v "grep" | wc -l') );
    if ($pid != '' && $pid != '0') {
      $service = true;
    } else {
      $service = false;
    }
    $return[] = array(
      'test' => __('Node-Red', __FILE__),
      'result' => ($service) ? __('OK', __FILE__) : __('NOK', __FILE__),
      'advice' => ($service) ? '' : __('Indique si le service node-red est démarré', __FILE__),
      'state' => $service,
    );
    return $return;
  }


  public static function deamon_info() {
    $return = array();
    $return['log'] = 'nodered_node';
    $return['state'] = 'nok';
    $pid = trim( shell_exec ('ps ax | grep "node-red" | grep -v "grep" | awk \'{print $1}\'') );
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
    log::add('nodered', 'info', 'Lancement du service Node-Red');

    $archi=exec("lscpu | grep Architecture | awk '{ print \$2 }'");
    if ($archi == "x86_64") {
      exec("whereis node-red | awk '{print \$NF}'", $retour);
      $cmd = $retour[0];
    } else {
      exec("whereis node-red-pi | awk '{print \$NF}'", $retour);
      $cmd = $retour[0];
    }
    $plugin_path = realpath(dirname(__FILE__) . '/../../resources');
    $result = exec('nohup ' . $cmd . ' -s ' . $plugin_path . '/settings.js >> ' . log::getPathToLog('nodered_node') . ' 2>&1 &');
    log::add('nodered', 'debug', 'nohup ' . $cmd . ' -s ' . $plugin_path . '/settings.js');
    if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
      log::add('nodered', 'error', $result);
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
      log::add('nodered', 'error', 'Impossible de lancer le démon nodered, vérifiez le port', 'unableStartDeamon');
      return false;
    }
    message::removeAll('nodered', 'unableStartDeamon');
    log::add('nodered', 'info', 'Démon nodered lancé');
    return true;
  }

  public static function deamon_stop() {
    exec('kill $(ps aux | grep "node-red" | awk \'{print $2}\')');
    log::add('nodered', 'info', 'Arrêt du service node-red');
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('kill -9 $(ps aux | grep "node-red" | awk \'{print $2}\')');
    }
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('sudo kill $(ps aux | grep "node-red" | awk \'{print $2}\')');
    }
  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'nodered_dep';
    exec("whereis node-red | awk '{print \$NF}'", $retour);
    $cmd = $retour[0];
    $return['progress_file'] = '/tmp/nodered_dep';
    if ($cmd != '') {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    if (file_exists('/tmp/nodered_dep')) {
			return;
		}
    log::add('nodered','info','Installation de nodered');
    $resource_path = realpath(dirname(__FILE__) . '/../../resources');
    if (strpos($_SERVER['SERVER_SOFTWARE'],'Apache') !== false) {
      $server = 'apache';
    } else {
      $server = 'nginx'; //welldone !!!
    }
    //$url  = config::byKey('externalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api') . '&type=nodered&';
    exec('/bin/bash ' . $resource_path . '/reverse.sh ' . $resource_path . ' nodered ' . $server . ' > ' . log::getPathToLog('nodered_dep') . ' 2>&1 &');
    passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' >> ' . log::getPathToLog('nodered_dep') . ' 2>&1');
  }

}



class noderedCmd extends cmd {

  public function execute($_options = null) {

  }

}
