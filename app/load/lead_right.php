<?php
	$notEnglishPauses = $db->getNotPausedEnglishFunnels($_SESSION['user']['agent_id']);
	$notFrenchPauses = $db->getNotPausedFrenchFunnels($_SESSION['user']['agent_id']);
?>
<?php
	# Tokenizer container
	$postActionEmail = Tokenizer::add('post-action-email', 20, 'email');
	$postCaseEmailSend = Tokenizer::add('post-case-sendLead', 30, 'sendLead');
?>
<ul class="nav nav-tabs navtab-bg nav-justified">
	<li class="active">
		<a href="#home1" data-toggle="tab" aria-expanded="false">
			<span class="visible-xs"><i class="fa fa-home"></i></span>
			<span class="hidden-xs"><?php echo $tr['notes'] ?></span>
		</a>
	</li>
	<li class="">
		<a href="#profile1" data-toggle="tab" aria-expanded="false">
			<span class="visible-xs"><i class="fa fa-user"></i></span>
			<span class="hidden-xs"><?php echo $tr['new_task'] ?></span>
		</a>
	</li>
	<li class="">
		<a href="#messages1" data-toggle="tab" aria-expanded="false">
			<span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
			<span class="hidden-xs"><?php echo $tr['send_email'] ?></span>
		</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="home1">
		<form role="form" onsubmit="return validate(this)">
			<input type="hidden" name="id" value="<?php echo $leadID ?>">
			<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-comments', 20, 'comments'); ?>">
			<div class="form-group">
				<label class="control-label" for="notes"><?php echo $tr['write_notes'] ?></label>
				<textarea class="form-control" rows="5" name="comments" id="notes" placeholder="Click to add notes"><?php echo $lead['comments'] ?></textarea>
			</div>
			<button type="submit" class="btn btn-success pull-right"><?php echo $tr['submit'] ?></button>
			<div class="clearfix"></div>
		</form>
	</div>
	<div class="tab-pane" id="profile1">
		<form role="form" onsubmit="return validate(this)">
			<input type="hidden" name="id" value="<?php echo $leadID ?>">
			<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-task', 20, 'task'); ?>">
			<div class="form-group">
				<label class="control-label" for="task"><?php echo $tr['write_task'] ?></label>
				<input class="form-control" name="task" id="task" placeholder="<?php echo $tr['title'] ?>" required/>
			</div>
			<div class="form-group">
				<label class="control-label" for="importance">Importance</label>
				<select name="importance" id="importance" class="form-control fancy" required>
					<option value="3"><?php echo $tr['high'] ?></option>
					<option value="2"><?php echo $tr['medium'] ?></option>
					<option value="1" selected><?php echo $tr['low'] ?></option>
				</select>
			</div>
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" class="form-control" name="date" placeholder="mm/dd/yyyy" id="datepicker" required>
					<span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
					<input type="text" class="form-control" name="time" value="13:14" required>
					<span class="input-group-addon bg-custom b-0 text-white"> <span class="glyphicon glyphicon-time"></span> </span>
				</div>
			</div>


			<button type="submit" class="btn btn-success pull-right"><?php echo $tr['create_task'] ?></button>
			<div class="clearfix"></div>
		</form>
	</div>
	<div class="tab-pane" id="messages1">
		<form role="form" data-parsley-validate="" novalidate>
			<input type="hidden" name="action" value="<?php echo $postActionEmail; ?>">
			<input type="hidden" name="case" value="<?php echo $postCaseEmailSend; ?>">
			<input type="hidden" name="leadID" value="<?php echo IDObfuscator::encode($lead['id']) ?>" >

			<div class="form-group">
				<select id="funnel" class="form-control fancy">
					<option value="">Funnel</option>
					<optgroup label="English">
					<?php foreach ($notEnglishPauses as $fun)
						echo "<option value='".$fun['funnel_id']."'>".$fun['name']."</option>";
					?>
					</optgroup>
					<optgroup label="French">
					<?php foreach ($notFrenchPauses as $fun)
						echo "<option value='".$fun['funnel_id']."'>".$fun['name']."</option>";
					?>
					</optgroup>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="subject" placeholder="<?php echo $tr['subject'] ?>">
			</div>
			<div class="form-group">
				<input type="hidden" id="compose-input" name="content">
				<textarea class="form-control" rows="5" name="email" id="compose-textarea" placeholder="<?php echo $tr['content'] ?>"></textarea>
			</div>
			<button type="submit" class="btn btn-success pull-right"><?php echo $tr['send_email'] ?></button>
			<div class="clearfix"></div>
		</form>
	</div>
</div>

<script>
	$('body').on('change','#funnel', function(e){
		e.preventDefault();
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-selectDropdownFunnel', 20, 'selectDropdownFunnel'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).val() + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$(function(){
		tinymce.init({
			selector: "#compose-textarea",
			theme: "modern",
			height:200,
			menubar: false,
			plugins: [
				'advlist autolink lists link charmap anchor',
				'visualblocks paste jbimages textcolor jbimages'
			],
			toolbar: "fontsizeselect forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages",
			
			setup: function (editor) {
				editor.on('init', function(){
					editor.setContent($('#compose-input').val());
				});

				editor.on('change', function () {
					$('#compose-input').val(editor.getContent().replace(/</g, '&lt;').replace(/>/g, '&gt;'));
					editor.save();
				});
			}
		});
	});

</script>
