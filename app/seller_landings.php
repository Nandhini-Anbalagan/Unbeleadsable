<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>

<?php
$agents = $db->getSellerAgents();

	# Tokenizer container
$postActionLanding = Tokenizer::add('post-action-landing', 20, 'landing');
$postCaseLandingView = Tokenizer::add('post-case-landing-view-seller', 30, 'view-seller');
$postCaseLandingPic = Tokenizer::add('post-case-landing-picture', 30, 'picture');

?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div class="m-b-30">
								<h2 class="page-title pull-left">Customers</h2>
							</div>

							<div class="p-20">
								<table class="table table-striped table-responsive m-0" id="datatable_agents">
									<thead>
										<th>Name</th>
									</thead>
									<tbody>
										<?php
										foreach ($agents as $value) {
											?>
											<tr>
												<td name="name"><a href="javascript:void(0)" data-id="<?php echo $value['agent_id'] ?>"><?php echo $value['agent_name'] ?></a></td>
											</tr>
											<?php
										}
										if(COUNT($agents) == 0)
											echo "<tr><td class='text-center'><i>oupps, no user found. Are you sure you have any?</i></td></tr>"
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9 template" style="display: none">
				<div class="card-box">
					<div id="landings-wrapper">
						<?php require_once('load/seller_landings.php'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){

		$('td[name="name"] a').on('click', function(e) {
			$('.template').show();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLanding; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseLandingView; ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#datatable_agents').dataTable({
			"bStateSave": true,
			"dom":
				"<'row'<'col-sm-12'l><'col-sm-12'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12'i><'col-sm-12'p>>"
		});

		$(':file').change(function(){
			var file = this.files[0],
			type = file.type,
			types = ["image/png", "image/jpg", "image/jpeg"],
			formData = new FormData();

			formData.append("action", '<?php echo $postActionLanding; ?>');
			formData.append("case", '<?php echo $postCaseLandingPic; ?>');
			formData.append("file", file);

			if(types.indexOf(type) != -1){
				$.ajax({
					url: 'core.php',
					type: 'POST',
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						$('#bg4').prop("checked", true);
						$('input[name="uploadedBg"]').val(response);
					}
				});
			}else{
				$(this).val("");
				generateNotification("Sorry file type not valid!", "bottom-right", "error", 3000, true)
			}

		});
	});
</script>

 <script src="assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>
