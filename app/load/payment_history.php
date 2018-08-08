<?php isset($_SESSION['teammate'])?die("Access Denied"):'' ?>

<?php 
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
	$postActionPayments = Tokenizer::add('post-action-payments', 20, 'payments');
	$postCaseMailTo = Tokenizer::add('post-case-mailTo', 30, 'mailTo');
	$nextBill = $db->agentNextPayment($_SESSION['user']['agent_id'])['next_billing'];
?>

<h2 class="page-title pull-left"><?php echo $tr['payment_history'] ?></h2>
<h3 class="page-title pull-right"><small class="text-danger"><i><?php echo $tr['history_ad_sub_title'] . " " . Functions::userFriendlyDate($nextBill)?></i></small></h3>

<div class="clearfix"></div>
<h3><?php echo $tr['history_subscription_title'] . ", " . $tr['budget_payment'] ?></h3>
<div class="table-responsive">
	<table class="table table-hover mails m-0 table table-actions-bar">
		<thead>
			<tr>
				<th><?php echo $tr['invoice_num'] ?></th>
				<th>Date</th>
				<th><?php echo $tr['amount'] ?></th>
				<th>Description</th>
				<th><?php echo $tr['status'] ?></th>
				<th>Action</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($db->getAgentInvoice($_SESSION['user']['agent_id']) AS $value) { 
				$desc = "";
				$desc .= $value['install'] > 0?'Installation, ':'';
				$desc .= $value['monthly'] > 0?'Montly Payment, ':'';
				$desc .= $value['ads'] > 0?'Ads Budget':'';
			?>
			<tr>
				<td><?php echo $value['invoice_num'] ?></td>
				<td><?php echo Functions::userFriendlyDate($value['invoice_date']) ?></td>
				<td>$<?php echo $value['install'] +  $value['monthly'] + $value['ads']?> USD</td>
				<td><?php echo $desc ?></td>
				<td>Successful</td>
				<td class="text-center">
					<a href="http://unbeleadsable.com/app/receipt/<?php echo $value['invoice_num'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
</div>

<script>
	$('a.mailTo').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({   
				title: "Are you sure?",   
				text: "You want to send this invoice to your email? ",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, send it!",   
				closeOnConfirm: false 
			}, function(){
				$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionPayments; ?>">'
					+ '<input type="hidden" name="case" value="<?php echo $postCaseMailTo; ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$('#<?php echo $dynamicFormId; ?>').submit();
				$('#<?php echo $dynamicFormId; ?>').empty();
			});
		});
</script>

