<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="page-title pull-left m-b-20"><?php echo $tr['archieved_leads'] ?></h4>
				<div class="dropdown pull-right">
					<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="fa fa-plus"></span> Actions <span class="caret"></span></button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="javascript:void(0)" id="recover"><span class="fa fa-refresh"></span> <?php echo $tr['recover'] ?></a></li>
						<li><a href="javascript:void(0)" id="delete"><span class="fa fa-trash"></span> <?php echo $tr['delete_forver'] ?></a></li>
					</ul>
				</div>
				<div class="clearfix"></div>
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="completed-leads-wrapper">
								<?php
									require_once('load/archived-leads.php');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>