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
    <fieldset>
    <?php

	 if (exec('sudo cat /etc/sudoers')=="") {
 		echo'<div class="alert alert-danger">
	        <label class="col-lg-4 control-label">{{Installation automatique impossible}}</label>
	        <div class="col-lg-3">
	            {{Pour installer les dépendances (voir aussi la doc pour le reverse proxy):}} sudo apt-get -y install shellinabox
	        </div>
	    </div>';
 	}


?>
<div class="col-lg-12">
    <a class="btn btn-primary" href="/jeedom/shellinabox/" target="_blank">Cliquer ici pour ouvrir une nouvelle fenêtre et valider le certificat de Shellinabox afin de finir l'installation</a>
</div>



    </fieldset>
</form>
