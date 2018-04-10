<?php
if(file_exists("../head.php")){
	include("../head.php");
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}

$funnelCat = $db->getFunnelCategories(isset($_SESSION['user']['agent_id'])?$_SESSION['user']['agent_id']:0);

# Tokenizer container
$postActionEmailTemplate = Tokenizer::add('post-action-funnel', 20, 'funnel');
$postCaseEmailTemplateAdd = Tokenizer::add('post-case-funnel-add', 30, 'add');
$postCaseEmailTemplateDelete = Tokenizer::add('post-case-funnel-delete', 30, 'delete');
$postCaseEmailTemplateEdit = Tokenizer::add('post-case-funnel-edit', 30, 'edit');
$postCaseEmailTemplateSingle = Tokenizer::add('post-case-funnel-single', 30, 'single');
$postCaseEmailTemplateView = Tokenizer::add('post-case-funnel-single-view', 30, 'single-view');
$zeAgent = isset($_SESSION['user']['agent_id'])?$_SESSION['user']['agent_id']:0;
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default m-t-20">
			<div class="panel-body p-0">
				<div class="table-responsive p-20">
					<div class="m-b-50">
						<h2 class="page-title pull-left"><?php echo $tr['funnels'] ?></h2>
						<?php if($_SESSION['user']['level'] == 10):?>
							<button id="disable" data-id="<?php echo $_SESSION['user']['user_id'] ?>" class="btn btn-danger waves-effect waves-light pull-right" style="margin-right: 5px"><?php echo $tr['disable_defaults'] ?> <i class="fa fa-times"></i></button>
							<button id="enable" data-id="<?php echo $_SESSION['user']['user_id'] ?>" class="btn btn-success waves-effect waves-light pull-right" style="margin-right: 5px"><?php echo $tr['enable_defaults'] ?> <i class="fa fa-check"></i></button>
						<?php endif ?>
						<button class="btn btn-info waves-effect waves-light pull-right" data-toggle="modal" data-target="#add-modal" style="margin-right: 5px"><?php echo $tr['new_message'] ?> <i class="fa fa-plus"></i></button>
						<button class="btn btn-inverse waves-effect waves-light pull-right" data-toggle="modal" data-target="#add-funnel" style="margin-right: 5px"><?php echo $tr['funnels'] ?> <i class="fa fa-clock-o"></i></button>
					</div>

					<div class="panel-group" id="accordion-funnels">
						<?php
							foreach ($funnelCat as $key => $value){
								$templates = $db->getFunnelsByCat($zeAgent, $value['id'])
						?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion-funnels" href="#collapse-<?php echo $key ?>" aria-expanded="false" class="collapsed">
											<?php echo $value['title'] ?>
										</a>
									</h4>
								</div>
								<div id="collapse-<?php echo $key ?>" class="panel-collapse collapse ">
									<div class="panel-body">
										<table class="table table-striped table-responsive m-0 m-b-10">
											<thead>
												<tr>
													<th width="25%"><?php echo $tr['subject'] ?></th>
													<th width="50%"><?php echo $tr['content'] ?></th>
													<th width="10%"><?php echo $tr['interval'] ?></th>
													<th width="5%"><?php echo $tr['active'] ?></th>
													<th width="10%" class="text-center">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php if(count($templates) > 0): ?>
													<?php foreach($templates as $template):
														$content = strip_tags(html_entity_decode($template['content']));
														$int = intval($template['interval']);
														$val = "";
														if($int == 0)
															$val = $tr["immediately"];
														else if ($int < 24)
															$val = $int . $tr["hours"];
														else if($int / 24 < 30){
															$int = $int / 24;
															$val = $int . $tr["days"];
														}else{
															$int = $int / 720;
															$val = $int . $tr["months"];
														}
													?>

														<tr>
															<td><?php echo $template['name']; ?></td>
															<td><?php echo strlen($content) > 200 ? (substr($content, 0, 200) . "...") : $content; ?></td>
															<td><?php echo $val ?></td>
															<td align="center">
																<input type="checkbox" class="funnel" data-id="<?php echo $template['funnel_id'] ?>" <?php echo $template['status']?'checked':'' ?> data-plugin="switchery" data-color="#81C868" data-size="small"/>
															</td>
															<td class="text-center">
																<a href="#" data-toggle="modal" data-target="#view-template-modal" data-id="<?php echo $template['funnel_id'] ?>" title="<?php echo $tr['view_funnel_message'] ?>" class="on-default edit-row"><i class="fa fa-eye m-r-5"></i></a>
																	<a href="#" data-toggle="modal" data-target="#edit-template-modal" data-id="<?php echo $template['funnel_id'] ?>" title="<?php echo $tr['edit_funnel_message'] ?>" class="on-default edit-row"><i class="fa fa-pencil m-r-5"></i></a>
																	<a href="#" data-id="<?php echo $template['funnel_id']?>" class="on-default remove-row delete"><i class="fa fa-trash-o m-r-10"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr class="text-center">
														<td colspan="99"><?php echo $tr['no_funnels'] ?></td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="add-funnel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-primary">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center"><?php echo $tr['funnels']; ?></h2>
					</div>
					<div class="panel-body" style="background-color: #f4f8fb;padding: 0;">
						<div class="tabs-vertical-env">
							<ul class="nav tabs-vertical">
								<li class="active">
									<a href="#addFunnel" data-toggle="tab" aria-expanded="false"><?php echo $tr['add']; ?></a>
								</li>
								<li class="">
									<a href="#editFunnel" data-toggle="tab" aria-expanded="false"><?php echo $tr['edit']; ?></a>
								</li>
								<li class="">
									<a href="#deleteFunnel" data-toggle="tab" aria-expanded="true"><?php echo $tr['delete']; ?></a>
								</li>
							</ul>
							<div class="tab-content" style="width: 100%;">
								<div class="tab-pane active" id="addFunnel">
									<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
										<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
										<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-cat-add', 30, 'cat-add') ?>">
										<input type="hidden" name="agent" value="<?php echo $_SESSION['user']['agent_id'] ?>">

										<div class="form-group">
											<label for="name" class="col-lg-2 control-label"><?php echo $tr['name']; ?></label>
											<div class="col-lg-10">
												<input type="text" class="form-control" required="" id="name" name="name" placeholder="<?php echo $tr['name']; ?>">
											</div>
										</div>
										<?php if(!isset($_SESSION['user']['agent_id'])): ?>
										<div class="form-group">
											<label for="type" class="col-lg-2 control-label"><?php echo $tr['type']; ?></label>
											<div class="col-lg-10">
												<select name="type" id="type" class="form-control fancy">
													<option value="">--Select Type--</option>
													<option value="home_buyers">Home Buyer</option>
													<option value="home_sellers">Home Seller</option>
												</select>
											</div>
										</div>
										<?php else: ?>
										<input type="hidden" name="type" value="<?php echo $_SESSION['user']['agent_type']?>">
										<?php endif ?>
										<div class="form-group">
											<div class="col-sm-offset-4 col-sm-8">
												<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['add']; ?></button>
												<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel']; ?></button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="editFunnel">
									<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
										<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
										<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-cat-edit', 30, 'cat-edit') ?>">

										<div class="form-group">
											<label for="interval" class="col-lg-2 control-label"><?php echo $tr['funnel']; ?></label>
											<div class="col-lg-10">
												<select name="selectFunnel" class="form-control fancy">
													<option value=""><?php echo $tr['select_funnel']; ?></option>
													<?php
													foreach ($funnelCat as $value)
														echo "<option data-agent='".$value['agent']."' value='".$value['id']."'>".$value['title']."</option>";
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="editName" class="col-lg-2 control-label"><?php echo $tr['name']; ?></label>
											<div class="col-lg-10">
												<input type="text" class="form-control" required="" id="editName" name="name" placeholder="<?php echo $tr['name']; ?>">
											</div>
										</div>
										<?php if(!isset($_SESSION['user']['agent_id'])): ?>
										<div class="form-group">
											<label for="editType" class="col-lg-2 control-label"><?php echo $tr['type']; ?></label>
											<div class="col-lg-10">
												<select name="type" id="editType" class="form-control fancy">
													<option value="">--Select Type--</option>
													<option value="home_buyers">Home Buyer</option>
													<option value="home_sellers">Home Seller</option>
												</select>
											</div>
										</div>
										<?php else: ?>
										<input type="hidden" name="type" value="<?php echo $_SESSION['user']['agent_type']?>">
										<?php endif ?>
										<div class="form-group">
											<div class="col-sm-offset-4 col-sm-8">
												<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['edit']; ?></button>
												<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel']; ?></button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="deleteFunnel">
									<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
										<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
										<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-cat-delete', 30, 'cat-delete') ?>">
										<div class="form-group">
											<label for="interval" class="col-lg-2 control-label"><?php echo $tr['funnel']; ?></label>
											<div class="col-lg-10">
												<select name="funnel" class="form-control fancy">
													<option value=""><?php echo $tr['select_funnel']; ?></option>
													<?php
													foreach ($funnelCat as $value)
														echo "<option data-agent='".$value['agent']."' value='".$value['id']."'>".$value['title']."</option>";
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-4 col-sm-8">
												<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['delete']; ?></button>
												<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel']; ?></button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-info">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center"><?php echo $tr['add_new_funnel_message'] ?></h2>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
							<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
							<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateAdd; ?>">
							<div class="form-group">
								<label for="funnel"class="col-lg-2 control-label"><?php echo $tr['funnel'] ?></label>
								<div class="col-lg-10">
									<select name="funnel" class="form-control fancy">
										<option value=""><?php echo $tr['select_funnel'] ?></option>
										<?php
										foreach ($funnelCat as $value)
											echo "<option value='".$value['id']."'>".$value['title']."</option>";
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-lg-2 control-label"><?php echo $tr['name'] ?></label>
								<div class="col-lg-10">
									<input type="text" class="form-control" required="" data-parsley-length="[5, 250]" id="name" name="name" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="lang"class="col-lg-2 control-label"><?php echo $tr['language'] ?></label>
								<div class="col-lg-4">
									<select name="lang" id="lang" class="form-control fancy">
										<option value="EN">English</option>
										<option value="FR">Français</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="interval"class="col-lg-2 control-label"><?php echo $tr['interval'] ?></label>
								<div class="col-lg-5">
									<input type="number" class="form-control" required=""  id="intervalNum" name="intervalNum" placeholder="#">
								</div>
								<div class="col-lg-5">
									<select name="intervalFrame" class="form-control fancy">
										<option value="">&nbsp;</option>
										<option value="0"><?php echo $tr['immediately'] ?></option>
										<option value="1"><?php echo $tr['hours'] ?></option>
										<option value="24"><?php echo $tr['days'] ?></option>
										<option value="720"><?php echo $tr['months'] ?></option>
									</select>
								</div>
							</div>
							<div class="form-group clearfix">
								<label class="col-lg-2 control-label"> <?php echo $tr['content'] ?></label>
								<div class="col-lg-10">
									<textarea name="content" class="form-control" placeholder="Content..."></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
									<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['add'] ?></button>
									<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel'] ?></button>
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
						<h2 class="panel-title text-center"><?php echo $tr['edit_funnel_message'] ?></h2>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
							<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">
							<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateEdit; ?>">
							<input type="hidden" name="id" value="-1">
							<div class="form-group">
								<label for="funnel"class="col-lg-2 control-label"><?php echo $tr['funnel'] ?></label>
								<div class="col-lg-10">
									<select name="funnel" class="form-control fancy">
										<option value=""><?php echo $tr['select_funnel'] ?></option>
										<?php
										foreach ($funnelCat as $value)
											echo "<option value='".$value['id']."'>".$value['title']."</option>";
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-lg-2 control-label"><?php echo $tr['name'] ?></label>
								<div class="col-lg-10">
									<input type="text" class="form-control" required="" data-parsley-length="[5, 250]" id="name" name="name" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="lang"class="col-lg-2 control-label"><?php echo $tr['language'] ?></label>
								<div class="col-lg-4">
									<select name="lang" id="lang" class="form-control fancy">
										<option value="EN">English</option>
										<option value="FR">Français</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="interval"class="col-lg-2 control-label"><?php echo $tr['interval'] ?></label>
								<div class="col-lg-5">
									<input type="number" class="form-control" required=""  id="intervalNum" name="intervalNum" placeholder="#">
								</div>
								<div class="col-lg-5">
									<select name="intervalFrame" class="form-control fancy">
										<option value="">&nbsp;</option>
										<option value="0"><?php echo $tr['immediately'] ?></option>
										<option value="1"><?php echo $tr['hours'] ?></option>
										<option value="24"><?php echo $tr['days'] ?></option>
										<option value="720"><?php echo $tr['months'] ?></option>
									</select>
								</div>
							</div>
							<div class="form-group clearfix">
								<label class="col-lg-2 control-label"> <?php echo $tr['content'] ?></label>
								<div class="col-lg-10">
									<textarea name="content" id="edit-content-textarea" class="form-control" placeholder="Content..."></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
									<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['save'] ?></button>
									<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel'] ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="view-template-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-purple">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center"><?php echo $tr['view_funnel_message'] ?></h2>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-user-information dataTable">
									<tbody>
									<tr>
										<th><?php echo $tr['name'] ?></th>
										<td target="name"></td>
									</tr>
									<tr>
										<th><?php echo $tr['content'] ?></th>
										<td target="content"></td>
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
		$('#edit-template-modal').on('show.bs.modal', function(e){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateSingle; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#view-template-modal').on('show.bs.modal', function(e){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionEmailTemplate; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseEmailTemplateView; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#add-modal input[name="name"]').on('change keyup keydown', function(){
			$('#add-modal input[name="slug"]').val($(this).val().replace(/\s/g, '-').toLowerCase());
		});

		$('body').on('change','.funnel', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-funnel', 20, 'funnel'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-pause', 20, 'pause'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="switch" value="' + $(this).prop("checked") + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('change','select[name="selectFunnel"]', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-funnel', 20, 'funnel'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-selectCat', 20, 'selectCat'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).val() + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		<?php if(file_exists("../head.php")): ?>
		$('[data-plugin="switchery"]').each(function (idx, obj) {
			new Switchery($(this)[0], $(this).data());
		});
	<?php endif ?>

			tinymce.init({
				selector: "textarea",
				theme: "modern",
				<?php echo $_SESSION['user']['agent_lang'] == "FR"?"language: 'fr_FR',":"" ?>
				height:200,
				menubar: false,
				plugins: [
				'advlist autolink  lists linkcharmap  anchor ',
				' visualblocks  paste jbimagestextcolorjbimages'
				],
				toolbar: "fontsizeselectforecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent| link jbimages"
			});

	$('body').on('click', '#disable', function(e){
		e.preventDefault();
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-funnel', 20, 'funnel'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-disable', 20, 'disable'); ?>">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('body').on('click', '#enable', function(e){
		e.preventDefault();
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-funnel', 20, 'funnel'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-funnel-enable', 20, 'enable'); ?>">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('body').on('click', '.delete', function(e){
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
