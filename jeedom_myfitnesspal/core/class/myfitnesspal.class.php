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

class myfitnesspal extends eqLogic {
  public static function cronHourly() {
    foreach (eqLogic::byType('myfitnesspal',1) as $myfitnesspal) {
        log::add('myfitnesspal', 'debug', 'pull cron');
        $myfitnesspal->getInformations();
    }

  }

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'myfitnesspal_dep';
    $return['progress_file'] = '/tmp/myfitnesspal_dep';
    $mfp = realpath(dirname(__FILE__) . '/../../node/node_modules/mfp');
    $request = realpath(dirname(__FILE__) . '/../../node/node_modules/request');
    $return['progress_file'] = '/tmp/myfitnesspal_dep';
    if (is_dir($mfp) && is_dir($request)) {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    log::add('myfitnesspal','info','Installation des dépéndances nodejs');
    $resource_path = realpath(dirname(__FILE__) . '/../../resources');
    passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' > ' . log::getPathToLog('myfitnesspal_dep') . ' 2>&1 &');
  }

  public function preSave() {
    $this->setLogicalId($this->getConfiguration('user'));
  }

  public function postUpdate() {
    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'calories');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Calories', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('calories');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'carbs');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Carbs', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('carbs');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'fat');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Gras', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('fat');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'protein');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Protéines', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('protein');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'cholesterol');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Choléstérol', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('cholesterol');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'sodium');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Sodium', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('sodium');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'sugar');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Sucre', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('sugar');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $cmdlogic = myfitnesspalCmd::byEqLogicIdAndLogicalId($this->getId(),'fiber');
    if (!is_object($cmdlogic)) {
      $cmdlogic = new myfitnesspalCmd();
      $cmdlogic->setName(__('Fibre', __FILE__));
      $cmdlogic->setEqLogic_id($this->id);
      $cmdlogic->setLogicalId('fiber');
      $cmdlogic->setType('info');
      $cmdlogic->setSubType('numeric');
      $cmdlogic->save();
    }

    $this->getInformations();
  }

  public function getInformations() {
    $dependancy_info = self::dependancy_info();
    if ($dependancy_info['state'] != 'ok') {
      throw new Exception(__('Dépendances non prêtes', __FILE__));
    }
    if (!config::byKey('internalPort')) {
      $url = config::byKey('internalProtocol') . config::byKey('internalAddr') . config::byKey('internalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api');
    } else {
      $url = config::byKey('internalProtocol') . config::byKey('internalAddr'). ':' . config::byKey('internalPort') . config::byKey('internalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api');
    }
    $service_path = realpath(dirname(__FILE__) . '/../../node/');
    $user = $this->getConfiguration('user');

    $cmd = 'sudo nice -n 19 nodejs ' . $service_path . '/myfitnesspal.js ' . $url . ' ' . $user;
    log::add('myfitnesspal', 'debug', $cmd);
    $result = exec($cmd . ' >> ' . log::getPathToLog('myfitnesspal_node') . ' 2>&1 &');
    if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
      log::add('myfitnesspal', 'error', $result);
      return false;
    }
  }

  public static function event() {
    $user = init('user');

    $myfit = self::byLogicalId($user, 'myfitnesspal');
    if (is_object($myfit)) {
      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'calories');
      $myfitnesspal->setConfiguration('value', init('calories'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('calories'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'carbs');
      $myfitnesspal->setConfiguration('value', init('carbs'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('carbs'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'fat');
      $myfitnesspal->setConfiguration('value', init('fat'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('fat'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'protein');
      $myfitnesspal->setConfiguration('value', init('protein'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('protein'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'cholesterol');
      $myfitnesspal->setConfiguration('value', init('cholesterol'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('cholesterol'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'sodium');
      $myfitnesspal->setConfiguration('value', init('sodium'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('sodium'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'sugar');
      $myfitnesspal->setConfiguration('value', init('sugar'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('sugar'));

      $myfitnesspal = myfitnesspalCmd::byEqLogicIdAndLogicalId($myfit->getId(),'fiber');
      $myfitnesspal->setConfiguration('value', init('fiber'));
      $myfitnesspal->save();
      $myfitnesspal->event(init('fiber'));
    }
  }

}

class myfitnesspalCmd extends cmd {

  public function execute($_options = null) {
              return $this->getConfiguration('value');
    }

}

?>
