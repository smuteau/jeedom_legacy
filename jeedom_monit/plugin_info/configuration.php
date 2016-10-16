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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
}
?>


<form class="form-horizontal">
  <div class="form-group">
    <fieldset>


      <div class="form-group">

        <label class="col-lg-4 control-label" >{{Nginx}} :</label>
        <div class="col-lg-2">
          <input type="checkbox" class="configKey form-control bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="nginx" checked=""/>
        </div>
      </div>

      <div class="form-group">

        <label class="col-lg-4 control-label" >{{PHP}} :</label>
        <div class="col-lg-2">
          <input type="checkbox" class="configKey form-control bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="php" checked=""/>
        </div>
      </div>

      <div class="form-group">

        <label class="col-lg-4 control-label" >{{MySQL}} :</label>
        <div class="col-lg-2">
          <input type="checkbox" class="configKey form-control bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="mysql" checked=""/>
        </div>
      </div>

      <div class="form-group">

        <label class="col-lg-4 control-label" >{{Apache}} :</label>
        <div class="col-lg-2">
          <input type="checkbox" class="configKey form-control bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="apache" checked=""/>
        </div>
      </div>


    </fieldset>
  </form>



  <script>


function monit_postSaveConfiguration(){

$.ajax({// fonction permettant de faire de l'ajax
    type: "POST", // methode de transmission des données au fichier php
    url: "plugins/monit/core/ajax/monit.ajax.php", // url du fichier php
    data: {
        action: "config",
    },
    dataType: 'json',
    error: function (request, status, error) {
        handleAjaxError(request, status, error);
    },
    success: function (data) { // si l'appel a bien fonctionné
if (data.state != 'ok') {
  $('#div_alert').showAlert({message: data.result, level: 'danger'});
  return;
}
}
});
}


</script>
</div>
</fieldset>
</form>
