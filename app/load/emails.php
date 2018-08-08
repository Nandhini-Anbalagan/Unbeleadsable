<?php
	if(file_exists("../head.php")){
		include("../head.php");
		$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
	}

	$emails = $db->getEmails();
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default m-t-20">
			<div class="panel-body p-0">
				<div class="table-responsive p-20">
					<table id="email-table" class="table table-striped m-0 m-b-10">
						<thead>
							<tr>
								<th width="15%">To</th>
								<th>Content</th>
								<th width="25%" class="text-right">Sent Date</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($emails as $email): ?>
								<?php $content = strip_tags(html_entity_decode($email['content'])); ?>
								<tr data-id="<?php echo $email['email_id']; ?>">
									<td><?php echo $email['to']; ?></td>
									<td><?php echo strlen($content) > 200 ? (substr($content, 0, 200) . "...") : $content; ?></td>
									<td class="text-right">
										<?php echo Functions::userFriendlyDate($email['email_sent_date']); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="email-preview" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title">Compose Email</h2>
				</div>
				<div class="panel-body"></div>
				</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#email-table').dataTable({"bSort": false});

		$('body').on('click', '#email-table tbody tr', function(){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::get('post-action-email'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-email-fetch', 30, 'preview'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});
</script>
