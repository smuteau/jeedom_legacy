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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if (init('action') == 'getGeoloc') {
      ajax::success(openpaths::getGeoloc());
    }

     if (init('action') == 'getopenpaths') {
        $openpaths = openpaths::byId(init('id'));
        if (!is_object($openpaths)) {
            throw new Exception(__('openpaths inconnu verifié l\'id', __FILE__));
        }
        $return = utils::o2a($openpaths);
        $return['cmd'] = array();
        foreach ($openpaths->getCmd() as $cmd) {
            $cmd_info = utils::o2a($cmd);
            $cmd_info['value'] = $cmd->execCmd(null, 0);
            $return['cmd'][] = $cmd_info;
        }
        ajax::success($return);
     }


    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
