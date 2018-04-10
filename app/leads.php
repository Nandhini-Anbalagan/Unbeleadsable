<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<?php
	if($_SESSION['user']['level'] == 10)
		Functions::redirect("login");
 ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="leads-wrapper">
								<?php require_once('load/leads.php'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('load/compose-email.php'); ?>

<script>
	$('body').on('click', '.sendEmail', function(e){
		e.preventDefault();
		var name = $(this).parents('tr').find('td[name="name"]').html(),
		email = $(this).parents('tr').find('td[name="email"]').html(),
		areas = $(this).parents('tr').find('td[name="areas"]').html();
		if(email != ""){
			$('#compose-modal').modal('show');
			$('[name="to"]').val(email);
		}else
			generateNotification('Please select at least one Agent Lead.', 'bottom-right', 'error', 5000, true);
	});
</script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>