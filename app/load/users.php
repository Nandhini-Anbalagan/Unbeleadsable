<?php
	if(file_exists("../head.php")){
		include("../head.php");
		$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
	}

	$users = $db->getUsers();
	$totalUsers = 0;

	# Tokenizer container
	$postActionUser = Tokenizer::add('post-action-user', 20, 'user');
	$postCaseUserDelete = Tokenizer::add('post-case-user-delete', 30, 'delete');
	$postCaseUserSingle = Tokenizer::add('post-case-user-single', 30, 'single');
?>
<div class="m-b-30">
	<h2 class="page-title pull-left">Users</h2>
	<button class="btn btn-primary waves-effect waves-light pull-right m-l-10" data-toggle="modal" data-target="#add-modal">New User <i class="fa fa-plus"></i></button>
	<button class="btn btn-success waves-effect waves-light pull-right" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $_SESSION['user']['user_id'] ?>" title="Edit My Profile">Edit My Profile <i class="fa fa-user"></i></button>
</div>

<div class="p-20">
	<table class="table table-striped table-responsive m-0" id="datatable-editable">
		<thead>
			<tr>
				<th width="25%">Name</th>
				<th width="15%">Username</th>
				<th width="20%">Email</th>
				<th width="10%">Level</th>
				<th width="20%">Last Login</th>
				<th width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($users as $value) {
					//TODO
					//if($value['user_id'] != $_SESSION['user']['user_id'] && $_SESSION['user']['level'] < $value['level']){
					if($value['level'] != 100 AND $value['level'] != 10){
						$totalUsers++;
			?>
						<tr>
							<td><?php echo $value['name']?></td>
							<td><?php echo $value['username'] ?></td>
							<td><?php echo $value['email'] ?></td>
							<td><span class="label label-table label-inverse"><?php echo ucfirst(User::getUserLevel($value['level'])); ?></span></td>
							<td><?php echo Functions::userFriendlyDate($value['last_login']); ?></td>
							<td class="actions">
								<?php
									if($value['level'] == 10)
										$userLink = '?mockUser=' . $value['user_id'];
									else
										$userLink = 'javascript:void(0)';
								?>
								<a href="#" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $value['user_id'] ?>" title="Edit User" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
								<a href="#" data-id="<?php echo $value['user_id'] ?>" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
			<?php
					}
				}
				if($totalUsers == 0)
					echo "<tr><td colspan='6' class='text-center'><i>oupps, no user found. Are you sure you have any?</i></td></tr>"
			?>
		</tbody>
	</table>
</div>
<p class="text-muted font-13">Total of <span class="users-count"><?php echo $totalUsers; ?> User<?php echo $totalUsers > 1 ? 's' : '' ?></span></p>

<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Add New User</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-user', 20, 'user'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-user-add', 30, 'add'); ?>">

						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="username" class="col-sm-4 control-label">Username</label>
							<div class="col-sm-7">
								<input type="text" id="username" name="username" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" placeholder="Username">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label">Email</label>
							<div class="col-sm-7">
								<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-4 control-label">Password</label>
							<div class="col-sm-6">
								<input type="text" id="password" name="password" data-parsley-length="[<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>]" placeholder="Password" required class="form-control">
							</div>
							<div class="col-sm-1 p-t-10">
								<button class="btn btn-info btn-xs" title="click to generate password" id="genBtn"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
						<div class="form-group" id="countryDiv">
							<label class="col-sm-4 control-label" for="country">Country</label>
							<div class="col-sm-7">
								<select class="form-control" name="country" id="country">
									<option value="CA">Canada</option>
									<option value="US">United States</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="level">User Level</label>
							<div class="col-sm-7">
								<select class="form-control" name="level" id="level">
									<option value="<?php echo User::LEVEL_ADMIN; ?>">Administrator</option>
									<option value="<?php echo User::LEVEL_AGENT; ?>">Agent</option>
									<option value="<?php echo User::LEVEL_MODERATOR; ?>">Moderator</option>
									<option value="<?php echo User::LEVEL_USER; ?>">Standard</option>
								</select>
							</div>
						</div>

						<div class="form-group ">
							<div class="col-sm-7 col-sm-offset-4">
								<input id="emailUser" type="checkbox" class="styledCheckbox" name="emailUser">
								<label for="emailUser" style="vertical-align: super;">Email User his credentials</label>
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

<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Edit User</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-user', 20, 'user'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-user-edit', 30, 'edit'); ?>">
						<input type="hidden" name="user_id" value="">
						<input type="hidden" name="level" value="">


						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="username" class="col-sm-4 control-label">Username</label>
							<div class="col-sm-7">
								<input type="text" id="username" name="username" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" placeholder="Username">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label">Email</label>
							<div class="col-sm-7">
								<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
							</div>
						</div>
						<div class="form-group" id="passDiv">
							<label for="pass" class="col-sm-4 control-label">Password</label>
							<div class="col-sm-7">
								<input type="password" id="pass" name="pass" class="form-control" data-parsley-length="[5,10]">
							</div>
						</div>
						<div class="form-group" id="countryDiv">
							<label class="col-sm-4 control-label" for="country">Country</label>
							<div class="col-sm-7">
								<select class="form-control" name="country" id="country">
									<option value="CA">Canada</option>
									<option value="US">United States</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="lvlDiv">
							<label class="col-sm-4 control-label" for="level">User Level</label>
							<div class="col-sm-7">
								<select class="form-control" name="level" id="level">
									<option value="<?php echo User::LEVEL_ADMIN; ?>">Administrator</option>
									<option value="<?php echo User::LEVEL_AGENT; ?>">Agent</option>
									<option value="<?php echo User::LEVEL_MODERATOR; ?>">Moderator</option>
									<option value="<?php echo User::LEVEL_USER; ?>">Standard</option>
								</select>
							</div>
						</div>

						<div class="form-group ">
							<div class="col-sm-7 col-sm-offset-4">
								<input id="deleteUser" type="checkbox" class="styledCheckbox" name="deleteUser">
								<label for="deleteUser" style="vertical-align: super;">Delete User</label>
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
		$('#genBtn').on('click', function(e){
			e.preventDefault();
			$('#password').val(generateRandomString(generateRandomNumber(<?php echo User::MIN_PASSWORD_LENGTH . "," . User::MAX_PASSWORD_LENGTH ?>), true, false, true, false));
			return false;
		});

		$('#edit-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionUser; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseUserSingle; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('a.remove-row').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this user!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: false
			}, function(){
				$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionUser; ?>">'
					+ '<input type="hidden" name="case" value="<?php echo $postCaseUserDelete; ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$('#<?php echo $dynamicFormId; ?>').submit();
				$('#<?php echo $dynamicFormId; ?>').empty();
			});
		});

		$('select').select2();
	});
</script>

