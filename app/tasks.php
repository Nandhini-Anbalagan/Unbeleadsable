<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="page-title pull-left m-b-20"><?php echo $tr['task'] ?></h4>
				<div class="btn-group pull-right" id="status">
					<button type="button" class="btn btn-danger" disabled data-status="1"><?php echo $tr['incomplete'] ?> </button>
					<button type="button" class="btn btn-danger" data-status="2"><?php echo $tr['completed'] ?></button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="task-wrapper">
								<?php require_once('load/tasks.php'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){
		$("#status button").click(function(){
			$("#status button").removeAttr("disabled");
			$(this).attr("disabled", "disabled");
			$("#task-wrapper").load('load/tasks.php?status='+$(this).data('status'));
		})
	})
</script>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>