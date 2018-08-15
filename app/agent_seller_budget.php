<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<?php

	$agents = $db->agents_budget();
	$totalAgents = count($agents);

	# Tokenizer container
	$postActionAgent = Tokenizer::add('post-action-agent', 20, 'agent');
	$postCaseAgentDelete = Tokenizer::add('post-case-agent-delete', 30, 'delete');
	$postCaseAgentSingleView = Tokenizer::add('post-case-agent-singleView', 30, 'singleView');
	$postCaseAgentSingleEdit = Tokenizer::add('post-case-agent-singleEdit', 30, 'singleEdit');

?>

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="agents-wrapper">
								<div class="m-b-30">
									<h2 class="page-title pull-left">Customer's Ad Budgets <span class="text-muted font-13">(Total of <span class="users-count"><?php echo $totalAgents; ?> agent<?php echo $totalAgents > 1 ? 's' : '' ?></span>)</span></h2>
									<a id="daterange" class="pull-right btn btn-success" style="margin-right: 10px;"><span class="fa fa-filter"></span> Date Range</a>
								</div>
								<div class="clearfix"></div>
								<h2 class="text-center">Lead Statistic Date Range: <span id="range">Default</span></h2>

								<div class="p-20">
									<table class="table table-striped m-0" id="datatable-editable">
										<thead>
											<tr>
												<th class="text-center">Stats</th>
												<th>Name</th>
												<th width="20%">Areas</th>
												<th class="text-center">Period</th>
												<th class="text-center">Period Budget</th>
											</tr>
										</thead>
										<tbody>
											<?php
												foreach ($agents as $value) {

											?>
													<tr>
														<td class="text-center">
															<button class="btn btn-sm btn-primary stats" data-id="<?php echo $value['agent_id'] ?>" data-name="<?php echo $value['agent_name'] ?>" data-campaign="<?php echo $value['campaign_id'] ?>" data-start="<?php echo explode(" ", $value['invoice_date'])[0] ?>" data-end="<?php echo explode(" ", $value['next_billing'])[0] ?>" data-toggle="modal" data-target="#stats-modal"><span class="fa fa-line-chart"></span></button>
															<a href="?mockUser=<?php echo IDObfuscator::encode($value['agent_id']) ?>" title="View Agent's Account" class="btn btn-sm btn-danger"><i class="fa fa-globe"></i></a>

														<td>
														<?php echo $value['agent_name'] . "<br>" . $value['campaign_id']?>
														<div id="stat<?php echo $value['agent_id'] ?>"></div>
														</td>
														<td><?php echo $value['assigned_area'] ?></td>
														<td class="text-center"><?php echo Functions::userFriendlyDate($value['invoice_date']) . " - " . Functions::userFriendlyDate($value['next_billing']) ?></td>
														<td class="text-center">$<?php echo $value['ads'] ?></td>
													</tr>
												<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="stats-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-purple">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 target="title" class="panel-title text-center"></h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-user-information dataTable">
								<tbody>
									<tr>
										<th>Range</th>
										<td target="range"></td>
									</tr>
									<tr>
										<th>Completed Leads</th>
										<td target="completed"></td>
									</tr>
									<tr>
										<th>Partial Leads</th>
										<td target="partial"></td>
									</tr>
									<tr>
										<th>Address Capture</th>
										<td target="address"></td>
									</tr>
									<tr>
										<td colspan="2" class="text-center"><strong><span class="fa fa-facebook" style="color:#3b5998"></span> &nbsp;Campaign</strong></td>
									</tr>
									<tr>
										<th>Amount Spent</th>
										<td target="spent"></td>
									</tr>
									<tr>
										<th>Reached</th>
										<td target="reach"></td>
									</tr>
									<tr>
										<th>Clicked</th>
										<td target="clicks"></td>
									</tr>
									<tr>
										<th>CPC</th>
										<td target="cpc"></td>
									</tr>
									<tr>
										<th>CTR</th>
										<td target="ctr"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		var range = "Default";
		$("#datatable-editable").DataTable({
			language: {
				emptyTable: "Oopps, no agent found, are you sure you have any?"
			},
			order: [],
			columnDefs: [
				{ "orderable": false, "targets": [1,2,3] }
			],
			fixedHeader: true,
			autoWidth: false,
			responsive: true,
			"bStateSave": true
		});

		$('#stats-modal').on('show.bs.modal', function(e) {
			if($("#range").text() == "Default")
				range = $(e.relatedTarget).data('start') + " - " + $(e.relatedTarget).data('end');

			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agent-stats', 20, 'stats'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">'
				+ '<input type="hidden" name="name" value="' + $(e.relatedTarget).data('name') + '">'
				+ '<input type="hidden" name="campaign" value="' + $(e.relatedTarget).data('campaign') + '">'
				+ '<input type="hidden" name="type" value="home_sellers">'
				+ '<input type="hidden" name="range" value="' + range + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#daterange').daterangepicker({
			autoUpdateInput: false,
			opens: "left",
			drops: "down",
			maxDate: moment(),
			showDropdowns: true,
			ranges: {
				"<?php echo $tr['today'] ?>": [moment(), moment()],
				"<?php echo $tr['yesterday'] ?>": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				"<?php echo $tr['sevenDays'] ?>": [moment().subtract(6, 'days'), moment()],
				"<?php echo $tr['thirtyDays'] ?>": [moment().subtract(29, 'days'), moment()],
				"<?php echo $tr['thisMonth'] ?>": [moment().startOf('month'), moment().endOf('month')],
				"<?php echo $tr['lastMonth'] ?>": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				"Default": "Default"
			},
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-primary',
			cancelClass: 'btn-danger',
		});

		$('#daterange').on('apply.daterangepicker', function(ev, picker) {
			var sDate = picker.startDate.format('YYYY-MM-DD'), eDate = picker.endDate.format('YYYY-MM-DD');

			if(sDate != eDate)
				range = sDate + ' - ' + eDate;
			else
				range = sDate;

			if(range != "Invalid date")
				$("#range").text(picker.chosenLabel + " (" + range +")");
			else
				$("#range").text(picker.chosenLabel);
			return false;
		});

		$('#daterange').on('cancel.daterangepicker', function(ev, picker) {
			$("#range").text("All");
			range = "All"
		});
	});
</script>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>
