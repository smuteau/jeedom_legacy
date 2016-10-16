<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'xivo');
$eqLogics = eqLogic::byType('xivo');

?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes xivo}}
        </legend>
            <div class="eqLogicThumbnailContainer">
                      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-plus-circle" style="font-size : 7em;color:#00979c;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>Ajouter</center></span>
    </div>
                <?php
                $dir = dirname(__FILE__) . '/../../doc/images/';
                $files = scandir($dir);
                foreach ($eqLogics as $eqLogic) {
                  $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                  echo "<center>";
                    $test = 'node_' . $eqLogic->getConfiguration('icone') . '.png';
                    log::add('playbulb', 'debug', 'test ' . $test);
                    if (in_array($test, $files)) {
                        $path = 'node_' . $eqLogic->getConfiguration('icone');
                    } else {
                        $path = 'xivo_icon';
                    }
                    echo '<img src="plugins/xivo/doc/images/' . $path . '.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
    </div>

<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">

    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
    <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>

    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
<div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <form class="form-horizontal">
            <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
                <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
                </legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Nom du Xivo}}</label>
                    <div class="col-sm-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement xivo}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                    <div class="col-sm-3">
                        <select class="form-control eqLogicAttr" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Catégorie}}</label>
                    <div class="col-sm-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">';
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>';
                        }
                        ?>

                    </div>
                </div>
                <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-8">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
</div>
                </div>


                            <div class="form-group">
                    <label class="col-sm-3 control-label">{{Commentaire}}</label>
                    <div class="col-sm-8">
                        <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
                    </div>
                </div>



                        <div class="form-group">
                    		<label class="col-sm-3 control-label">{{Adresse IP du XiVo}}</label>
                    		<div class="col-sm-3">
                    		 <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="addr" placeholder="adresse IP de XiVo"/>
                    		</div>
                	</div>

                  <div class="form-group">
                  <label class="col-sm-3 control-label">{{URL pour l'envoi depuis XiVO : }}</label>
                    <div class="col-sm-3">
                  <?php
                  $url  = config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort') . config::byKey('externalComplement') . '/core/api/jeeApi.php?api=' . config::byKey('api') . '&type=xivo&variable1=valeur1&variable2=valeur2';
                  echo $url;
                  ?>

                  </div>
            </div>

                    </fieldset>
                </form>
            </div>
         <div role="tabpanel" class="tab-pane" id="commandtab">


  <form class="form-horizontal">
    <fieldset>
        <div class="form-actions">
        <a class="btn btn-success btn-sm cmdAction" id="bt_addxivoAction"><i class="fa fa-plus-circle"></i> {{Ajouter une commande action}}</a>
        <a class="btn btn-success btn-sm cmdAction" id="bt_addxivoInfo"><i class="fa fa-plus-circle"></i> {{Ajouter une commande info}}</a>
      </div>
  </fieldset>
  </form>
    <br />

        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 150px;">{{Nom}}</th>
                    <th style="width: 110px;">{{URL}}</th>
                    <th style="width: 100px;">{{Valeur}}</th>
                    <th style="width: 200px;">{{Paramètres}}</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
</div>
</div>
</div>
</div>

<?php include_file('desktop', 'xivo', 'js', 'xivo'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
