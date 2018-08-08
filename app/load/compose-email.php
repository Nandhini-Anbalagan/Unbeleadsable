<?php
	# Tokenizer container
	$postActionEmail = Tokenizer::add('post-action-email', 20, 'email');

	if(isset($_SESSION['user']['agent_signature']))
		$postCaseEmailSend = Tokenizer::add('post-case-email', 30, 'sendMultipleLead');
	else
		$postCaseEmailSend = Tokenizer::add('post-case-email', 30, 'send');

	$templates = $db->getTemplates();
?>

<div id="compose-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg in">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center"><?php echo $tr['compose_email'] ?></h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo $postActionEmail; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseEmailSend; ?>">
						<input type="hidden" name="ids">
						<div class="form-group">
							<label for="to" class="col-lg-2 control-label"><?php echo $tr['to'] ?></label>
							<div class="col-lg-10">
								<input type="text" name="to" class="form-control" value="" readonly>
							</div>
						</div>

						<?php if($_SESSION['user']["level"] == 10){ ?>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo $tr['subject'] ?></label>
							<div class="col-lg-10">
								<input type="text" name="subject" class="form-control" value="">
							</div>
						</div>
						<?php }else{ ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Template</label>
							<div class="col-lg-10">
								<select name="template" class="form-control">
									<option value="" selected>Blank</option>
									<?php foreach($templates as $template): ?>
										<option value="<?php echo $template['email_template_id']?>"><?php echo $template['name']; ?> (<?php echo $template['slug']; ?>)</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<div class="form-group clearfix">
							<label class="col-lg-2 control-label"> <?php echo $tr['content'] ?></label>
							<div class="col-lg-10">
								<textarea id="compose-textarea" name="content" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['send_email'] ?></button>
								<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5 cancel" data-dismiss="modal"><?php echo $tr['cancel'] ?></button>
							</div>
						</div>

						<?php if($_SESSION['user']["level"] == 10){ ?>
						<small>
							<?php
								if($_SESSION['user']['agent_lang'] == 'EN')
									echo "You can use [NAME] and/or [SHORTADDRESS] to refference the lead's name and/or address";
								else
									echo "Vous pouvez utilisez [NAME] et/ou [SHORTADDRESS] pour faire référence au nom et/ou l'adresse du prospect";
							?>
						</small>
						<?php } ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('select[name="template"]').select2({
			placeholder: "Choose a template..."
		});

		$('select[name="template"]').on('change', function(){
			$.get("core/email/preview/"+$(this).val(), function(data){
				tinymce.get('compose-textarea').setContent(data);
			});

		});

		tinymce.init({
			selector: "#compose-textarea",
			theme: "modern",
			<?php echo $_SESSION['user']['agent_lang'] == "FR"?"language: 'fr_FR',":"" ?>
			height:200,
			menubar: false,
			plugins: [
				'advlist autolink lists link charmap anchor',
				'visualblocks paste jbimages textcolor jbimages'
			],
			toolbar: "fontsizeselect forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages"
		});
	});
</script>