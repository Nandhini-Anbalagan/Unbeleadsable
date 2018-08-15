<?php
if(file_exists("../head.php")){
	include("../head.php");
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}

if($_SESSION['user']['level'] >= 50)
	$agents = $db->getAgents();
else
	$agents = $db->getAgentsByCountry($_SESSION['user']['user_country']);
$totalAgents = count($agents);


	# Tokenizer container
	$postActionAgent = Tokenizer::add('post-action-agent', 20, 'agent');
	$postCaseAgentDelete = Tokenizer::add('post-case-agent-delete', 30, 'delete');
	$postCaseAgentLock = Tokenizer::add('post-case-agent-lock', 30, 'lock');
	$postCaseAgentUnlock = Tokenizer::add('post-case-agent-unlock', 30, 'unlock');
	$postCaseAgentSingleView = Tokenizer::add('post-case-agent-singleView', 30, 'singleView');
	$postCaseAgentSingleEdit = Tokenizer::add('post-case-agent-singleEdit', 30, 'singleEdit');
	$postCaseAgentSingleUser = Tokenizer::add('post-case-agent-singleUser', 30, 'singleUser');

?>
<div class="m-b-30">
	<h2 class="page-title pull-left">Customers <span class="text-muted font-13">(Total of <span class="users-count"><?php echo $totalAgents; ?> agent<?php echo $totalAgents > 1 ? 's' : '' ?></span>)</span></h2>
	<?php if($_SESSION['user']["level"] > 20){ ?>
	<a href="#" class="btn btn-danger waves-effect waves-light m-l-5 pull-right" data-toggle="modal" data-target="#allEmaillModal"><i class="fa fa-envelope"></i>&nbsp;Email All Customers</a>
	<div class="btn-group pull-right m-l-5">
	<button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Filter Status<span class="m-l-5"><i class="fa fa-filter"></i></span></button>
		<ul class="dropdown-menu" role="menu">
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-success"><i class="fa fa-check"></i></span>Active</a></li>
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-warning"><i class="fa fa-ban"></i></span>Error</a></li>
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-danger"><i class="fa fa-times"></i></span>Innactive</a></li>
			<li><a class="filter troll" href="#" data-status="1"><b><span class="m-r-5 text-inverse"><i class="fa fa-signal"></i></span>All</b></a></li>
		</ul>
	</div>
	<?php } ?>
	<div class="btn-group pull-right m-l-5">
	<button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Filter Type<span class="m-l-5"><i class="fa fa-filter"></i></span></button>
		<ul class="dropdown-menu" role="menu">
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-success"><i class="fa fa-usd"></i></span>Subscriber</a></li>
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-primary"><i class="fa fa-shopping-cart"></i></span>Sponsor</a></li>
			<li><a class="filter troll" href="#" data-status="1"><b><span class="m-r-5 text-inverse"><i class="fa fa-signal"></i></span>All</b></a></li>
		</ul>
	</div>
</div>

