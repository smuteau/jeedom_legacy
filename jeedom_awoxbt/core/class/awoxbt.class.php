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

class awoxbt extends eqLogic {

  public static $_widgetPossibility = array('custom' => true);

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\adresse ne peut etre vide',__FILE__));
    }
  }

  public function preSave() {
    $this->setLogicalId($this->getConfiguration('addr'));
  }

  public function postSave() {
    $this->setLogicalId($this->getConfiguration('addr'));
    $apcupsCmd = $this->getCmd(null, 'envoi');
    if (!is_object($apcupsCmd)) {
      log::add('awoxbt', 'debug', 'Création de la commande d\'envoi');
      $awoxbtCmd = new awoxbtCmd();
      $awoxbtCmd->setName(__('Envoi', __FILE__));
      $awoxbtCmd->setEqLogic_id($this->id);
      $awoxbtCmd->setEqType('awoxbt');
      $awoxbtCmd->setLogicalId('envoi');
      $awoxbtCmd->setConfiguration('data', 'envoi');
      $awoxbtCmd->setType('action');
      $awoxbtCmd->setSubType('message');
      $awoxbtCmd->setDisplay('generic_type','LIGHT_SETCOLOR');
      $awoxbtCmd->save();
    }
  }

  public function sendWidget( $addr, $brightness, $color, $mode, $speed ) {
    $elogic = self::byLogicalId($addr, 'awoxbt');
    $this->setConfiguration('command',$command);
    $this->setConfiguration('argument',$arguments);
    // transformer 100 en ff et 1-9 en 01-09
    if ($white = '100') {
      $white = 'ff';
    }
    $lenght = strlen($white);
    if ($lenght = '1') {
      $white = '0' . $white;
    }
    $white = $white;
    $color = str_replace('#','',$color);
    switch ($type) {
      case 'candle' :
      if ($mode = '00') {
        $command = '0x0016';
        $argument = $brightness . $color;
      } else {
        $command = '0x0014';
        $argument = $brightness . $color . $effect . '00' . $speed . '00';
      }
      break;
      case 'candle6' :
      if ($mode = '00') {
        $command = '0x0019';
        $argument = $brightness . $color;
      } else {
        $command = '0x0017';
        $argument = $brightness . $color . $effect . '00' . $speed . '00';
      }
      break;
      case 'color' :
      if ($mode = '00') {
        $command = '0x0018';
        $argument = $brightness . $color;
      } else {
        $command = '0x0016';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
      case 'rainbow' :
      if ($mode = '00') {
        $command = '0x0018';
        $argument = $brightness . $color;
      } else {
        $command = '0x0016';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
      case 'garden' :
      if ($mode = '00') {
        $command = '0x001b';
        $argument = $brightness . $color;
      } else {
        $command = '0x0019';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
      case 'bluelabel' :
      if ($mode = '00') {
        $command = '0x001b';
        $argument = $brightness . $color;
      } else {
        $command = '0x0019';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
      case 'sphere' :
      if ($mode = '00') {
        $command = '0x001b';
        $argument = $brightness . $color;
      } else {
        $command = '0x0019';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
      case 'original' :
      if ($mode = '00') {
        $command = '0x001b';
        $argument = $brightness . $color;
      } else {
        $command = '0x0019';
        $argument = $brightness . $color . $effect . '00' . $speed;
      }
      break;
    }
    $command = $cmd->setConfiguration('command', $command);
    $argument = $cmd->setConfiguration('argument', $argument);
    awoxbt::sendCommand($eqLogic->getConfiguration('addr'));

  }

  public function sendCommand( $addr ) {
    $awoxbt = self::byLogicalId($addr, 'awoxbt');
    $command = $awoxbt->getConfiguration('command');
    $argument = $awoxbt->getConfiguration('argument');
    log::add('awoxbt', 'info', 'Commande : gatttool -b ' . $addr . ' --char-write -a ' . $command . ' -n ' . $argument);
    if ($awoxbt->getConfiguration('maitreesclave') == 'deporte'){
      $ip=$awoxbt->getConfiguration('addressip');
      $port=$awoxbt->getConfiguration('portssh');
      $user=$awoxbt->getConfiguration('user');
      $pass=$awoxbt->getConfiguration('password');
      if (!$connection = ssh2_connect($ip,$port)) {
        log::add('awoxbt', 'error', 'connexion SSH KO');
      }else{
        if (!ssh2_auth_password($connection,$user,$pass)){
          log::add('awoxbt', 'error', 'Authentification SSH KO');
        }else{
          log::add('awoxbt', 'debug', 'Commande par SSH');
          $hcion = ssh2_exec($connection, 'sudo hciconfig hciO up');
          $result = ssh2_exec($connection, 'sudo gatttool -b ' . $addr . ' --char-write -a ' . $command . ' -n ' . $argument);
          stream_set_blocking($result, true);
          $result = stream_get_contents($result);

          $closesession = ssh2_exec($connection, 'exit');
          stream_set_blocking($closesession, true);
          stream_get_contents($closesession);
        }
      }
    }else {
      exec('sudo hciconfig hciO up');
      exec('sudo gatttool -b ' . $addr . ' --char-write -a ' . $command . ' -n ' . $argument);
    }
  }



public function toHtml($_version = 'dashboard') {
  $replace = $this->preToHtml($_version);
  if (!is_array($replace)) {
    return $replace;
  }
  $version = jeedom::versionAlias($_version);
  if ($this->getDisplay('hideOn' . $version) == 1) {
    return '';
  }

  foreach ($this->getCmd('info') as $cmd) {
    $replace['#' . $cmd->getLogicalId() . '_history#'] = '';
    $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
    $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
    $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
    if ($cmd->getIsHistorized() == 1) {
      $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
    }
  }

$type = $this->getConfiguration('type');
switch ($type) {
  case 'candle' :
    $replace['#cmd#'] = '0x0016';
    break;
  case 'candle6' :
    $replace['#cmd#'] = '0x0019';
    break;
  case 'color' :
    $replace['#cmd#'] = '0x0018';
    break;
  case 'rainbow' :
    $replace['#cmd#'] = '0x0018';
    break;
  case 'garden' :
    $replace['#cmd#'] = '0x001b';
    break;
  case 'bluelabel' :
    $replace['#cmd#'] = '0x001b';
    break;
  case 'sphere' :
    $replace['#cmd#'] = '0x001b';
    break;
  case 'original' :
    $replace['#cmd#'] = '0x0010';
    break;
  }

$cmdlogic = awoxbtCmd::byEqLogicIdAndLogicalId($this->getId(),'envoi');
$replace['#cmdid#'] = $cmdlogic->getId();

if ($type == 'original') {
       return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'awoxbt_nocolor', 'awoxbt'));
} else {
       return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'awoxbt', 'awoxbt'));
}}

}

class awoxbtCmd extends cmd {
  /*     * *************************Attributs****************************** */



  /*     * ***********************Methode static*************************** */

  /*     * *********************Methode d'instance************************* */
  public function execute($_options = null) {
    log::add('awoxbt', 'info', 'Commande recue : ' . $_options['title'] . ' ' . $_options['message']);

    switch ($this->getType()) {

      case 'action' :
        $eqLogic = $this->getEqLogic();
        //log::add('awoxbt', 'debug', print_r($eqLogic,true));

        $eqLogic->setConfiguration('command',$_options['title']);
        $eqLogic->setConfiguration('argument',$_options['message']);
        $eqLogic->save();
        //log::add('awoxbt', 'debug', print_r($eqLogic,true));

        awoxbt::sendCommand($eqLogic->getConfiguration('addr'));
        return true;
    }

  }
}

?>
