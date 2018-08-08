<?php 
	require_once('header.php');
	if(User::isLoggedIn()){
		Functions::generateErrorMessage(Config::INVALID_PERMISSION_MESSAGE);
		Functions::redirect("main");
	}
?>

<style>
	body{
		height: 100vh;
	}
</style>

<!-- <div class="account-pages"></div> -->
<div class="clearfix"></div>
<div class="wrapper-page">
	<div class=" card-box">
		<div class="panel-heading">
			<img src="../assets/img/logo.png" class="img-responsive center-block">
		</div>

		<div class="panel-body">
			<form method="post" role="form" class="text-center" onsubmit="return validate(this);">
				<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-forgot-password', 20, 'user'); ?>">
				<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-forgot-password', 20, 'forgot-password'); ?>">
				<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
						×
					</button>
					Enter your <b>Email</b> and instructions will be sent to you!
				</div>
				<div class="form-group m-b-0">
					<div class="input-group">
						<input type="email" class="form-control" placeholder="someone@example.com" name="email" required="">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-primary w-sm waves-effect waves-light">
								Reset
							</button> 
						</span>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<?php require_once('foot.php'); ?>