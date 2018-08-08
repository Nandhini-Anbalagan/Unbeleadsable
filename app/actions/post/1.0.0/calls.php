<?php
switch ($_POST['case']) {
	case "single":
		if(isset($_POST['id'])){
			$callRecord = $db->getSingleCall($_POST['id']);
			if($callRecord){
				$resultObj['callback-data'] = $callRecord;
				$resultObj['no-message'] = true;
				$resultObj['callback'] = 'get-single-edit-calls';
			}else
				$resultObj['error'] = "Invalid call record.";
		}else
			$resultObj['error'] = "Unknown call record.";
		break;

	case "add":
		if(Functions::isValidFields($_POST,
			array('name', 'phone', 'desired_area'),
			array(2, 7, 2),
			$resultObj['error'])){
			//$_POST['notes'] = nl2br($_POST['notes']);
			$_POST['country'] = $_SESSION['user']['user_country'];
			if(!$db->addCallRecord($_POST))
				$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
			else{
				$resultObj['success'] = $_POST['name'] . " added successfully";
				$resultObj['callback'] = "reload-calls";
				Tokenizer::delete(array('post-action-calls','post-action-calls-add'));
			}
		}
		break;

	case "delete":
		$resultObj['callback'] = "swal";
		$resultObj['no-message'] = true;
		if(isset($_POST['id'])){
			$callRecord = $db->getSingleCall($_POST['id']);
			if($callRecord){
				$resultObj['callback-data'] = array("title" => "Deleted!",
					"message" => $callRecord['call_name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-calls");
				Tokenizer::delete(array('post-action-calls', 'post-case-calls-delete'));
				$resultObj['delete'] = $db->deleteCallRecord($_POST['id']);
			}else
				$resultObj['error'] = "Invalid call record.";
		}else
			$resultObj['error'] = "Unknown call record.";
		break;

	case "edit":
		if(Functions::isValidFields($_POST,
			array('name', 'phone', 'desired_area'),
			array(2, 7, 2),
			$resultObj['error'])){
			//$_POST['notes'] = nl2br($_POST['notes']);
			$callRecord = $db->getSingleCall($_POST['id']);
			if($callRecord){
				if(!$db->updateCallRecord($_POST))
					$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
				else
					$resultObj['success'] = $_POST['name'] . " edited successfully.";
			}else
				$resultObj['error'] = "Invalid call record.";
		}

		if($resultObj['error'] == "-1"){
			$resultObj['callback'] = "reload-calls";
			Tokenizer::delete(array('post-action-calls','post-action-calls-edit'));
		}
		break;

	case 'single-convert':
		if(isset($_POST['id'])){
			$callRecord = $db->getSingleCall($_POST['id']);
			if($callRecord){
				$resultObj['callback-data'] = $callRecord;
				$resultObj['no-message'] = true;
				$resultObj['callback'] = 'get-single-convert-calls';
			}else
				$resultObj['error'] = "Invalid call record.";
		}else
			$resultObj['error'] = "Unknown call record.";
		break;

	case 'convert':
		if(Functions::isValidFields($_POST,
			array('name', 'email', 'state', 'phone', 'areas', 'agency'),
			array(2, "email", 2, 6, 2, 2),
			$resultObj['error'])) {
			$_POST['buyer_option'] = $_POST['buyer'] == 'buyer' || $_POST['buyer'] == 'both' ? 1 : 0;
			$_POST['seller_option'] = $_POST['buyer'] == 'seller' || $_POST['buyer'] == 'both' ? 1 : 0;

			$_POST['country'] = explode(",", $_POST['state'])[1];
			$_POST['state'] = explode(",", $_POST['state'])[0];

			if ($db->addToAgentLeads($_POST)) {
				$resultObj['success'] = $_POST['name'] . " added successfully";
				$resultObj['callback'] = "reload-leads-calls";

				Tokenizer::delete(array('post-action-calls','post-action-calls-convert'));
			}
		}
		break;
}
?>