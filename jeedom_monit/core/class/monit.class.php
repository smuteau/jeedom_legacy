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

class monit extends eqLogic {
  /*     * *************************Attributs****************************** */


  /*     * ***********************Methode static*************************** */

  public static function health() {
    $return = array();
    $pid = trim( shell_exec ('ps ax | grep "monit" | grep -v "grep" | wc -l') );
    if ($pid != '' && $pid != '0') {
      $service = true;
    } else {
      $service = false;
    }
    $return[] = array(
      'test' => __('Monit', __FILE__),
      'result' => ($service) ? __('OK', __FILE__) : __('NOK', __FILE__),
      'advice' => ($service) ? '' : __('Indique si le service monitd est démarré', __FILE__),
      'state' => $service,
    );
    return $return;
  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'monit_dep';
    $cmd = "dpkg -l | grep ' monit '";
    exec($cmd, $output, $return_var);
    if ($output[0] != "") {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    $cmd = 'sudo apt-get -y install monit > ' . log::getPathToLog('monit_dep') . ' 2>&1 &';
    exec($cmd);
  }

  public function configFiles() {
    $resource_path = realpath(dirname(__FILE__) . '/../../resources/');
    log::add('monit','debug','Installation des conf : ' . config::byKey('nginx','monit') . ' ' . config::byKey('php','monit') . ' ' . config::byKey('mysql','monit'));
    if (config::byKey('nginx','monit') == 1) {
      exec('sudo cp ' . $resource_path . '/nginx.conf /etc/monit/conf.d/');
    } else {
      exec('sudo rm /etc/monit/conf.d/nginx.conf');
    }
    if (config::byKey('php','monit') == 1) {
      exec('sudo cp ' . $resource_path . '/php.conf /etc/monit/conf.d/');
    } else {
      exec('sudo rm /etc/monit/conf.d/php.conf');
    }
    if (config::byKey('mysql','monit') == 1) {
      exec('sudo cp ' . $resource_path . '/mysql.conf /etc/monit/conf.d/');
    } else {
      exec('sudo rm /etc/monit/conf.d/mysql.conf');
    }
    if (config::byKey('apache','monit') == 1) {
      exec('sudo cp ' . $resource_path . '/apache.conf /etc/monit/conf.d/');
    } else {
      exec('sudo rm /etc/monit/conf.d/apache.conf');
    }
    exec('sudo cp ' . $resource_path . '/httpd.conf /etc/monit/conf.d/');
    exec('sudo service monit reload');
  }

}

class monitCmd extends cmd {

  public function execute($_options = null) {

  }

}

?>