<div class="clearfix"></div>
<div class="p-t-20">
<?php include "buyerSellerBullets.php" ?>
</div>
<div class="p-20">
	<table class="table table-striped table-responsive m-0" id="datatable-editable">
		<thead>
			<tr>
				<th width="15%">Name</th>
				<th width="20%">Email</th>
				<th width="10%">Areas</th>
				<th width="15%">Company</th>
				<th width="15%">Comments</th>
				<th width="5%" class="text-center">Ref</th>
				<th width="15%" class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($agents as $value) {
				$bg = "";
					$bull = "s";

				if($value['agent_status'] == 2)
					$bg = 'background-color: rgba(255, 189, 74, 0.5)!important';

				if($value['status'] == 2 || $value['agent_status'] == 3)
					$bg = 'background-color: rgba(229, 25, 55, 0.3)!important';

				if ($value['agent_slug'] == "home_buyers")
					$bull = 'b';
				else if ($value['agent_slug'] == "home_sellers")
					$bull = 's';
				?>
				<tr style="<?php echo $bg ?>">
					<td name="name">
						<span class="<?php echo $value['agent_lang'] == 'EN'?'en':'fr' ?>"></span>
						<img style="width: 20px;" src="assets/img/button_<?php echo $bull ?>.png" alt="buyer">
						<?php echo $value['agent_name'] . " (" . $value['agent_id'] . "|" . $value['user_id'] . ")" ?>
					</td>
					<td name="email"><?php echo $value['agent_email'] ?></td>
					<td name="areas"><?php echo $value['assigned_area'] ?></td>
					<td name="agency"><?php echo $value['agent_agency'] ?></td>
					<td name="comments"><?php echo strlen($value['agent_comments']) > 50 ? substr($value['agent_comments'],0,50)."..." : $value['agent_comments']; ?></td>
					<td name="lang"><?php echo $value['agent_ref'] ?></td>
					<td class="actions text-center">
						<a title="View Lead" class="viewAgent" data-toggle="modal" data-target="#view-modal" data-id="<?php echo $value['agent_id'] ?>"><i class="fa fa-eye"></i></a>
						<?php if($_SESSION['user']["level"] > 20){ ?>
						<a href="?mockUser=<?php echo IDObfuscator::encode($value['agent_id']) ?>" title="View Agent's Account" class="on-default edit-row"><i class="fa fa-globe"></i></a>
						<a title="Send Email to agent" class="on-default sendEmail"><i class="fa fa-envelope"></i></a>
						<a data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $value['agent_id'] ?>" title="Edit agent" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

						<?php if($value['agent_status'] == 3): ?>
							<a data-name="<?php echo $value['agent_name'] ?>" data-agent="<?php echo $value['agent_id'] ?>" data-user="<?php echo $value['user_id'] ?>" title="UnLock agent" class="on-default unlock-row"><i class="fa fa-lock"></i></a>
						<?php else: ?>
							<a data-name="<?php echo $value['agent_name'] ?>" data-agent="<?php echo $value['agent_id'] ?>" data-user="<?php echo $value['user_id'] ?>" title="Lock agent" class="on-default text-warning lock-row"><i class="fa fa-unlock"></i></a>
						<?php endif ?>
						<a data-id="<?php echo $value['user_id'] ?>" data-toggle="modal" data-target="#user-modal" title="Password" class="on-default "><i class="fa fa-key"></i></a>

						<a data-id="<?php echo $value['agent_id'] ?>" title="Delete agent" class="on-default remove-row"><i class="fa fa-times"></i></a>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-purple">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center">View Agent</h2>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-user-information dataTable">
									<tbody>

										<tr>
											<th>Name</th>
											<td target="name"></td>
										</tr>
										<tr>
											<th>Email</th>
											<td target="email"></td>
										</tr>
										<tr>
											<th>Phone</th>
											<td target="phone"></td>
										</tr>
										<tr>
											<th>Address</th>
											<td target="address"></td>
										</tr>
										<tr>
											<th>Areas</th>
											<td target="areas"></td>
										</tr>
										<tr>
											<th>Agency</th>
											<td target="agency"></td>
										</tr>
										<tr>
											<th>License #</th>
											<td target="license"></td>
										</tr>
										<tr>
											<th>Board</th>
											<td target="board"></td>
										</tr>
										<tr>
											<th>Reference</th>
											<td target="ref"></td>
										</tr>
										<tr>
											<th>Campaign #</th>
											<td target="camp"></td>
										</tr>
										<tr>
											<th>Language</th>
											<td target="language"></td>
										</tr>
										<tr>
											<th>Comments</th>
											<td target="comments"></td>
										</tr>
										<tr>
											<th width="140px">Date Created</th>
											<td target="date"></td>
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

	<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-primary">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center">Edit Agent</h2>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
							<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">
							<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agent-edit', 30, 'edit'); ?>">
							<input type="hidden" name="id" value="">
							<input type="hidden" name="signature" value="">
							<input type="hidden" name="phone_notification" value="">
							<input type="hidden" name="email_notification" value="">
							<input type="hidden" name="avatar" value="">

							<div class="form-group">
								<label for="name" class="col-sm-4 control-label">Name</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="address" class="col-sm-4 control-label">Address</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="address" name="address" placeholder="Address">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-4 control-label">Email</label>
								<div class="col-sm-7">
									<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
								</div>
							</div>
							<div class="form-group">
								<label for="phone" class="col-sm-4 control-label">Phone</label>
								<div class="col-sm-7">
									<input type="text" id="phone" name="phone" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="areas" class="col-sm-4 control-label">Areas</label>
								<div class="col-sm-7">
									<input type="text" id="areas" name="areas" class="form-control" required="" placeholder="Areas">
								</div>
							</div>
							<div class="form-group">
								<label for="agency" class="col-sm-4 control-label">Agency</label>
								<div class="col-sm-7">
									<input type="text" id="agency" name="agency" class="form-control" required="" placeholder="Agency">
								</div>
							</div>
							<div class="form-group">
								<label for="license" class="col-sm-4 control-label">License #</label>
								<div class="col-sm-7">
									<input type="text" id="license" name="license" class="form-control" placeholder="License number">
								</div>
							</div>
							<div class="form-group">
								<label for="board" class="col-sm-4 control-label">Board</label>
								<div class="col-sm-7">
									<input type="text" id="board" name="board" class="form-control" placeholder="Board">
								</div>
							</div>
							<div class="form-group">
								<label for="ref" class="col-sm-4 control-label">Reference</label>
								<div class="col-sm-7">
									<input type="text" id="ref" name="ref" class="form-control" placeholder="Reference">
								</div>
							</div>
							<div class="form-group">
								<label for="camp" class="col-sm-4 control-label">Campaign #</label>
								<div class="col-sm-7">
									<input type="text" id="camp" name="camp" class="form-control" placeholder="Campaign #">
								</div>
							</div>
							<div class="form-group">
								<label for="areas" class="col-sm-4 control-label">Comments</label>
								<div class="col-sm-7">
									<textarea id="comments" name="comments" class="form-control" placeholder="Write message here"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Language</label>
								<div class="col-sm-7">
									<select class="form-control" name="lang">
										<option value="EN">English</option>
										<option value="FR">French</option>
									</select>
								</div>
							</div>

							<div class="form-group ">
								<div class="col-sm-7 col-sm-offset-4">
									<input id="deleteAgent" type="checkbox" class="styledCheckbox" name="deleteAgent">
									<label for="deleteUser" style="vertical-align: super;">Delete Agent</label>
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

		<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-primary">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center">Edit Login Credentials</h2>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
							<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">
							<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agent-user', 30, 'user'); ?>">
							<input type="hidden" name="user_id" value="">


							<div class="form-group">
								<label for="email" class="col-sm-4 control-label">Email</label>
								<div class="col-sm-7">
									<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
								</div>
							</div>
							<div class="form-group">
								<label for="username" class="col-sm-4 control-label">Username</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="username" name="username" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-sm-4 control-label">Password</label>
								<div class="col-sm-7">
									<input type="text" id="password" name="password" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_USERNAME_LENGTH . "," . User::MAX_USERNAME_LENGTH ?>]" placeholder="Password">
									<small id="currentPassword"></small>
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
		$("#datatable-editable").DataTable({
			language: {
				emptyTable: "Oopps, no user found, are you sure you have any?"
			},
			order: [],
			<?php if($_SESSION['user']["level"] > 20){ ?>
			columnDefs: [
				{ "orderable": false, "targets": [1,2,3,4,6] }
			],
			<?php } ?>
			fixedHeader: true,
			autoWidth: false,
			responsive: true,
			"bStateSave": true
		});

		$('#view-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentSingleView; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#edit-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentSingleEdit; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#user-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentSingleUser; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('a.remove-row').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this agent!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: false
			}, function(){
					$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
						+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentDelete; ?>">'
						+ '<input type="hidden" name="id" value="' + id + '">');
					$('#<?php echo $dynamicFormId; ?>').submit();
					$('#<?php echo $dynamicFormId; ?>').empty();
				});
			});

			$('body').on('click', 'a.lock-row', function(e){
				e.preventDefault();
				var agent = $(this).data('agent'), user = $(this).data('user'), name = $(this).data('name');

				swal({
					title: "Are you sure?",
					text: "The agent's acount will be disabled until re-enabled",
					type: "error",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, disable it!",
					closeOnConfirm: false
				}, function(){
					$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
						+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentLock; ?>">'
						+ '<input type="hidden" name="agent_id" value="' + agent + '">'
						+ '<input type="hidden" name="user_id" value="' + user + '">'
						+ '<input type="hidden" name="name" value="' + name + '">');
					$('#<?php echo $dynamicFormId; ?>').submit();
					$('#<?php echo $dynamicFormId; ?>').empty();
				});
			});

			$('body').on('click', 'a.unlock-row', function(e){
				e.preventDefault();
				var agent = $(this).data('agent'), user = $(this).data('user'), name = $(this).data('name');
				swal({
					title: "Are you sure?",
					text: "The agent's acount will be re-enabled",
					type: "success",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, re-enable it!",
					closeOnConfirm: false
				}, function(){
					$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgent; ?>">'
						+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentUnlock; ?>">'
						+ '<input type="hidden" name="agent_id" value="' + agent + '">'
						+ '<input type="hidden" name="user_id" value="' + user + '">'
						+ '<input type="hidden" name="name" value="' + name + '">');
					$('#<?php echo $dynamicFormId; ?>').submit();
					$('#<?php echo $dynamicFormId; ?>').empty();
				});
			});

		$('select').select2();
		});
	</script>
