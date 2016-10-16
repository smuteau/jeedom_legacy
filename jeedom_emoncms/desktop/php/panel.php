<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('object_id') == '') {
	$_GET['object_id'] = $_SESSION['user']->getOptions('defaultDashboardObject');
}
$object = object::byId(init('object_id'));
if (!is_object($object)) {
	$object = object::rootObject();
}
if (!is_object($object)) {
	throw new Exception('{{Aucun objet racine trouvé}}');
}

$url = config::byKey('url','emoncms');
?>

<div style="height: 600px; width: 100%;">
<iframe src="<?php echo $url; ?>" height="100%" width="100%">You need a Frames Capable browser to view this content.</iframe>
</div>
