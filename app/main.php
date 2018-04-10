<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php Functions::redirect('dashboard'); ?>

<div class="wrapper">
	<div class="container">

		<!-- Page-Title -->
		<div class="row">
			<div class="col-sm-12">
				<div class="btn-group pull-right m-t-15">
					<a href="#" class="btn btn-default waves-effect waves-light troll">Settings <span class="m-l-5"><i class="fa fa-cog"></i></a>
				</div>
				<h4 class="page-title">Dashboard</h4>
				<p class="text-muted page-title-alt">Welcome back <?php echo $_SESSION['user']['name']; ?>!</p>
			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>