<?php 
	# Tokenizer container
	$postActionAgentLead = Tokenizer::add('post-action-agentLead', 20, 'agentLead');
	$postFile = Tokenizer::add('post-case-avatar', 30, 'avatar');  
?>
<h2 class="page-title"><?php echo $tr['account_settings'] ?></h2>
<br>
<div class="clearfix"></div>
<?php if(!isset($_SESSION['teammate'])){ ?>

<form role="form" class="col-md-7" data-parsley-validate="" novalidate>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-settings', 20, 'settings'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-settings-edit-agent', 30, 'edit-agent'); ?>">

	<div class="form-group">
		<label class="control-label"><?php echo $tr['conf_username'] ?></label>
		<input type="text" name="username" class="form-control" value="<?php echo $_SESSION['user']['username'] ?>" readonly>
	</div>

	<div class="form-group">
		<label class="control-label"><?php echo $tr['agent_name'] ?></label>
		<input type="text" name="name" class="form-control" value="<?php echo $_SESSION['user']['agent_name'] ?>">
	</div>

	<div class="form-group">
		<label class="control-label"><?php echo $tr['address'] ?></label>
		<input type="text" id="address" name="address" class="form-control" value="<?php echo $_SESSION['user']['agent_address'] ?>">
	</div>

	<div class="form-group">
		<label class="control-label"><?php echo $tr['agent_email'] ?></label>
		<input type="text" name="email" class="form-control" value="<?php echo $_SESSION['user']['agent_email'] ?>">
	</div>

	<div class="form-group">
		<label class="control-label"><?php echo $tr['conf_phone'] ?></label>
		<input type="text" name="phone" class="form-control" value="<?php echo $_SESSION['user']['agent_phone'] ?>">
	</div>

	<div class="form-group">
		<label class="control-label">License #</label>
		<input type="text" name="license" class="form-control" value="<?php echo $_SESSION['user']['agent_license'] ?>">
	</div>

	<div class="form-group">
		<label class="control-label">Board</label>
		<input type="text" name="board" class="form-control" value="<?php echo $_SESSION['user']['agent_board'] ?>">
	</div>

	<div class="form-group well">
		<label class="control-label"><?php echo $tr['conf_change_pass'] ?></label>
		<input type="password" name="old_password" class="form-control" placeholder="<?php echo $tr['old_password'] ?>"><br>
		<input type="password" name="new_password" class="form-control" placeholder="<?php echo $tr['new_password'] ?>"><br>
		<input type="password" name="confirm_password" class="form-control" placeholder="<?php echo $tr['confirm_password'] ?>">
	</div>

	<div class="form-group" data-toggle="popover" data-trigger="hover" data-placement="right"  data-html="true" data-content="<img src='uploads/avatars/<?php echo $_SESSION['user']['agent_avatar'] ?>' width='150px'>" >
		<label class="control-label">Avatar</label>
		<input type="file" class="filestyle" data-buttonbefore="true" data-buttonText="<?php echo $_SESSION['user']['agent_lang'] == 'EN'?'Choose file':'Choisissez le fichier' ?>">
		<input type="hidden" name="avatar">
	</div>

	<div class="form-group">
		<label class="control-label"><?php echo $tr['signature'] ?></label>

		<textarea name="signature" id="signature" rows="7" class="form-control"><?php echo preg_replace('/\v+|\\\r\\\n/','<br/>',$_SESSION['user']['agent_signature']) ?></textarea>
	</div>

	<div class="form-group">
		<label style="padding: 0" class="control-label col-sm-6"><?php echo $tr['sms_noty'] ?></label>

		<input type="checkbox" class="form-control" name="phone_notification" <?php echo $_SESSION['user']['phone_alert'] == 1?'checked':'' ?> data-plugin="switchery" data-color="#81C868" data-size="medium"/>
	</div>

	<div class="form-group">
		<label style="padding: 0" class="control-label col-sm-6"><?php echo $tr['email_noty'] ?></label>
		<input type="checkbox" class="form-control" name="email_notification" <?php echo $_SESSION['user']['email_alert'] == 1?'checked':'' ?> data-plugin="switchery" data-color="#81C868" data-size="medium"/>
	</div>

	<div class="form-group">
		<label><?php echo $tr['language'] ?></label>
		<select class="form-control fancy" name="lang">
			<option value="EN" <?php echo $_SESSION['user']['agent_lang'] == "EN"?"selected":"" ?>>English</option>
			<option value="FR" <?php echo $_SESSION['user']['agent_lang'] == "FR"?"selected":"" ?>>Français</option>
		</select>
	</div>

	<div class="form-group">
		<input type="submit" value="<?php echo $tr['submit'] ?>" class="btn btn-danger btn-block">
	</div>
	<div class="clearfix"></div>

