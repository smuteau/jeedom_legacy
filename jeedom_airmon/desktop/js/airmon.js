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

$('#bt_healthairmon').on('click', function () {
  $('#md_modal').dialog({title: "{{Santé airmon}}"});
  $('#md_modal').load('index.php?v=d&plugin=airmon&modal=health').dialog('open');
});

$('#bt_webairmon').on('click', function () {
  var nodeId = $('#idairmon').value();
  $('#md_modal').dialog({title: "{{Interface airmon}}"});
  $('#md_modal').load('index.php?v=d&plugin=airmon&modal=web&ip=' + nodeId).dialog('open');
});

$('.changeIncludeState').on('click', function () {
  var el = $(this);
  jeedom.config.save({
    plugin : 'airmon',
    configuration: {include_mode: el.attr('data-state')},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      if (el.attr('data-state') == 1) {
        $.hideAlert();
        $('.changeIncludeState:not(.card)').removeClass('btn-default').addClass('btn-success');
        $('.changeIncludeState').attr('data-state', 0);
        $('.changeIncludeState.card').css('background-color','#8000FF');
        $('.changeIncludeState.card span center').text('{{Arrêter l\'inclusion}}');
        $('.changeIncludeState:not(.card)').html('<i class="fa fa-sign-in fa-rotate-90"></i> {{Arreter inclusion}}');
        $('#div_inclusionAlert').showAlert({message: '{{Vous etes en mode inclusion. Recliquez sur le bouton d\'inclusion pour sortir de ce mode}}', level: 'warning'});
      } else {
        $.hideAlert();
        $('.changeIncludeState:not(.card)').addClass('btn-default').removeClass('btn-success btn-danger');
        $('.changeIncludeState').attr('data-state', 1);
        $('.changeIncludeState:not(.card)').html('<i class="fa fa-sign-in fa-rotate-90"></i> {{Mode inclusion}}');
        $('.changeIncludeState.card span center').text('{{Mode inclusion}}');
        $('.changeIncludeState.card').css('background-color','#ffffff');
        $('#div_inclusionAlert').hideAlert();
      }
    }
  });
});

$('body').on('airmon::includeDevice', function (_event,_options) {
  if (modifyWithoutSave) {
    $('#div_inclusionAlert').showAlert({message: '{{Un périphérique vient d\'être inclu/exclu. Veuillez réactualiser la page}}', level: 'warning'});
  } else {
    if (_options == '') {
      window.location.reload();
    } else {
      window.location.href = 'index.php?v=d&p=airmon&m=airmon&id=' + _options;
    }
  }
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }

  if (init(_cmd.type) == 'info') {
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom du capteur}}"></td>';
    tr += '<td class="expertModeVisible">';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="cmdAttr"  data-l1key="configuration" data-l2key="cmd"></span> (Task <span class="cmdAttr"  data-l1key="configuration" data-l2key="taskid"></span>)';
    tr += '</td><td>';
    tr += '<span class="cmdAttr"  data-l1key="configuration" data-l2key="value"></span>';
    tr += '</td><td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" style="width : 90px;" placeholder="{{Unite}}">';
    tr += '</td><td>';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
      tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
      tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
}

  /*jeedom.eqLogic.builSelectCmd({
    id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });*/
}
