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

class lbc extends eqLogic {

  public static function cron30() {
    foreach (eqLogic::byType('lbc', true) as $lbc) {
        foreach($lbc->getCmd() as $cmd){
          log::add('lbc', 'debug', 'Pull cron - ' . $lbc->getName() . ' : ' . $cmd->getName());
          $result = lbc::getInformations($cmd->getConfiguration('url'));
          log::add('lbc', 'debug', $result);
          $cmd->setConfiguration('value', $result);
          $cmd->save();
          log::add('lbc', 'debug', 'value : ' . $result);
          $alert = str_replace('#','',$lbc->getConfiguration('alert'));
          if ($result != "[]" && $alert != '') {
            $cmd = cmd::byId($alert);
            $options['title'] = "Alerte Leboncoin";
            $options['message'] = "Nouvelles annonces : " . $result;
            $cmd->execCmd($options);
          }
        }
    }

  }

  public static function postUpdate() {
    foreach (eqLogic::byType('lbc', true) as $lbc) {
        foreach($lbc->getCmd() as $cmd){
          log::add('lbc', 'debug', 'Pull cron - ' . $lbc->getName() . ' : ' . $cmd->getName());
          $result = lbc::getInformations($cmd->getConfiguration('url'));
          log::add('lbc', 'debug', $result);
          $cmd->setConfiguration('value', $result);
          $cmd->save();
          log::add('lbc', 'debug', 'value : ' . $result);
          $alert = str_replace('#','',$lbc->getConfiguration('alert'));
          if ($result != "" && $alert != '') {
            $cmd = cmd::byId($alert);
            $options['title'] = "Alerte Leboncoin";
            $options['message'] = "Nouvelles annonces : " . $result;
            $cmd->execCmd($options);
          }
        }
    }

  }

  public function getInformations($url) {
      log::add('lbc', 'debug', $url);
      $value = '';
      $virgule = 0;

      $html = new DOMDocument();
      $html->loadHTMLFile($url);
      log::add('lbc', 'debug', print_r($html, true));

      //sleep(rand(1,5));
      foreach ($html->getElementsByTagName("a") as $result) {
        // est-ce bien une annonce ?
        if (false === strpos($result->getAttribute("class"), "list_item")) {
          continue;
        }
        // pas d'ID, pas d'annonce
        if (!preg_match('/([0-9]+)\.htm.*/', $result->getAttribute("href"), $m)) {
          continue;
        }
        $title = $result->getAttribute("title");
        $link = $result->getAttribute("href");
        $id = $m[1];
        $price = '';
        // recherche du prix
        foreach ($result->getElementsByTagName("h3") AS $node) {
            $class = (string) $node->getAttribute("class");
            if (false !== strpos($class, "item_price")) {
                if (preg_match("#[0-9 ]+#", $node->nodeValue, $m)) {
                    $price = (int)str_replace(" ", "", trim($m[0]));
                }
            }
        }


            $i = 0;
            foreach ($result->getElementsByTagName("p") AS $node) {
                $class = (string) $node->getAttribute("class");
                if (false !== strpos($class, "item_supp")) {
                    $value = trim($node->nodeValue);
                    if ($i == 0) { // catégorie
                        if (false !== strpos($value, "(pro)")) {
                            //professionnel
                        }
                        $category = $value;
                    } elseif ($i == 1) { // localisation
                        if (false !== strpos($value, "/")) {
                            $value = explode("/", $value);
                            $ad->setCountry(trim($value[1]))
                                ->setCity(trim($value[0]));
                        } else {
                            $ad->setCountry(trim($value));
                        }
                    } elseif ($i == 2) { // date de l'annonce + urgent
                        $spans = $node->getElementsByTagName("span");
                        if ($spans->length > 0) {
                            $ad->setUrgent(true);
                            $node->removeChild($spans->item(0));
                            $value = trim($node->nodeValue);
                        }
                        $dateStr = preg_replace("#\s+#", " ", $value);
                        $aDate = explode(' ', $dateStr);
                        $aDate[1] = trim($aDate[1], ",");
                        if (false !== strpos($dateStr, 'Aujourd')) {
                            $time = strtotime(date("Y-m-d")." 00:00:00");
                        } elseif (false !== strpos($dateStr, 'Hier')) {
                            $time = strtotime(date("Y-m-d")." 00:00:00");
                            $time = strtotime("-1 day", $time);
                        } else {
                            if (!isset(self::$months[$aDate[1]])) {
                                continue;
                            }
                            $time = strtotime(date("Y")."-".self::$months[$aDate[1]]."-".$aDate[0]);
                        }
                        $aTime = explode(":", $aDate[count($aDate) - 1]);
                        $time += (int)$aTime[0] * 3600 + (int)$aTime[1] * 60;
                        if ($timeToday < $time) {
                            $time = strtotime("-1 year", $time);
                        }
                        $ad->setDate($time);
                    }
                    $i++;
                }
            }

      }
/*
      foreach($t as $v)
      {
        $url = $v->previousSibling->attributes->getNamedItem('href')->nodeValue;
        $date =  str_replace(Array('Hier','Aujourd\'hui'), Array('yesterday','today'), preg_replace('#\s+#', ' ', $v->childNodes->item(0)->nodeValue));
        $ts = strtotime($date);
        $title = preg_replace('#\s+#', ' ', $v->childNodes->item(0)->nextSibling->nextSibling->childNodes->item(0)->nodeValue);

        log::add('lbc', 'debug', $title);

        if( $ts > time() - 46*60 && $ts < time() - 14*60  ){
          if ($virgule == 0) {
            $virgule = 1;
          } else {
            $value = $value . ',';
          }
          $value = $value . '{"url":"' . $url . '","title":"' . $title . '"}';
          }
      }
      */
      if ($virgule == 0) {
        $virgule = 1;
      } else {
        $value = $value . '<br>';
      }
      $value = $value . '<a href=""' . $url . '">' . $title . ' (' . $price . ' €)</a> : ' . $url;
      return $value;
  }
}

class lbcCmd extends cmd {
  public function execute($_options = null) {
          return $this->getConfiguration('value');
    }
}

?>
