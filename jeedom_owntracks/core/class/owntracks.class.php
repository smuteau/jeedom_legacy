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

class owntracks extends eqLogic {
  /*     * *************************Attributs****************************** */


  /*     * ***********************Methode static*************************** */


  public static function cron15() {
    foreach (eqLogic::byType('MQTT') as $mqttEqp) {
      log::add('owntracks', 'debug', 'check MQTT : ' . $mqttEqp->getLogicalId());
      if (strpos($mqttEqp->getLogicalId(), 'owntracks') !== false) {
        log::add('owntracks', 'debug', 'Owntracks trouvé');
        foreach ($mqttEqp->getCmd() as $cmd) {
          $owntracksid = $cmd->getConfiguration('topic');
          if (end(explode("/",$owntracksid)) != $cmd->getLogicalId()) continue;
          log::add('owntracks', 'debug', 'Owntracks : ' . $owntracksid);
          $eqpOwntracks = self::byLogicalId($owntracksid, 'owntracks');
          if (!is_object($eqpOwntracks)) {
            log::add('owntracks', 'info', 'création equipement ' . $owntracksid . ' - '.$cmd->getLogicalId());
            $eqpOwntracks = new owntracks();
            $eqpOwntracks->setEqType_name('owntracks');
            $eqpOwntracks->setLogicalId($owntracksid);
            $eqpOwntracks->setName($mqttEqp->getLogicalId() . ' - '.$cmd->getLogicalId());
            $eqpOwntracks->setIsEnable(true);
            $eqpOwntracks->save();
            //For add new command
            $eqpOwntracks->updateCommands($cmd->execCmd());
          }

          //Création / Mise a jour du listener
          $listener = listener::byClassAndFunction('owntracks', 'updater', array('eqp_id' => intval($eqpOwntracks->getId())));
          if (!is_object($listener)) {
            $listener = new listener();
            $listener->setClass('owntracks');
            $listener->setFunction('updater');
            $listener->setOption(array('eqp_id' => intval($eqpOwntracks->getId())));
            $listener->setEvent(array('#'.$cmd->getId().'#'));
            $listener->save();
          }
          //En cas de changement d'ID de commande MQTT
          else if (!in_array('#'.$cmd->getId().'#', $listener->getEvent()))
          {
            $listener->setEvent(array('#'.$cmd->getId().'#'));
            $listener->save();
            $eqpOwntracks->updateCommands($cmd->execCmd());
          }
        }
      }
    }
  }

  public static function updater($param)
  {
    //$param['event_id'];
    //$param['value'];
    //$param['eqp_id'];
    log::add('owntracks', 'debug', 'listener Updater : ' . $param['eqp_id'] .' / ' . $param['event_id'] .' / ' . $param['value']);
    try
    {
      $eqp = eqLogic::byId($param['eqp_id']);
      if (!is_object($eqp))
        throw new Exception('Impossible de récuperer equipement Owntracks avec ID : ' . $param['eqp_id']);
      //$eqp->updateCommands($param['value']); ne fonctionne pas, le message mqtt est tronqué (surement un bug du listener)
      $eqp->updateCommands(cmd::byId($param['event_id'])->execCmd());
      //Check for send data to other cmd
      log::add('owntracks', 'debug', 'Recherche de Cmd avec ID : ' . $eqp->getConfiguration('geolocDynamicCmd'));
      $cmdGeoloc = cmd::byId($eqp->getConfiguration('geolocDynamicCmd'));
      if (is_object($cmdGeoloc)) {
        log::add('owntracks', 'debug', 'Lien vers cmd Geolocalisation trouvé');
        $cmdLat = $eqp->getCmd(null,'location.lat');
        $cmdLon = $eqp->getCmd(null,'location.lon');
        if (is_object($cmdLat) && is_object($cmdLon))
        {

          $cmdGeoloc->event($cmdLat->execCmd().','.$cmdLon->execCmd());
        }
      }
    } catch (Exception $e) {
      log::add('owntracks', 'error', $e->getMessage());
    }
  }

  /*     * *********************Methode d'instance************************* */
  public function updateCommands($value)
  {
      try {
        $infos = json_decode($value);
        $type = $infos->{'_type'};

        foreach ($infos as $key => $value) {
          if ($key == '_type') continue;
          if ($key == 'batt')
          {
            $this->batteryStatus($value);
            continue;
          }
          $cmd = $this->getCmd(null, $type.'.'.$key);
          if (!is_object($cmd)) {
            log::add('owntracks', 'debug', 'création commande ' . $type.'.'.$key . ' valeur ' . $value);
            $cmd = new owntracksCmd();
            $cmd->setEqLogic_id($this->getId());
            $cmd->setEqType('owntracks');
            $cmd->setIsVisible(1);
            $cmd->setIsHistorized(0);
            $cmd->setType('info');
            if (is_numeric($value)) $cmd->setSubType('numeric');
            else $cmd->setSubType('string');
            $cmd->setName( $type.'.'.$key );
            $cmd->setConfiguration('name', $type.'.'.$key);
            $cmd->setLogicalId($type.'.'.$key);
            $cmd->save();
          }

          //Mise a jour
          if ($cmd->getConfiguration('value') != $value)
          {
            $cmd->setConfiguration('value', $value);
            $cmd->save();
          }

          $cmd->event($value);
        }
      } catch (Exception $e) {
        log::add('owntracks', 'error', $e->getMessage());
      }
  }

  public function preRemove(){
    $listener = listener::byClassAndFunction('owntracks', 'updater', array('eqp_id' => intval($this->getId())));
		if (is_object($listener)) {
			$listener->remove();
		}
  }

  /*     * **********************Getteur Setteur*************************** */

}

class owntracksCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*     * ***********************Methode static*************************** */

  /*     * *********************Methode d'instance************************* */
  public function execute($_options = null) {
    return $this->getConfiguration('value');
  }
}

?>
