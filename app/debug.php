<?php require_once('header.php'); ?>

<div class="wrapper">
	<div class="container">
		<section>
			<div class="row">
				<div class="col-md-12">
					<p>This is just a test tool to test the core action with specific version.</p>
					<form onsubmit="return validate(this);">
						<div class="form-group">
							<label for="action">Action</label>
							<input type="text" name="action" id="action" value="<?php echo Tokenizer::add('post-action-login', 20, 'login'); ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="version">Version</label>
							<input type="text" name="version" id="version" value="<?php echo Tokenizer::add('post-action-login-version', 20, '1.0.0'); ?>" class="form-control">
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-success">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
</div>

<?php require_once('foot.php'); ?>