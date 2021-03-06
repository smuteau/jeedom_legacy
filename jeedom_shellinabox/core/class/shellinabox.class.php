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


class shellinabox extends eqLogic {
    /*     * *************************Attributs****************************** */

    public static function dependancy_info() {
      $return = array();
      $return['log'] = 'shellinabox_dep';
      $cmd = "dpkg -l | grep shellinabox";
      exec($cmd, $output, $return_var);
      if ($output[0] != "") {
        $return['state'] = 'ok';
      } else {
        $return['state'] = 'nok';
      }
      return $return;
    }

    public static function dependancy_install() {
      $cmd = 'sudo apt-get -y install shellinabox > ' . log::getPathToLog('shellinabox_dep') . ' 2>&1';
      exec($cmd);
      $resource_path = realpath(dirname(__FILE__) . '/../../resources');
      if (strpos($_SERVER['SERVER_SOFTWARE'],'Apache') !== false) {
        $server = 'apache';
      } else {
        $server = 'nginx'; //welldone !!!
      }
      //$url  = config::byKey('externalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api') . '&type=shellinabox&';
      passthru('/bin/bash ' . $resource_path . '/reverse.sh ' . $resource_path . ' shellinabox ' . $server . ' >> ' . log::getPathToLog('shellinabox_dep') . ' 2>&1 &');
    }

	}



class shellinaboxCmd extends cmd {

	public function execute($_options = null) {

    }

}
