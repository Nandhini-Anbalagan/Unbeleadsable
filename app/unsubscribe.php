<?php require_once('header.php'); ?>

<div class="animationload">
	<div class="loader"></div>
</div>
<div class="wrapper-page">
	<div class=" card-box">
		<div class="panel-heading"> 
			<h3 class="text-center"><img src="../assets/img/logo.png" class="img-responsive center-block"></h3>
		</div>
		
		<div class="panel-body">
			<form class="form-horizontal" data-parsley-validate="" novalidate>
				<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-subscription', 20, 'subscription'); ?>">
                <input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-subscription-unsubscribe', 20, 'unsubscribe'); ?>">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="text" required="" placeholder="someone@example.com" value="<?php echo $_GET['email'] ?>" name="email">
					</div>
				</div>
				
				<div class="form-group text-center">
					<div class="col-xs-12">
						<button class="btn btn-primary btn-block text-uppercase waves-effect waves-light" type="submit">Unsubscribe</button>
					</div>
				</div>
			</form> 
		
		</div>   
		
	</div>
</div>

<?php require_once('foot.php'); ?>