<?php
	require_once('header.php');
	if(User::isLoggedIn()){
		Functions::generateErrorMessage("You're already logged in.");
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
			<a href="/"><img src="../assets/img/logo.png" class="img-responsive center-block"></a>
			<!-- <h3 class="text-center"> Sign In</h3> -->
		</div>

		<div class="panel-body">
			<form class="form-horizontal m-t-20" data-parsley-validate="" novalidate>
				<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-login', 20, 'login'); ?>">
				<input type="hidden" name="version" value="<?php echo Tokenizer::add('post-version-login', 10, '1.0.0'); ?>">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="text" required="" placeholder="Username" name="login" autofocus>
					</div>
				</div>

				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" required="" placeholder="Password" name="password" autocomplete="new-password">
					</div>
				</div>

				<div class="form-group ">
					<div class="col-xs-12">
						<input id="checkbox-signup" type="checkbox" class="styledCheckbox" name="remember_me">
						<label for="checkbox-signup" style="vertical-align: super;">Remember me</label>
					</div>
				</div>

				<div class="form-group text-center m-t-20">
					<div class="col-xs-12">
						<button class="btn btn-primary btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
					</div>
				</div>

				<div class="form-group m-t-30 m-b-0">
					<div class="col-sm-12">
						<a href="forgot-password" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php require_once('foot.php'); ?>