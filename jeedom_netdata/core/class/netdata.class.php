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


class netdata extends eqLogic {

  public static function deamon_info() {
    $return = array();
    $return['log'] = 'netdata_node';
    $return['state'] = 'nok';
    $pid = trim( shell_exec ('ps ax | grep "netdata" | grep -v "grep" | awk \'{print $1}\'') );
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
    log::add('netdata', 'info', 'Lancement du service netdata');

    $plugin_path = realpath(dirname(__FILE__) . '/../../resources');
    $result = exec('nohup sudo /opt/netdata/usr/sbin/netdata >> ' . log::getPathToLog('netdata_node') . ' 2>&1 &');
    log::add('netdata', 'debug', 'nohup sudo /opt/netdata/usr/sbin/netdata');
    if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
      log::add('netdata', 'error', $result);
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
      log::add('netdata', 'error', 'Impossible de lancer le démon netdata, vérifiez le port', 'unableStartDeamon');
      return false;
    }
    message::removeAll('netdata', 'unableStartDeamon');
    log::add('netdata', 'info', 'Démon netdata lancé');
    return true;
  }

  public static function deamon_stop() {
    exec('kill $(ps aux | grep "netdata" | awk \'{print $2}\')');
    log::add('netdata', 'info', 'Arrêt du service netdata');
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('kill -9 $(ps aux | grep "netdata" | awk \'{print $2}\')');
    }
    $deamon_info = self::deamon_info();
    if ($deamon_info['state'] == 'ok') {
      sleep(1);
      exec('sudo kill $(ps aux | grep "netdata" | awk \'{print $2}\')');
    }
  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'netdata_dep';
    $return['progress_file'] = '/tmp/netdata_dep';
    if (file_exists('/opt/netdata/etc/netdata/netdata.conf')) {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    if (file_exists('/tmp/netdata_dep')) {
			return;
		}
    log::add('netdata','info','Installation de netdata');
    $resource_path = realpath(dirname(__FILE__) . '/../../resources');
    if (strpos($_SERVER['SERVER_SOFTWARE'],'Apache') !== false) {
      $server = 'apache';
    } else {
      $server = 'nginx'; //welldone !!!
    }
    //$url  = config::byKey('externalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api') . '&type=netdata&';
    exec('/bin/bash ' . $resource_path . '/reverse.sh ' . $resource_path . ' netdata ' . $server . ' > ' . log::getPathToLog('netdata_dep') . ' 2>&1 &');
    passthru('/bin/bash ' . $resource_path . '/install.sh ' . $resource_path . ' >> ' . log::getPathToLog('netdata_dep') . ' 2>&1');
  }

}



class netdataCmd extends cmd {

  public function execute($_options = null) {

  }

}
