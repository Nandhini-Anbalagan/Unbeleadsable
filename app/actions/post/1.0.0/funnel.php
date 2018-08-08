<?php
	switch($_POST['case']){
		case "add":
			//$keys = array('name', 'slug', 'content');
			//$types = array('funnel-name', 'funnel-slug', 'funnel-content');
			//if(Functions::isValidFields($_POST, $keys, $types, $resultObj['error'])){
				$db->addFunnel($_POST);
				$resultObj['callback'] = "refresh-funnel";
				$resultObj['success'] = "The funnel message <b>{$_POST['name']}</b> has been added.";
			//}
			break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$funnel = $db->getFunnel($_POST['id']);
				if($funnel)
					$db->deleteFunnel($_POST['id']);
				else
					$resultObj['error'] = "Invalid funnel message.";
			}else
				$resultObj['error'] = "Unknown funnel message.";

			if($resultObj['error'] == "-1"){
				$resultObj['callback-data'] = array("message" => "funnel message " . $funnel['name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "refresh-funnel");
			}else{
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
					"type" => "error",
					"next-action" => "refresh-funnel");
			}
			break;

		case "pause":
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$funnel = $db->getFunnel($_POST['id']);

				if($funnel)
					if($_POST['switch'] === 'true')
						$db->unPauseFunnel($_POST['id']);
					else
						$db->pauseFunnel($_POST['id']);
				else
					$resultObj['error'] = "Invalid funnel message.";
			}else
				$resultObj['error'] = "Unknown funnel message.";

			break;

		case "disable":
			$db->disableFunnels();
			$resultObj['callback'] = "refresh-funnel";
			$resultObj['success'] = "Default Funnels Messages Disabled";
			break;

		case "enable":
			$db->enableFunnels();
			$resultObj['callback'] = "refresh-funnel";
			$resultObj['success'] = "Default Funnels Messages Enabled";
			break;

		case "edit":
			if(isset($_POST['id'])){
				$funnel = $db->getFunnel($_POST['id']);
				if($funnel){
					$keys = array('name', 'slug', 'content');
					$types = array(5, 5, 20);
					//$types = array('funnel-name', 'funnel-slug', 'funnel-content');
					//if(Functions::isValidFields($_POST, $keys, $types, $resultObj['error'])){
						$db->updatefunnel($_POST['id'], $_POST);
						$resultObj['callback'] = "refresh-funnel";
						$resultObj['success'] = "The funnel message <b>" . $funnel['name'] . "</b> has been updated.";
					//}
				}else
					$resultObj['error'] = "Invalid funnel message.";
			}else
				$resultObj['error'] = "Unknown funnel message.";
			break;

		case "single":
			if(isset($_POST['id'])){
				$funnel = $db->getFunnel($_POST['id']);
				if($funnel){
					$resultObj['callback-data'] = $funnel;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-funnel';
				}else
					$resultObj['error'] = "Invalid funnel message.";
			}else
				$resultObj['error'] = "Unknown funnel message.";
			break;
		case "single-view":
			if(isset($_POST['id'])){
				$funnel = $db->getFunnel($_POST['id']);
				if($funnel){
					$resultObj['callback-data'] = $funnel;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-view-funnel';
				}else
					$resultObj['error'] = "Invalid funnel message.";
			}else
				$resultObj['error'] = "Unknown funnel message.";
			break;
		case "cat-add":
			if($_POST['agent'] != "")
				$db->addFunnelCategory($_POST['name'], $_POST['type'], $_POST['agent']);
			else
				$db->addFunnelCategory($_POST['name'], $_POST['type']);
			$resultObj['callback'] = "refresh-funnel";
			$resultObj['success'] = "Funnels <b>{$_POST['name']}</b> Added Successfully";
		break;
		case "cat-edit":
			$db->editFunnelCategory($_POST['name'], $_POST['type'], $_POST['selectFunnel']);
			$resultObj['callback'] = "refresh-funnel";
			$resultObj['success'] = "Funnels <b>{$_POST['name']}</b> Edited Successfully";
		break;
		case "cat-delete":
			$db->deleteFunnelCategory($_POST['funnel']);
			$resultObj['callback'] = "refresh-funnel";
			$resultObj['success'] = "Funnels Deleted Successfully";
		break;
		case "selectCat":
			if(isset($_POST['id'])){
				$funnelCat = $db->getFunnelCat($_POST['id']);
				if($funnelCat){
					$resultObj['callback-data'] = $funnelCat;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-funnelCat';
				}else
					$resultObj['error'] = "Invalid funnel.";
			}else
				$resultObj['error'] = "Unknown funnel.";
		break;
	}
?>