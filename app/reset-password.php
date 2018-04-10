<?php 
	require_once('header.php');
	if(User::isLoggedIn()){
		Functions::generateErrorMessage(Config::INVALID_PERMISSION_MESSAGE);
		Functions::redirect("main");
	}else if(!isset($_GET['key'])){
		Functions::generateErrorMessage("What happened to your reset password key?");
		Functions::redirect("login");
    }
    
    $key = $db->getUserKey($_GET['key'], "RESET_PASSWORD");
    
    if(!$key){
        Functions::generateErrorMessage("Your reset password key seems to be invalid. Please request a new one.");
		Functions::redirect("login");
    }
    
    $db->deleteUserKey($key['user_key_id']);
    if(Config::PASSWORD_RESET_KEY_DURATION != 0 && time() - strtotime($key['creation_time']) >= Config::PASSWORD_RESET_KEY_DURATION * 60 * 60){
        Functions::generateErrorMessage("Your reset password key has expired. Please request a new one.");
		Functions::redirect("login");
    }else{
        $db->resetPassword($key['user_fk']);
        $_SESSION['user'] = $db->reloadProfile($key['user_fk']);
        Functions::redirect("create-password");
    }
?>

<?php require_once('foot.php'); ?>