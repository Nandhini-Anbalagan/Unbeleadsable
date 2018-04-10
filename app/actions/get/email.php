<?php 
switch ($_GET['case']) {
	case 'preview':
		$temp = $db->getTemplate($_GET['id']);
	    if($temp)
	    	echo html_entity_decode($temp['content']);
	break;
}

?>