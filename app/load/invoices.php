<?php
if(file_exists('../head.php')){
	require_once('../head.php');
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}


if(isset($_GET['search']))
	$invoices = $db->searchInvoices($_GET);
else
	$invoices = $db->getInvoices();

$postActionAgent = Tokenizer::add('post-action-agent', 20, 'agent');
$postCaseAgentSingleView = Tokenizer::add('post-case-agent-singleView', 30, 'singleView');
?>

<table id="invoice-table" class="table table-striped table-responsive table-bordered">
	<thead>
		<tr>
			<th> Invoice Date </th>
			<th> Invoice Number </th>
			<th> Customer </th>
			<th> Details </th>
			<th> Amount </th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($invoices as $i) {
			$desc = "";
			$desc .= $i['install'] > 0?'Installation, ':'';
			$desc .= $i['monthly'] > 0?'Montly Payment, ':'';
			$desc .= $i['ads'] > 0?'Ads Budget':'';
		?>
		<tr>
			<td><?php echo date_format(date_create($i['invoice_date']), 'F jS Y') ?></td>
			<td><a href="receipt/<?php echo $i['invoice_num'] ?>" target="_blank"><?php echo $i['invoice_num'] ?></a></td>
			<td><a href="#" title="View Lead" class="viewAgent" data-toggle="modal" data-target="#view-modal" data-id="<?php echo $i['agent_fk'] ?>"><?php echo $i['name'] ?></a></td>
			<td><?php echo $desc ?></td>
			<td>$<?php echo number_format($i['install']+$i['monthly']+$i['ads'],2) ?></span></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-purple">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">View Customer</h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-user-information">
								<tbody>

									<tr>
										<th>Name</th>
										<td target="name"></td>
									</tr>
									<tr>
										<th>Email</th>
										<td target="email"></td>
									</tr>
									<tr>
										<th>Phone</th>
										<td target="phone"></td>
									</tr>
									<tr>
										<th>Areas</th>
										<td target="areas"></td>
									</tr>
									<tr>
										<th>Agency</th>
										<td target="agency"></td>
									</tr>
									 <tr>
										<th>Language</th>
										<td target="language"></td>
									</tr>
									 <tr>
										<th>Comments</th>
										<td target="comments"></td>
									</tr>
									<tr>
										<th width="140px">Date Created</th>
										<td target="date"></td>
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

<script type="text/javascript">
	$(document).ready(function(){
		var handleDataTableButtons=function(){"use strict";0!==$("#invoice-table").length&&$("#invoice-table").DataTable({order: [],"columnDefs": [{"targets": 'no-sort',"orderable": false}],dom:"Bfrtip",buttons:[{extend:"copy",className:"btn-sm"},{extend:"csv",className:"btn-sm"},{extend:"excel",className:"btn-sm"},{extend:"pdf",className:"btn-sm"},{extend:"print",className:"btn-sm"}],responsive:!0})},TableManageButtons=function(){"use strict";return{init:function(){handleDataTableButtons()}}}();
		TableManageButtons.init();

		$('#view-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentSingleView; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});
</script>
