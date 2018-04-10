<?php 
	if(file_exists("../head.php")){
		include("../head.php");
		$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
	}
?>
<h2 class="page-title pull-left"><?php echo $tr['lead_statues'] ?></h2>
		<button class="btn btn-danger waves-effect waves-light pull-right" data-toggle="collapse" data-target="#newLevel" aria-expanded="false" aria-controls="newLevel"><i class="fa fa-plus"></i>&nbsp;<?php echo $tr['new'] ?></button>
		<div class="clearfix"></div>
		<form id="newLevel" class="form-horizontal collapse m-t-15" role="form" data-parsley-validate="" novalidate>
			<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-status', 20, 'status'); ?>">
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-add', 30, 'add'); ?>">
			<input type="hidden" name="agent_fk" value="<?php echo $_SESSION['user']['agent_id'] ?>">

			<div class="form-group">
				<div class="col-sm-5">
					<input type="text" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_GEN_FIELD_LENGTH . "," . Config::MAX_GEN_FIELD_LENGTH ?>]" name="name_en" placeholder="Level name En">
				</div>
				<div class="col-sm-5">
					<input type="text" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_GEN_FIELD_LENGTH . "," . Config::MAX_GEN_FIELD_LENGTH ?>]" name="name_fr" placeholder="Level name Fr">
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-primary btn-block waves-effect waves-light">Add</button>
				</div>
			</div>
		</form>

		<div>
			<table class="table table-striped m-0">
				<thead>
					<tr>
						<th width="40%" class="text-left"><?php echo $tr['english_name'] ?></th>
						<th width="40%" class="text-left"><?php echo $tr['french_name'] ?></th>
						<th width="20%" class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($db->getAgentStatus($_SESSION['user']['agent_id']) as $value) {
						?>
						<tr>
							<td class="text-left">
								<div class="toggleHide"><?php echo $value['name_en']; ?></div>
								<div class="toggleHide" style="display:none">
									<input type="text" name="name_en" value="<?php echo $value['name_en'] ?>" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_GEN_FIELD_LENGTH . "," . Config::MAX_GEN_FIELD_LENGTH ?>]">
								</div>
							</td>
							<td class="text-left">
								<div class="toggleHide"><?php echo $value['name_fr']; ?></div>
								<div class="toggleHide" style="display:none">
									<input type="text" name="name_fr" value="<?php echo $value['name_fr'] ?>" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_GEN_FIELD_LENGTH . "," . Config::MAX_GEN_FIELD_LENGTH ?>]">
								</div>
							</td>
							<td class="category text-center">
								<?php if($value['agent_fk'] != 0){ ?>
								<div class="toggleHide">
									<a href="javascript:" title="Edit Level" class="edit"><i class="fa fa-pencil"></i></a>&nbsp;
									<a href="javascript:" data-id="<?php echo $value['id'] ?>" data-name="<?php echo $value['name_en'] ?>" data-action="<?php echo Tokenizer::add('post-action-status', 20, 'status'); ?>" data-case="<?php echo Tokenizer::add('post-case-delete', 30, 'delete'); ?>" class="delete remove-row" title="Delete Status"><i class="fa fa-times"></i></a>
								</div>
								<div class="toggleHide" style="display:none">
									<a href="#" data-id="<?php echo $value['id'] ?>" data-name="<?php echo $value['name_en'] ?>" data-action="<?php echo Tokenizer::add('post-action-status', 20, 'status'); ?>" data-case="<?php echo Tokenizer::add('post-case-edit', 30, 'edit'); ?>" title="Save Status" class="saveStatus"><i class="fa fa-check"></i></a>&nbsp;
									<a href="#" class="cancel cancel-row" title="Cancel Edit"><i class="fa fa-times"></i></a>
								</div>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

<script>	

$('body').on('click', '.cancel, .edit', function(e){
	e.preventDefault();
	$(this).closest('tr').find('.toggleHide').toggle();
});

$('body').on('click', '.delete', function(e){
	e.preventDefault();
	var id = $(this).data('id');
	var action = $(this).data('action');
	var actionCase = $(this).data('case');
	var name = $(this).data('name');

	swal({   
		title: "Are you sure?",   
		text: "You will not be able to recover this " + status + "!",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",   
		closeOnConfirm: false
	}, function(){
		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
			'<input type="hidden" name="action" value="' + action + '">'
			+ '<input type="hidden" name="case" value="' + actionCase + '">'
			+ '<input type="hidden" name="id" value="' + id + '">'
			+ '<input type="hidden" name="name" value="' + name + '">');
		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
		$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
	});
});

$('body').on('click', '.saveStatus', function(e){
	e.preventDefault();
	var name_en = $(this).closest('tr').find("input[name='name_en']").val();
	var id = $(this).attr('data-id');
	var name_fr = $(this).closest('tr').find("input[name='name_fr']").val();
	var action = $(this).data('action');
	var actionCase = $(this).data('case');
	$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
		'<input type="hidden" name="action" value="' + action + '">'
		+ '<input type="hidden" name="case" value="' + actionCase + '">'
		+ '<input type="hidden" name="id" value="' + id + '">'
		+ '<input type="hidden" name="name_en" value="' + name_en + '">'
		+ '<input type="hidden" name="name_fr" value="' + name_fr + '">');
	$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
	$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
});
</script>