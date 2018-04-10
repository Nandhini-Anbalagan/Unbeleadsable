<?php foreach ($db->getTasks($leadID) as $task) { ?>
<div class="card-box m-b-10">
	<div class="table-box opport-box">
		<div class="table-detail checkbx-detail">
			<input id="checkbox1" type="checkbox" class="styledCheckbox done" data-id="<?php echo $task['task_id'] ?>" <?php echo $task['status'] == "2"?'checked':'' ?>>
		</div>

		<div class="table-detail">
			<div class="member-info">
				<h4 class="m-t-0"><b><?php echo $task['note'] ?></b></h4>
				<p class="text-dark m-b-5"><b>Due: </b> <span class="text-muted"><?php echo $task['dateTime'] ?></span></p>
			</div>
		</div>

		<div class="table-detail lable-detail">
			<?php
				if($task['importance'] == 1)
					echo '<span class="label label-danger">High</span>';
				else if($task['importance'] == 2)
					echo '<span class="label label-warning">Medium</span>';
				else if($task['importance'] == 3)
					echo '<span class="label label-success">Low</span>';
			?>

		</div>

		<div class="table-detail table-actions-bar"><a href="#" data-id="<?php echo $task['task_id'] ?>" class="table-action-btn delete"><i class="md md-close"></i></a></div>
	</div>
</div>
<?php } ?>

<script>
	$('body').on('change','.done', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-taskDone', 20, 'taskDone'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="done" value="' + $(this).prop("checked") + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

	$('body').on('click', '.delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover these leads!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		}, function(){
			$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
				'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-taskDelete', 20, 'taskDelete'); ?>">'
				+ '<input type="hidden" name="id" value="' + id + '">');
			$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
			$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
		});
	});
</script>