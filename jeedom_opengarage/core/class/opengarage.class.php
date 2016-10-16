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

class opengarage extends eqLogic {

  public function cron() {
    foreach (self::byType('opengarage', true) as $opengarage) {
      $opengarage->refresh();
    }
  }

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\'adresse ne peut être vide',__FILE__));
    }
  }

  public function preSave() {
    $this->setLogicalId($this->getConfiguration('addr'));
  }


  public function postUpdate() {
    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'reboot');
		if (!is_object($cmd)) {
			$cmd = new opengarageCmd();
			$cmd->setLogicalId('reboot');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Redémarrage', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
    $cmd->setConfiguration('url','reboot');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'door');
		if (!is_object($cmd)) {
			$cmd = new opengarageCmd();
			$cmd->setLogicalId('door');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Ouverture Porte', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
    $cmd->setConfiguration('url','click');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'status');
		if (!is_object($cmd)) {
			$cmd = new opengarageCmd();
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Porte', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'dist');
		if (!is_object($cmd)) {
			$cmd = new opengarageCmd();
			$cmd->setLogicalId('dist');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Distance', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

    $this->refresh();
  }

  public function refresh() {
    $addr = trim($this->getConfiguration('addr'));
    $url = 'http://' . $addr . '/jc';
    $result = file_get_contents($url);

    $parsed_json = json_decode($result, true);
    log::add('opengarage', 'debug', 'Retour : ' . print_r($parsed_json,true));
    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'status');
    if (is_object($cmd)) {
			$cmd->setConfiguration('value',$parsed_json['door']);
      $cmd->save();
      $cmd->event($parsed_json['door']);
		}
    $cmd = opengarageCmd::byEqLogicIdAndLogicalId($this->getId(),'dist');
    if (is_object($cmd)) {
			$cmd->setConfiguration('value',$parsed_json['dist']);
      $cmd->save();
      $cmd->event($parsed_json['dist']);
		}
  }

}

class opengarageCmd extends cmd {
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
          $addr = trim($eqLogic->getConfiguration('addr'));
          $pass = trim($eqLogic->getConfiguration('pass'));
          $url = 'http://' . $addr . '/cc?' . $this->getConfiguration('url') . '=1&dkey=' . $pass;
          file_get_contents($url);

					return true;
			}
			return true;
    }
}

?>
