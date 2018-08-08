<?php
	switch ($_POST['case']) {
		case "add":
			$db->addLeadStatus($_POST);
			$resultObj['callback'] = "reload-status";
			$resultObj['success'] = "Status added successfully";
			Tokenizer::delete(array('post-action-status','post-case-add'));
		break;

		case "edit":
			if(isset($_POST['id'])){
				$db->editLeadStatus($_POST);
			}else
				$resultObj['error'] = "Invalid Status.";
			
			if($resultObj['error'] == "-1"){
				$resultObj['callback'] = "reload-status";
				$resultObj['success'] = "Status edited successfully";
				Tokenizer::delete(array('post-action-status','post-case-edit'));
			}
		break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$resultObj['callback-data'] = array("title" => "Deleted!",
				"message" => $_POST['name'] . " has been deleted.",
				"type" => "success",
				"next-action" => "reload-status");
				Tokenizer::delete(array('post-action-status', 'post-case-delete'));
				$resultObj['delete'] = $db->deleteStatus($_POST['id']);
			}else
				$resultObj['error'] = "Unknown Lead.";
				
			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...", 
					"type" => "error",
					"next-action" => "reload-leads");
			}
		break;

	}
?>