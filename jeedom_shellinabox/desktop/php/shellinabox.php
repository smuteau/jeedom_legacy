<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

?>


    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Shellinabox}}
        </legend>
                    <center><span class="eqLogicDisplayCard cursor" data-eqLogic_id="shellinabox" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
                    <center><img src="plugins/shellinabox/doc/images/shellinabox_icon.png" height="105" width="95" onclick="loadModal()"/></center>
                    </span>

            </div>
    </div>
    <script>
		function loadModal() {
			$('#md_modal2').dialog({
				title: "Shellinabox"
			});

			$('#md_modal2').load('index.php?v=d&plugin=shellinabox&modal=shellinabox').dialog('open');
 }
    </script>

<?php include_file('core', 'plugin.template', 'js'); ?>
