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

class xivo extends eqLogic {
  /*     * *************************Attributs****************************** */


  /*     * ***********************Methode static*************************** */


  public static function cron15() {
    foreach (eqLogic::byType('xivo') as $xivo) {
      log::add('xivo', 'debug', 'pull cron');
      $xivo->getInformations();
    }

  }


  /*     * *********************Methode d'instance************************* */

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\'adresse ne peut être vide',__FILE__));
    }
  }

  public function preSave() {
    $this->setLogicalId($this->getConfiguration('addr'));
  }


  /*     * **********************Getteur Setteur*************************** */

  public function getInformations() {
    $addr = $this->getConfiguration('addr');
    log::add('xivo', 'info', 'getInformations ' . $addr);
    foreach ($this->getCmd() as $cmd) {
      if($cmd->getConfiguration('type') === "pull"){
        if ($cmd->getConfiguration('url') != '') {
          log::add('xivo', 'debug', 'Vérification ' . $cmd->getName());
          $url = $cmd->getConfiguration('url');
          $value = file($url);
          $cmd->setConfiguration('value', $value);
          $cmd->save();
          $cmd->event($value);
        } else {
          log::add('xivo', 'debug', 'URL manquante sur ' . $cmd->getName());
        }
      }
    }
    return ;
  }

  public function xivoCall($ip) {
    $elogic = eqLogic::byLogicalId($ip, 'xivo');
    log::add('xivo', 'info', 'get Xivo Call ' . $ip);
    foreach ($_GET as $key => $value) {
      if($key != "api" && $key != "type"){
        log::add('xivo', 'debug', 'Get ' . $key);
        $cmdlogic = cmd::byEqLogicIdAndLogicalId($elogic->getId(),$key);
        if (!is_object($cmdlogic)) {
          log::add('xivo', 'debug', 'création commande ' . $key . ' valeur ' . $value);
          $cmdlogic = new xivoCmd();
          $cmdlogic->setEqLogic_id($elogic->getId());
          $cmdlogic->setEqType('xivo');
          $cmdlogic->setIsVisible(1);
          $cmdlogic->setIsHistorized(0);
          $cmdlogic->setSubType('string');
          $cmdlogic->setType('info');
          $cmdlogic->setName( $key );
          $cmdlogic->setConfiguration('url', $key);
          $cmdlogic->setLogicalId($key);
          $cmdlogic->save();
        }
        $cmdlogic->setConfiguration('value', $value);
        $cmdlogic->save();
        $cmdlogic->event($value);
      }
    }
    return ;
  }

  public static function event() {
    $ip = getClientIp();
    log::add('xivo', 'debug', 'Event recu de ' . $ip);
    xivo::xivoCall($ip);
	}

}

class xivoCmd extends cmd {
  /*     * *************************Attributs****************************** */



  /*     * ***********************Methode static*************************** */

  /*     * *********************Methode d'instance************************* */
  public function execute($_options = null) {


            switch ($this->getType()) {
				          case 'info' :
          					return $this->getConfiguration('value');
          					break;
                case 'action' :
					          $request = $this->getConfiguration('request');
                    switch ($this->getSubType()) {
                        case 'slider':
                            $request = str_replace('#slider#', $value, $request);
                            break;
                        case 'color':
                            $request = str_replace('#color#', $_options['color'], $request);
                            break;
                        case 'message':
              							if ($_options != null)  {
              								$replace = array('#title#', '#message#');
              								$replaceBy = array($_options['title'], $_options['message']);
              								if ( $_options['title'] == '') {
              									throw new Exception(__('Le sujet ne peuvent être vide', __FILE__));
              								}
              								$request = str_replace($replace, $replaceBy, $request);

              							}
              							else
              							 $request = 1;
                            break;
						            default : $request == null ?  1 : $request;
					          }

					$eqLogic = $this->getEqLogic();
					$LogicalID = $this->getLogicalId();

					$url = $this->getConfiguration('url');
          $value = file($url);

					return $value;
			}
			return true;
    }
}

?>
