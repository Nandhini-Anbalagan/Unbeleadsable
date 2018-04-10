<?php
switch ($_POST['case']) {
	case "edit-agent":
		$agent = $db->getAgentByID($_SESSION['user']['agent_id']);
		if($agent){
			$_POST['areas'] = $_SESSION['user']['agent_areas'];
			$_POST['agency'] = $_SESSION['user']['agent_agency'];
			$_POST['comments'] = $_SESSION['user']['agent_comments'];
			$_POST['camp'] = $_SESSION['user']['campaign_id'];
			$_POST['ref'] = $_SESSION['user']['agent_ref'];
			$_POST['id'] = $_SESSION['user']['agent_id'];

			if($_POST['old_password'] == "" OR ((md5($_POST['old_password']) == $_SESSION['user']['password']) AND ($_POST['new_password'] == $_POST['confirm_password']))){

				if($_POST['phone_notification'] == "on")
					$_POST['phone_notification'] = 1;
				else
					$_POST['phone_notification'] = 0;

				if($_POST['email_notification'] == "on")
					$_POST['email_notification'] = 1;
				else
					$_POST['email_notification'] = 0;

				$_POST['signature'] = str_replace("/cdn/attached/", "https://unbeleadsable.com/cdn/attached/", $_POST['signature']);

				$db->editAgent($_POST);
				$db->editAgentUser($_POST['email'], $_POST['name'], $_SESSION['user']['user_id']);

				if($_POST['old_password'] != ""){
					$db->updatePassword($_SESSION['user']['user_id'], $_POST['new_password']);
					$_SESSION['user']['password'] = md5($_POST['new_password']);
				}

				$_SESSION['user'] = $db->reloadAgentProfile($_SESSION['user']['agent_id']);
				$resultObj['refresh'] = true;
				$resultObj['after-success'] = "Account Settings saved";
			}else if(md5($_POST['old_password']) != $_SESSION['user']['password'])
				$resultObj['error'] = "Sorry old password missmatch";
			else if($_POST['new_password'] != $_POST['confirm_password'])
				$resultObj['error'] = "Sorry new password not confirmed";
		}else
			$resultObj['error'] = "Invalid Agent.";
	break;
	case "edit":
		if(isset($_POST["uploadedBg"]) && !empty($_POST["uploadedBg"])){
			Functions::moveFile("temp/" . $_POST["uploadedBg"], "uploads/landings/" . $_POST["uploadedBg"]);
			$_POST["defaultBackground"] = $_POST["uploadedBg"];
		}

		if(!$db->editAgentLandingPage($_POST))
			$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
		else
			$resultObj['success'] = "Landing page edited successfully.";

		if($resultObj['error'] == "-1"){
			Tokenizer::delete(array('post-action-lead','post-case-lead-edit'));
		}
	break;
	case "picture":
		$ext = pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);
		$fileName = "cust_" . time() . ".$ext";
		move_uploaded_file($_FILES['file']['tmp_name'], "temp/$fileName");
		$resultObj['no-message'] = true;
		exit($fileName);
	break;
	case "addUser":
		if(User::isValidFields($_POST,
			array('name', 'username', 'email', 'password'),
			array('fullname', 'username', 'email', 'password'),
			$resultObj['error'])){
			$_POST['level'] = 10;
		if($db->getUserByUsername($_POST['username']))
			$resultObj['error'] = "The username <b>" . $_POST['username'] . "</b> is already in use.";
		else if($db->getUserByEmail($_POST['email']))
			$resultObj['error'] = "The email address <b>" . $_POST['email'] . "</b> is already in use.";
		else if(!$db->addTeamUser($_POST))
			$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
		else{
			$resultObj['success'] = $_POST['name'] . " added successfully";
			$resultObj['refresh'] = true;
			if(isset($_POST['emailUser']))
				emailCredentials($_POST['username'], $_POST['password'], $_POST['email']);

			Tokenizer::delete(array('post-action-settings','post-case-addUser'));
		}
	}
	break;
	case "editUser":
		if(User::isValidFields($_POST, array('name', 'email'), array('fullname', 'email'), $resultObj['error'])){
			$user = $db->getUser($_POST['user_id']);
			$pass = "Same as before";
			if($_POST['password'] == '')
				$_POST['password'] = $user['password'];
			else{
				$pass = $_POST['password'];
				$_POST['password'] = md5($_POST['password']);
			}

			if($db->editTeamUser($_POST)){
				$resultObj['after-success'] = $_POST['name'] . " edited successfully";
				$resultObj['refresh'] = true;

				if(isset($_POST['emailUser']))
					emailCredentials($_POST['username'], $pass, $_POST['email']);

				Tokenizer::delete(array('post-action-settings','post-case-editUser'));
			}else
				$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
		}
	break;
	case "editUserSelf":
		if(User::isValidFields($_POST, array('name', 'email'), array('fullname', 'email'), $resultObj['error'])){
			$user = $db->getUser($_SESSION['teammate']['id']);
			$_POST['user_id'] = $_SESSION['teammate']['id'];
			if($_POST['old_password'] == "" OR ((md5($_POST['old_password']) == $user['password']) AND ($_POST['new_password'] == $_POST['confirm_password']))){

				if($_POST['old_password'] == "")
					$_POST['password'] = $user['password'];
				else
					$_POST['password'] = md5($_POST['new_password']);

				if($db->editTeamUser($_POST)){
					$resultObj['after-success'] = $_POST['name'] . " edited successfully";
					$resultObj['refresh'] = true;

					$_SESSION['user']['agent_lang'] = $_POST['lang'];

					Tokenizer::delete(array('post-action-settings','post-case-editUserSelf'));
				}else
					$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
			}
		}
	break;
	case "delete-user":
		$resultObj['callback'] = "swal";
		$resultObj['no-message'] = true;
		if(isset($_POST['id'])){
			$user = $db->getUser($_POST['id']);
			if($user){
				$resultObj['callback-data'] = array("title" => "Deleted!",
					"message" => $user['name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-team-users");
				Tokenizer::delete(array('post-action-user', 'post-case-user-delete'));
				$resultObj['delete'] = $db->deleteUser($_POST['id']);
			}else
				$resultObj['error'] = "Invalid user.";
		}else
			$resultObj['error'] = "Unknown user.";

		if($resultObj['error'] != "-1"){
			$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
				"type" => "error",
				"next-action" => "reload-team-users");
		}
	break;
}

/**
	Function to send user credentials
*/
	function emailCredentials($username, $password, $email){
		$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
			<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
				<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
					<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
					<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
								New user registration on ' . Config::WEBSITE_TITLE . '
							</td>
						</tr>
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
								Username: ' . $username . '
								<br>Password: ' . $password . '
							</td>
						</tr>
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							<a href="' . Config::WEBSITE_URL . '/login" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5fbeaa; margin: 0; border-color: #5fbeaa; border-style: solid; border-width: 10px 20px;">Login</a>
						</td>
					</tr>
					<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							&mdash; ' . Config::WEBSITE_TITLE . '
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>';
	Functions::sendEmail("donotreply@unbeleadsable.com", $email, "New Team Registration", $msg);
	}
?>