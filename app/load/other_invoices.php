<?php
if(file_exists('../head.php')){
	require_once('../head.php');
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}


if(isset($_GET['search']))
	$invoices = $db->searchOtherInvoices($_GET);
else
	$invoices = $db->getOtherInvoices();
?>

<table id="invoice-table" class="table table-striped table-responsive table-bordered">
	<thead>
		<tr>
			<th> Invoice Date </th>
			<th> Invoice Number </th>
			<th> Customer Name </th>
			<th> Details </th>
			<th> Amount </th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($invoices as $i) { ?>
		<tr>
			<td><?php echo date_format(date_create($i['invoice_date']), 'F jS Y') ?></td>
			<td><a href="receipt/other/<?php echo $i['invoice_num'] ?>" target="_blank"><?php echo $i['invoice_num'] ?></a></td>
			<td><?php echo $i['name'] ?></td>
			<td><?php echo $i['description'] ?></td>
			<td>$<?php echo number_format($i['amount'],2) ?></span></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready(function(){
		var handleDataTableButtons=function(){"use strict";0!==$("#invoice-table").length&&$("#invoice-table").DataTable({order: [],"columnDefs": [{"targets": 'no-sort',"orderable": false}],dom:"Bfrtip",buttons:[{extend:"copy",className:"btn-sm"},{extend:"csv",className:"btn-sm"},{extend:"excel",className:"btn-sm"},{extend:"pdf",className:"btn-sm"},{extend:"print",className:"btn-sm"}],responsive:!0})},TableManageButtons=function(){"use strict";return{init:function(){handleDataTableButtons()}}}();
		TableManageButtons.init();
	});
</script>