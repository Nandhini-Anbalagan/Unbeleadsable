<?php
	if(file_exists("../head.php")){
		include("../head.php");
		$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
		$templates = $db->getTemplates();
	}
		
	# Tokenizer container
	$postActionEmailTemplate = Tokenizer::add('post-action-email-template', 20, 'email');
	$postCaseEmailTemplateAdd = Tokenizer::add('post-case-email-template-add', 30, 'add');
	$postCaseEmailTemplateDelete = Tokenizer::add('post-case-email-template-delete', 30, 'delete');
	$postCaseEmailTemplateEdit = Tokenizer::add('post-case-email-template-edit', 30, 'edit');
	$postCaseEmailTemplateSingle = Tokenizer::add('post-case-email-template-single', 30, 'single');
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default m-t-20">
			<div class="panel-body p-0">
				<div class="table-responsive p-20">
					<div class="m-b-50">
						<button class="btn btn-primary waves-effect waves-light pull-right" data-toggle="modal" data-target="#add-modal">New Template <i class="fa fa-plus"></i></button>
					</div>
					<table class="table table-striped m-0 m-b-10">
						<thead>
							<tr>
								<th width="25%">Name</th>
								<th width="45%">Content</th>
								<th width="20%">Slug</th>
								<th width="10%" class="text-right">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($templates) > 0): ?>
								<?php foreach($templates as $template): ?>
									<?php $content = strip_tags(html_entity_decode($template['content'])); ?>
									<tr>
										<td><?php echo $template['name']; ?></td>
										<td><?php echo strlen($content) > 200 ? (substr($content, 0, 200) . "...") : $content; ?></td>
										<td><?php echo $template['slug']; ?></td>
										<td class="text-right">
											<a href="#" data-toggle="modal" data-target="#edit-template-modal" data-id="<?php echo $template['email_template_id'] ?>" title="Edit Template" class="on-default edit-row"><i class="fa fa-pencil m-r-5"></i></a>
											<a href="#" data-id="<?php echo $template['email_template_id']?>" class="on-default remove-row delete"><i class="fa fa-trash-o m-r-10"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr class="text-center">
									<td colspan="99">No template to display...</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Add New Template</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateAdd; ?>">

						<div class="form-group">
							<label for="name" class="col-lg-2 control-label">Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_EMAIL_NAME_LENGTH . "," . Config::MAX_EMAIL_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="slug"class="col-lg-2 control-label">Slug</label>
							<div class="col-lg-10">
								<input type="text" id="slug" name="slug" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_EMAIL_SLUG_LENGTH . "," . Config::MAX_EMAIL_SLUG_LENGTH ?>]" placeholder="Slug">
							</div>
						</div>
						<div class="form-group">
							<label for="slug"class="col-lg-2 control-label">Interval</label>
							<div class="col-lg-10">
								<input type="text" id="slug" name="slug" class="form-control" required="" placeholder="interval">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-lg-2 control-label"> Content</label>
							<div class="col-lg-10">
								<input id="content-input" type="hidden" name="content">
								<textarea id="content-textarea" class="form-control" placeholder="Content..."></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-primary waves-effect waves-light">Add</button>
								<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="edit-template-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Edit Template</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateEdit; ?>">
						<input type="hidden" name="id" value="-1">

						<div class="form-group">
							<label for="name" class="col-lg-2 control-label">Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_EMAIL_NAME_LENGTH . "," . Config::MAX_EMAIL_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="slug"class="col-lg-2 control-label">Slug</label>
							<div class="col-lg-10">
								<input type="text" id="slug" name="slug" class="form-control" required="" data-parsley-length="[<?php echo Config::MIN_EMAIL_SLUG_LENGTH . "," . Config::MAX_EMAIL_SLUG_LENGTH ?>]" placeholder="Slug">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-lg-2 control-label"> Content</label>
							<div class="col-lg-10">
								<input id="edit-content-input" type="hidden" name="content">
								<textarea id="edit-content-textarea" class="form-control" placeholder="Content..."></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
								<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#edit-template-modal').on('show.bs.modal', function(e){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateSingle; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
		
		$('#add-modal input[name="name"]').on('change keyup keydown', function(){
			$('#add-modal input[name="slug"]').val($(this).val().replace(/\s/g, '-').toLowerCase());
		});
		
		tinymce.init({
			selector: "#content-textarea",
			theme: "modern",
			height:200,
			menubar: false,
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table contextmenu directionality emoticons template paste textcolor"
			],
			toolbar: "forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent, link",
			style_formats: [
				{title: 'Bold text', inline: 'b'},
				{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				{title: 'Example 1', inline: 'span', classes: 'example1'},
				{title: 'Example 2', inline: 'span', classes: 'example2'},
				{title: 'Table styles'},
				{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],
			setup: function (editor) {
				editor.on('init', function(){
					editor.setContent($('#content-input').val());
				});
				
				editor.on('change', function () {
					$('#content-input').val(editor.getContent().replace(/</g, '&lt;').replace(/>/g, '&gt;'));
					editor.save();
				});
			}
		});
		
		tinymce.init({
			selector: "#edit-content-textarea",
			theme: "modern",
			height:200,
			menubar: false,
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table contextmenu directionality emoticons template paste textcolor"
			],
			toolbar: "forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent, link",
			style_formats: [
				{title: 'Bold text', inline: 'b'},
				{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				{title: 'Example 1', inline: 'span', classes: 'example1'},
				{title: 'Example 2', inline: 'span', classes: 'example2'},
				{title: 'Table styles'},
				{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],
			setup: function (editor) {
				editor.on('init', function(){
					editor.setContent($('#edit-content-input').val().replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
				});
				
				editor.on('change', function () {
					$('#edit-content-input').val(editor.getContent().replace(/</g, '&lt;').replace(/>/g, '&gt;'));
					editor.save();
				});
			}
		});

		$(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window").length) {
				e.stopImmediatePropagation();
			}
		});
		
		$('body').on('click', '.template-container .delete', function(e){
			e.preventDefault();
			var id = $(this).data('id');
		
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this template!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: false
			}, function(){
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
					'<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">'
					+ '<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateDelete; ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
			});
		});
	});
</script>