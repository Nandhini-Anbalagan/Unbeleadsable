<?php
	switch ($_POST['case']) {
		case "single":
			if(isset($_POST['id'])){
				$area = $db->getAreaById($_POST['id']);
				if($area){
					$resultObj['callback-data'] = $area;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-area';
				}else
					$resultObj['error'] = "Invalid Area.";
			}else
				$resultObj['error'] = "Unknown Area.";
		break;

		case "add":
			if(isset($_POST['country']) && ($_POST['country'] == "Canada" || $_POST['country'] == "USA")) {
				if (isset($_POST['agents'])) {
					if (Functions::isValidField($_POST, 'areaName', 5, $resultObj['error'])) {

						$_POST['country'] = $_POST['country'] == "USA" ? "US" : "CA";
						if ($db->getAreaByName($_POST['areaName']))
							$resultObj['error'] = "Sorry  <b>" . $_POST['areaName'] . "</b> exist in Database already.";
						else if (!$db->addArea($_POST))
							$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
						else {
							$resultObj['success'] = $_POST['areaName'] . " added successfully.";
							$resultObj['callback'] = "reload-areas";

							Tokenizer::delete(array('post-action-area', 'post-action-area-add'));
						}
					}
				} else
					$resultObj['error'] = "Please assign agents to the area.";
			}else
				$resultObj['error'] = "Please choose a valid area.";
		break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$area = $db->getAreaById($_POST['id']);
				if($area){
					$resultObj['callback-data'] = array("title" => "Deleted!",
					"message" => $area['area_name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-areas");
					Tokenizer::delete(array('post-action-area', 'post-case-area-delete'));
					$resultObj['delete'] = $db->deleteArea($_POST['id']);
				}else
					$resultObj['error'] = "Invalid Area.";
			}else
				$resultObj['error'] = "Unknown Area.";
				
			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...", 
					"type" => "error",
					"next-action" => "reload-areas");
			}
		break;

		case "edit":
			if(isset($_POST['deleteLead'])){
				if(!$db->getAreaById($_POST['id']))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = $_POST['name'] . " deleted successfully.";
			}else /*if(Functions::isValidFields($_POST, 
				array('name', 'email', 'phone', 'areas', 'agency'),
				array(5, 'email', 9, 'empty', 2),
				$resultObj['error']))*/{
				
				$area = $db->getAreaById($_POST['id']);
				if($area){
					if(!$db->editArea($_POST))
						$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
					else
						$resultObj['success'] = $_POST['areaName'] . " edited successfully.";
				}else
					$resultObj['error'] = "Invalid Area.";
			}
			
			if($resultObj['error'] == "-1"){
				$resultObj['callback'] = "reload-areas";
				Tokenizer::delete(array('post-action-areas','post-action-areas-edit'));
			}
		break;
	}
?>