<?php
	if(!isset($_POST['login']) || Functions::isEmpty($_POST['login']))
		$resultObj['error'] = "Please enter your username or email to continue.";
	else if(!isset($_POST['password']) || Functions::isEmpty($_POST['password'], false))
		$resultObj['error'] = "Your password cannot be empty.";
	else{
		$user = $db->signIn($_POST['login'], $_POST['password']);

		if(!$user)
			$resultObj['error'] = "Your login credentials are invalid.";
		else if($user['status'] == User::BANNED)
			$resultObj['error'] = "Your account has been suspended.";
		else{
			if(isset($_POST['remember_me'])){
				$key = "";
				do{
					$key = Tokenizer::generateString(rand(15, 30));
				}while($db->getUserKey($key, "AUTO_LOGIN"));
				$db->generateUserKey($user['user_id'], $key, 'AUTO_LOGIN');
				setcookie("remember_me", $key, time() + 7 * 24 * 60 * 60, "/");
			}

			unset($_SESSION['user']);
			$_SESSION['user'] = $user;
			unset($_SESSION['teammate']);

			$db->updateLastLogin($user['username']);
			$resultObj['no-reset'] = true;
			$resultObj['remove-disable'] = false;
			$resultObj['after-success'] = "Welcome back " . $user['username'] . "...";

			if($user['main_user'] != null){
				$_SESSION['teammate'] = array();
				$_SESSION['teammate']['id'] = $user['user_id'];
				$_SESSION['user'] = $db->reloadProfile($_SESSION['user']['main_user']);
				$resultObj['destination'] = "completed-leads";
			}else{

				if($user['changed_password'] == 0)
					$resultObj['destination'] = "create-password";
				else if($user['level'] == 10){
					$_SESSION['user'] = $db->reloadProfile($user['user_id']);
					$resultObj['destination'] = "completed-leads";
				}else
					$resultObj['destination'] = "leads";

			}

			Tokenizer::delete(array('post-action-login', 'post-version-login'));
		}
	}
?>