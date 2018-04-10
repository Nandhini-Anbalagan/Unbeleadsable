<?php isset($_SESSION['teammate'])?die("Access Denied"):'' ?>
<?php
	if(file_exists('../head.php'))
		require_once('../head.php');

	$teamUsers = $db->getTeamUsers($_SESSION['user']['user_id']);

	# Tokenizer container
	$postAddUser = Tokenizer::add('post-case-addUser', 20, 'addUser');
	$postEditUser = Tokenizer::add('post-case-editUser', 30, 'editUser');
?>

<h2 class="page-title text-center"><?php echo $tr['add_team_members'] ?></h2>
<br>
<div class="clearfix"></div>
<div class="col-md-8">
<form id="team-user-form" class="form-horizontal" role="form" data-parsley-validate="" novalidate>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-settings', 20, 'settings'); ?>">
	<input type="hidden" id="case" name="case" value="<?php echo $postAddUser ?>">
	<input type="hidden" name="main_user" value="<?php echo $_SESSION['user']['user_id'] ?>">
	<input type="hidden" name="country" value="<?php echo $_SESSION['user']['user_country'] ?>">
	<input type="hidden" name="user_id" value="0">

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label"><?php echo $tr['name'] ?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="<?php echo $tr['name'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-sm-3 control-label"><?php echo $tr['username'] ?></label>
		<div class="col-sm-9">
			<input type="text" id="username" name="username" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" placeholder="<?php echo $tr['username'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label"><?php echo $tr['email'] ?></label>
		<div class="col-sm-9">
			<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="<?php echo $tr['enter_valid_email'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-3 control-label"><?php echo $tr['password'] ?></label>
		<div class="col-sm-6">
			<input type="text" id="password" name="password" data-parsley-length="[<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>]" placeholder="<?php echo $tr['password'] ?>" required class="form-control">
		</div>
		<div class="col-sm-1 p-t-10">
			<button class="btn btn-info btn-xs" title="click to generate password" id="genBtn"><i class="fa fa-refresh"></i></button>
		</div>
	</div>

	<div class="form-group ">
		<div class="col-sm-9 col-sm-offset-4">
			<input id="emailUser" type="checkbox" class="styledCheckbox" name="emailUser" checked="">
			<label for="emailUser" style="vertical-align: super;"><?php echo $tr['email_member_credentials'] ?></label>
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

<div class="col-md-4 well">
	<h4 class="page-title text-center"><?php echo $tr['members_of_team'] ?></h4>
	<br>
	<div class="table-responsive">
		<table class="table table-hover mails m-0 table table-actions-bar">
			<tbody>
				<?php foreach ($teamUsers as $user){
					?>
					<tr>
						<td><?php echo $user['name'] ?></td>
						<td>
							<a href="#" class="table-action-btn edit" data-id="<?php echo $user['user_id'] ?>" data-name="<?php echo $user['name'] ?>" data-username="<?php echo $user['username'] ?>" data-email="<?php echo $user['email'] ?>"><span class="text-success"><i class="md md-edit"></i></span></a>
							<a href="#" class="table-action-btn delete" data-id="<?php echo $user['user_id'] ?>" data-name="<?php echo $user['name'] ?>"><span class="text-danger"><i class="md md-close"></i></span></a>
						</td>
					</tr>
					<?php
				} ?>
				<?php if(empty($teamUsers)): ?>
					<tr><td colspan="2" class="text-center"><em><?php echo $tr['no_members'] ?></em></td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#genBtn').on('click', function(e){
			e.preventDefault();
			$('#password').val(generateRandomString(generateRandomNumber(<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>), true, false, true, false));
			return false;
		});

		$('button[type="reset"]').on('click', function (e) {
			$('button[type="submit"]').text("<?php echo $tr['save'] ?>");
			$('#emailUser').prop("checked", true );
			$('#case').val('<?php echo $postAddUser ?>');
			$('#username').removeAttr('readonly');
		});

		$('body').on('click', '.edit', function(e){
			e.preventDefault();
			id = $(this).data('id');

			if(id == "" || id == undefined)
				generateNotification('invalid user...', 'bottom-right', 'error', 5000, true);
			else{
				var form = $("#team-user-form");
				form.find('input[name="user_id"]').val(id);
				form.find('input[name="name"]').val($(this).data('name'));
				form.find('input[name="username"]').val($(this).data('username'));
				form.find('input[name="email"]').val($(this).data('email'));
				form.find('button[type="submit"]').text("<?php echo $tr['update'] ?>");
				form.find("input[name='emailUser']").prop("checked", false );

				$('#password').prop("required", false );
				$('#case').val('<?php echo  $postEditUser ?>');
				$('#username').prop("readonly", true );
			}
		});

		$('body').on('click', '.delete', function(e){
			e.preventDefault();
			id = $(this).data('id');

			if(id != "" && id != undefined){
				swal({
					title: "<?php echo $tr['are_you_sure'] ?>",
					text: "<?php echo $tr['not_retrive_user'] ?>" + $(this).data('name') + "!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#81c868",
					confirmButtonText: "<?php echo $tr['yes_delete'] ?>",
					cancelButtonText: "<?php echo $tr['cancel'] ?>",
					closeOnConfirm: false
				}, function(){
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
						'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-settings', 20, 'settings'); ?>">'
						+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-delete-user', 20, 'delete-user'); ?>">'
						+ '<input type="hidden" name="id" value="' + id + '">');
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
				});
			}else
				generateNotification('invalid user...', 'bottom-right', 'error', 5000, true);
		});
	});
</script>
