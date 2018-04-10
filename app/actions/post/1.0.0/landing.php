<?php
	switch ($_POST['case']) {
		case "view-seller":
			if(isset($_POST['id'])){
				$page = $db->getSellerLandingPage($_POST['id']);
				if($page){
					$resultObj['callback-data'] = $page;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-seller-landing-page';
				}else
					$resultObj['error'] = "Invalid Agent.";
			}else
				$resultObj['error'] = "Unknown Agent.";
		break;
		case "view-buyer":
			if(isset($_POST['id'])){
				$page = $db->getBuyerLandingPage($_POST['id']);
				$page['agent_fk'] = IDObfuscator::encode($page['agent_fk']);
				if($page){
					$resultObj['callback-data'] = $page;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-buyer-landing-page';
				}else
					$resultObj['error'] = "Invalid Agent.";
			}else
				$resultObj['error'] = "Unknown Agent.";
		break;
		case "edit-seller":
			/*if(Functions::isValidFields($_POST, 
				array('name', 'email', 'phone', 'areas', 'agency'),
				array(5, 'email', 9, 'empty', 2),
				$resultObj['error'])){*/
				
				if(isset($_POST["uploadedBg"]) && !empty($_POST["uploadedBg"])){
					Functions::moveFile("temp/" . $_POST["uploadedBg"], "uploads/landings/" . $_POST["uploadedBg"]);
					$_POST["defaultBackground"] = $_POST["uploadedBg"];
				}

				if(!$db->editSellerLandingPage($_POST))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = "Landing page edited successfully.";
			//}
			
			if($resultObj['error'] == "-1"){
				//$resultObj['callback'] = "reload-landing";
				Tokenizer::delete(array('post-action-lead','post-action-lead-edit'));
			}
		break;
		case "edit-buyer":
			/*if(Functions::isValidFields($_POST, 
				array('name', 'email', 'phone', 'areas', 'agency'),
				array(5, 'email', 9, 'empty', 2),
				$resultObj['error'])){*/
				
				if(isset($_POST["uploadedBg"]) && !empty($_POST["uploadedBg"])){
					Functions::moveFile("temp/" . $_POST["uploadedBg"], "uploads/landings/" . $_POST["uploadedBg"]);
					$_POST["defaultBackground"] = $_POST["uploadedBg"];
				}

				if(!$db->editBuyerLandingPage($_POST))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = "Landing page edited successfully.";
			//}
			
			if($resultObj['error'] == "-1"){
				//$resultObj['callback'] = "reload-landing";
				Tokenizer::delete(array('post-action-lead','post-action-lead-edit'));
			}
		break;
		case "picture":
			$ext = pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);
			$fileName = "cust_" . time() . ".$ext";
			move_uploaded_file($_FILES['file']['tmp_name'], "temp/$fileName");
			$resultObj['no-message'] = true;
			exit($fileName);
		break;
	}
?>