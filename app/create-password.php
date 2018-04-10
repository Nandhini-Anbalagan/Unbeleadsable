<?php 
	require_once('header.php');
	
	if($_SESSION['user']['changed_password']){
		Functions::generateErrorMessage(Config::INVALID_PERMISSION_MESSAGE);
		Functions::redirect("main");
	}
?>

<style>
	body{
		height: 100vh;
	}
</style>

<div class="animationload">
	<div class="loader"></div>
</div>
<div class="wrapper-page">
	<div class=" card-box">
		
		<div class="panel-heading"> 
			<img src="../assets/img/logo.png" class="img-responsive center-block">
		</div>
			
		<div class="panel-body">
			<form class="form-horizontal m-t-20" data-parsley-validate="" novalidate>
				<h4 class="text-success">Please update your password to continue.</h4>

				<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-user', 20, 'user'); ?>">
				<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-create-password', 20, 'create-password'); ?>">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="password" required="" placeholder="New Password" name="password" autocomplete="new-password" data-parsley-length="[<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>]">
					</div>
				</div>

				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" required="" placeholder="Confirm New Password" name="cpassword" autocomplete="new-password" data-parsley-length="[<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>]">
					</div>
				</div>
				
				<div class="form-group text-center m-t-40">
					<div class="col-xs-12">
						<button class="btn btn-success btn-block text-uppercase waves-effect waves-light" type="submit">Update</button>
					</div>
				</div>
			</form> 
		
		</div>   
		
	</div>
</div>
<?php require_once('foot.php'); ?>