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
if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
sendVarToJs('manageProtocol_slaveId', init('slave_id'));
?>
<div id='div_noderedProtocoleAlert' style="display: none;"></div>
<a class="btn btn-success btn-xs pull-right" id="bt_saveRfxProtocole"><i class="fa fa-check-circle"></i> Enregistrer</a><br/><br/>

<table id="table_noderedProtocole" class="table table-bordered table-condensed tablesorter">
    <thead>
        <tr>
            <th>{{ID}}</th>
            <th>{{Nom}}</th>
            <th>{{Actif}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>0</td>
            <td>GPIO HummingBoard</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::0" /></td>
        </tr>
        <tr>
            <td>1</td>
            <td>GPIO Raspberry Pi</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::1" /></td>
        </tr>
        <tr>
            <td>2</td>
            <td>GPIO BeagleBone</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::2" /></td>
        </tr>
        <tr>
            <td>3</td>
            <td>GPIO Galileo/Edision</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::3" /></td>
        </tr>
        <tr>
            <td>4</td>
            <td>GPIO Blend Micro</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::4" /></td>
        </tr>
        <tr>
            <td>5</td>
            <td>GPIO LightBlue Bean</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::5" /></td>
        </tr>
        <tr>
            <td>6</td>
            <td>GPIO Electirc Imp</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::6" /></td>
        </tr>
        <tr>
            <td>7</td>
            <td>GPIO Spark Core</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::7" /></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Arduino/Firmata</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::8" /></td>
        </tr>
        <tr>
            <td>9</td>
            <td>PiTFT</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::9" /></td>
        </tr>
        <tr>
            <td>10</td>
            <td>Pibrella</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::10" /></td>
        </tr>
        <tr>
            <td>11</td>
            <td>PiBord</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::11" /></td>
        </tr>
        <tr>
            <td>12</td>
            <td>Sensors (DHT, BMP085, DS18B20)</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::12" /></td>
        </tr>
        <tr>
            <td>13</td>
            <td>Pushover</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::13" /></td>
        </tr>
        <tr>
            <td>14</td>
            <td>Notify My Android</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::14" /></td>
        </tr>
        <tr>
            <td>15</td>
            <td>Pushbullet</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::15" /></td>
        </tr>
        <tr>
            <td>16</td>
            <td>Prowl</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::16" /></td>
        </tr>
        <tr>
            <td>17</td>
            <td>XMPP</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::17" /></td>
        </tr>
        <tr>
            <td>18</td>
            <td>IRC</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::18" /></td>
        </tr>
        <tr>
            <td>19</td>
            <td>Slack</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::19" /></td>
        </tr>
        <tr>
            <td>20</td>
            <td>Pusher</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::20" /></td>
        </tr>
        <tr>
            <td>21</td>
            <td>Stockage Cloud (Dropbox, Box, AWS, Flickr)</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::21" /></td>
        </tr>
        <tr>
            <td>22</td>
            <td>Musique (MPD, Mopidy)</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::22" /></td>
        </tr>
        <tr>
            <td>23</td>
            <td>Fitbit</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::23" /></td>
        </tr>
        <tr>
            <td>24</td>
            <td>Jawboneup</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::24" /></td>
        </tr>
        <tr>
            <td>25</td>
            <td>Strava</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::25" /></td>
        </tr>
        <tr>
            <td>26</td>
            <td>KNX/EIBD</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::26" /></td>
        </tr>
        <tr>
            <td>27</td>
            <td>OpenZwave</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::27" /></td>
        </tr>
        <tr>
            <td>28</td>
            <td>RFXcom</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::28" /></td>
        </tr>
        <tr>
            <td>29</td>
            <td>OWFS</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::29" /></td>
        </tr>
        <tr>
            <td>30</td>
            <td>Nest</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::30" /></td>
        </tr>
        <tr>
            <td>31</td>
            <td>Hue</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::31" /></td>
        </tr>
        <tr>
            <td>32</td>
            <td>Spark-Core</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::32" /></td>
        </tr>
        <tr>
            <td>33</td>
            <td>Wemo</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::33" /></td>
        </tr>
        <tr>
            <td>34</td>
            <td>Zibase</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::34" /></td>
        </tr>
        <tr>
            <td>35</td>
            <td>SensorTag</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::35" /></td>
        </tr>
        <tr>
            <td>36</td>
            <td>Blinkstick</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::36" /></td>
        </tr>
        <tr>
            <td>37</td>
            <td>Blink1</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::37" /></td>
        </tr>
        <tr>
            <td>38</td>
            <td>Tellstick</td>
            <td><input type="checkbox" class="configKey" data-l1key="protocol::38" /></td>
        </tr>
    </tbody>
</table>

<script>
    initTableSorter();
    if(manageProtocol_slaveId != ''){
        jeedom.jeeNetwork.loadConfig({
            configuration: $('#table_noderedProtocole').getValues('.configKey')[0],
            plugin: 'nodered',
            id: manageProtocol_slaveId,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                $('#table_noderedProtocole').setValues(data, '.configKey');
                modifyWithoutSave = false;
            }
        });


        $("#bt_saveRfxProtocole").on('click', function (event) {
            $.hideAlert();
            jeedom.jeeNetwork.saveConfig({
                configuration: $('#table_noderedProtocole').getValues('.configKey')[0],
                id: manageProtocol_slaveId,
                plugin: 'nodered',
                error: function (error) {
                    $('#div_rfxProtocoleAlert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    $('#div_rfxProtocoleAlert').showAlert({message: '{{Sauvegarde réussie. Le démon va être relancé}}', level: 'success'});
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/nodered/core/ajax/nodered.ajax.php", // url du fichier php
            data: {
                action: "restartSlaveDeamon",
                id: manageProtocol_slaveId,
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_rfxProtocoleAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_rfxProtocoleAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
        }
    });
}
});
});


}else{
   jeedom.config.load({
    configuration: $('#table_noderedProtocole').getValues('.configKey')[0],
    plugin: 'nodered',
    error: function (error) {
        $('#div_rfxProtocoleAlert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#table_noderedProtocole').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});


   $("#bt_saveRfxProtocole").on('click', function (event) {
    $.hideAlert();
    jeedom.config.save({
        configuration: $('#table_noderedProtocole').getValues('.configKey')[0],
        plugin: 'nodered',
        error: function (error) {
            $('#div_rfxProtocoleAlert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_rfxProtocoleAlert').showAlert({message: '{{Sauvegarde réussie. Le démon va être relancé}}', level: 'success'});
                $.ajax({// fonction permettant de faire de l'ajax
                    type: "POST", // methode de transmission des données au fichier php
                    url: "plugins/nodered/core/ajax/nodered.ajax.php", // url du fichier php
                    data: {
                        action: "restartDeamon",
                    },
                    dataType: 'json',
                    error: function (request, status, error) {
                        handleAjaxError(request, status, error,$('#div_noderedProtocoleAlert'));
                    },
                    success: function (data) { // si l'appel a bien fonctionné
                    if (data.state != 'ok') {
                        $('#div_rfxProtocoleAlert').showAlert({message: data.result, level: 'danger'});
                        return;
                    }
                }
            });
            }
        });
});
}


</script>

