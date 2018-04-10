<?php
	switch($_POST['case']){
		case "unsubscribe":
			if(User::isValidField($_POST, 'email', 'email', $resultObj['error'])){
				if(!$db->getBlacklistEmail($_POST['email'])){
					if($db->addBlacklist($_POST['email'])){
						$resultObj['success'] = "You have been removed from the subscription list.";
						$resultObj['reset'] = true;
					}else
						$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				}else
					$resultObj['error'] = "The email <b>" . $_POST['email'] . "</b> has already been removed from the subscription list.";
			}
			break;
	}
?>