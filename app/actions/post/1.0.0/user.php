<?php
	switch ($_POST['case']) {
		case "single":
			if(isset($_POST['id'])){
				$user = $db->getUser($_POST['id']);
				if($user){
					$resultObj['callback-data'] = $user;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-user';
				}else
					$resultObj['error'] = "Invalid user.";
			}else
				$resultObj['error'] = "Unknown user.";
		break;

		case "add":
			if(User::isValidFields($_POST,
				array('name', 'username', 'email', 'password'),
				array('fullname', 'username', 'email', 'password'),
				$resultObj['error'])){
				if($db->getUserByUsername($_POST['username']))
					$resultObj['error'] = "The username <b>" . $_POST['username'] . "</b> is already in use.";
				else if($db->getUserByEmail($_POST['email']))
					$resultObj['error'] = "The email address <b>" . $_POST['email'] . "</b> is already in use.";
				else if(!$db->addUser($_POST))
					$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
				else{
					$resultObj['success'] = $_POST['name'] . " added successfully";
					$resultObj['callback'] = "reload-users";
					if(isset($_POST['emailUser'])){
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
												Username: ' . $_POST['username'] . '
												<br>Password: ' . $_POST['password'] . '
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
						Functions::sendEmail("do-not-reply@unbeleadsable.com", $_POST['email'], "New Registration", $msg);
					}
					Tokenizer::delete(array('post-action-user','post-action-user-add'));
				}
			}
		break;

		case "create-password":
			if(User::isValidField($_POST, 'password', 'new-password', $resultObj['error'])){
				if(!isset($_POST['cpassword']))
					$resultObj['error'] = "Please confirm your password.";
				else if($_POST['password'] != $_POST['cpassword'])
					$resultObj['error'] = "Please make sure both passwords are the same.";
				else{
					$db->updatePassword($_SESSION['user']['user_id'], $_POST['password']);
					$_SESSION['user'] = $db->reloadProfile($_SESSION['user']['user_id']);

					if($_SESSION['user']['level'] == 10)
						$resultObj['destination'] = "completed-leads";
					else
						$resultObj['destination'] = "leads";

					$resultObj['after-success'] = "Your password has been updated.";
					$resultObj['delay'] = 0;
					Tokenizer::delete(array('post-action-user', 'post-case-create-password'));
				}
			}
			break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$user = $db->getUser($_POST['id']);
				if($user){
					if($user && $_SESSION['user']['level'] > $user['level']){
						$resultObj['callback-data'] = array("title" => "Deleted!",
						"message" => $user['username'] . " has been deleted.",
						"type" => "success",
						"next-action" => "reload-users");
						Tokenizer::delete(array('post-action-user', 'post-case-user-delete'));
						$resultObj['delete'] = $db->deleteUser($_POST['id']);
					}else
						$resultObj['error'] = "You do not have the permission to perform this action.";
				}else
					$resultObj['error'] = "Invalid user.";
			}else
				$resultObj['error'] = "Unknown user.";

			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...", 
					"type" => "error",
					"next-action" => "reload-users");
			}
		break;

		case "edit":
			if(isset($_POST['deleteUser'])){
				if(!$db->deleteUser($_POST['user_id']))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = $_POST['name'] . " deleted successfully.";
			}else if(User::isValidFields($_POST, 
				array('name', 'username', 'email'),
				array('fullname', 'username', 'email'),
				$resultObj['error'])){

				$user = $db->getUser($_POST['user_id']);
				if($user){
					if($_POST['username'] != $user['username'] && $db->getUserByUsername($_POST['username']))
						$resultObj['error'] = "The username <b>" . $_POST['username'] . "</b> is already in use.";
					else if($_POST['email'] != $user['email'] && $db->getUserByEmail($_POST['email']))
						$resultObj['error'] = "The email address <b>" . $_POST['email'] . "</b> is already in use.";
					else if(!$db->editUser($_POST))
						$resultObj['error'] = "Oupps our system had a little hiccup, please try again!";
					else
						$resultObj['success'] = $_POST['name'] . " edited successfully.";
				}else
					$resultObj['error'] = "Invalid user.";
			}

			if($resultObj['error'] == "-1"){
				$resultObj['callback'] = "reload-users";
				Tokenizer::delete(array('post-action-user','post-action-user-edit'));
			}
		break;

		case "forgot-password":
			if(User::isValidField($_POST, 'email', 'email', $resultObj['error'])){
				$user = $db->getUserByEmail($_POST['email']);
				if($user){
					$key = $db->getUserKeyByUserId($user['user_id'], "RESET_PASSWORD");
					if(!$key){
						do{
							$key = Tokenizer::generateString(rand(30, 50));
						}while($db->getUserKey($key, "RESET_PASSWORD"));
						$db->generateUserKey($user['user_id'], $key, 'RESET_PASSWORD');
					}else{
						$key = $key['value'];
					}

					$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											Hello ' . $user['name'] . ',
										</td>
									</tr>
									<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											Here is your password reset link:
											<br>
											<br> <a href="' . Config::WEBSITE_URL . '/reset-password/' . $key . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5fbeaa; margin: 0; border-color: #5fbeaa; border-style: solid; border-width: 10px 20px;">Reset Password</a>
											<br>
											<br> If the button does not work, please copy and paste the following link:
											<br><span style="font-size:12px;">' . Config::WEBSITE_URL . '/reset-password/' . $key . '</span>
										</td>
									</tr>
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											' . (Config::PASSWORD_RESET_KEY_DURATION != 0 ? "The password reset key will be valid for the next " . Config::PASSWORD_RESET_KEY_DURATION . " hours." : "") . '
											<br>If you did not request the password reset, please ignore this message.
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
					Functions::sendEmail("donotreply@bbtutorials.com", $_POST['email'], "Reset Password", $msg);
					$resultObj['delay'] = 0;
					$resultObj['destination'] = "login";
					$resultObj['after-success'] = "An email with the instructions has been sent. Please look in your spam folder.";
					Tokenizer::delete('post-action-forgot-password', 'post-case-forgot-password');
				}else
					$resultObj['error'] = "Sorry, we do not have any account associated with <b>{$_POST['email']}</b>.";
			}
			break;
	}
?>