<?php }else{
		$user = $db->getUser($_SESSION['teammate']['id']);
	?>
<form role="form" class="col-md-7 form-horizontal" data-parsley-validate="" novalidate>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-settings', 20, 'settings'); ?>">
	<input type="hidden" id="case" name="case" value="<?php echo Tokenizer::add('post-case-editUserSelf', 20, 'editUserSelf'); ?>">

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label"><?php echo $tr['name'] ?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" value="<?php echo $user['name'] ?>" placeholder="<?php echo $tr['name'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-sm-3 control-label"><?php echo $tr['username'] ?></label>
		<div class="col-sm-9">
			<input type="text" id="username" name="username" value="<?php echo $user['username'] ?>" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" readonly placeholder="<?php echo $tr['username'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label"><?php echo $tr['email'] ?></label>
		<div class="col-sm-9">
			<input type="email" id="email" name="email" value="<?php echo $user['email'] ?>" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="<?php echo $tr['enter_valid_email'] ?>">
		</div>
	</div>
	<div class="form-group well">
		<label class="control-label col-sm-3"><?php echo $tr['conf_change_pass'] ?></label>
		<div class="col-sm-9">
			<input type="password" name="old_password" class="form-control" placeholder="<?php echo $tr['old_password'] ?>"><br>
			<input type="password" name="new_password" class="form-control" placeholder="<?php echo $tr['new_password'] ?>"><br>
			<input type="password" name="confirm_password" class="form-control" placeholder="<?php echo $tr['confirm_password'] ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?php echo $tr['language'] ?></label>
		<div class="col-sm-9">
			<select class="form-control fancy" name="lang">
				<option value="EN" <?php echo $_SESSION['user']['agent_lang'] == "EN"?"selected":"" ?>>English</option>
				<option value="FR" <?php echo $_SESSION['user']['agent_lang'] == "FR"?"selected":"" ?>>Français</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo $tr['save'] ?></button>
			<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal"><?php echo $tr['cancel'] ?></button>
		</div>
	</div>

	<?php } ?>
</form>
<div class="col-md-4 well pull-right">
	<?php echo $tr['target'] ?> <br>
	<?php echo $_SESSION['user']['area_name'] ?>
	<hr>
	<?php echo $tr['conf_right_text'] ?>

</div>
<script src="assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>
<script>
$(function(){
	tinymce.init({
		selector: '#signature',
		height: 250,
		menubar: false,
		<?php echo $_SESSION['user']['agent_lang'] == "FR"?"language: 'fr_FR',":"" ?>
		plugins: [
		'advlist autolink lists link charmap anchor',
		'visualblocks paste jbimages textcolor'
		],
		toolbar: 'fontsizeselect forecolor bold italic | alignleft aligncenter alignright | link jbimages',
		content_css: '//www.tinymce.com/css/codepen.min.css',
		relative_urls: false,
		lineheight_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 36pt",
		force_br_newlines : true,
		force_p_newlines : false,
		forced_root_block : '',
		fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt'
	});
});

$(':file').change(function(){
	var file = this.files[0],
	type = file.type,
	types = ["image/png", "image/jpg", "image/jpeg"],
	formData = new FormData();

	formData.append("action", '<?php echo $postActionAgentLead; ?>');
	formData.append("case", '<?php echo $postFile; ?>');
	formData.append("file", file);

	if(types.indexOf(type) != -1){
		$.ajax({
			url: 'core.php',
			type: 'POST',
			cache: false,
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				$('input[name="avatar"]').val(response);
			}
		});
	}else{
		$(this).val("");
		generateNotification("Sorry file type not valid!", "bottom-right", "error", 3000, true)
	}
});

</script>
