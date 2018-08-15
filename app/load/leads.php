<?php
if(file_exists("../head.php")){
	include("../head.php");
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}
if($_SESSION['user']['level'] >= 50)
	$leads = $db->getAgentLeads();
else
	$leads = $db->getAgentLeadsByCountry($_SESSION['user']['user_country']);
$totalLeads = count($leads);

# Tokenizer container
$postActionLead = Tokenizer::add('post-action-lead', 20, 'lead');
$postActionCalls = Tokenizer::add('post-action-calls', 20, 'calls');
$postCaseCallsConvert = Tokenizer::add('post-case-calls-convert', 30, 'convert');
$postCaseLeadDelete = Tokenizer::add('post-case-lead-delete', 30, 'delete');
$postCaseLeadConvert = Tokenizer::add('post-case-lead-convert', 30, 'convert');
$postCaseLeadSingleView = Tokenizer::add('post-case-lead-singleView', 30, 'singleView');
$postCaseLeadSingleEdit = Tokenizer::add('post-case-lead-singleEdit', 30, 'singleEdit');
$postCaseLeadStatus = Tokenizer::add('post-case-lead-status', 30, 'status');

include "convert-to-lead-modal.php";

echo "<script>console.log('".IDObfuscator::encode(237)."')</script>";

?>
<div class="m-b-30">
	<h2 class="page-title pull-left">New Customer Leads <span class="text-muted font-13">(Total of <span class="users-count"><?php echo $totalLeads; ?> Lead<?php echo $totalLeads > 1 ? 's' : '' ?></span>)</span></h2>
	<button class="btn btn-danger waves-effect waves-light pull-right m-l-5" data-toggle="modal" data-target="#convert-lead-modal">New lead <i class="fa fa-user-plus"></i></button>
	<div class="btn-group pull-right">
	<button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Filter Type<span class="m-l-5"><i class="fa fa-filter"></i></span></button>
		<ul class="dropdown-menu" role="menu">
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-success"><i class="fa fa-usd"></i></span>Subscriber</a></li>
			<li><a class="filter troll" href="#" data-status="1"><span class="m-r-5 text-primary"><i class="fa fa-shopping-cart"></i></span>Sponsor</a></li>
			<li><a class="filter troll" href="#" data-status="1"><b><span class="m-r-5 text-inverse"><i class="fa fa-signal"></i></span>Both</b></a></li>
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
				<th width="10%">Date</th>
				<th width="15%">Name</th>
				<th width="10%">Email</th>
				<th width="10%">Phone</th>
				<th width="10%">Areas</th>
				<th width="10%">Company</th>
				<th width="15%">Comments</th>
				<th width="5%" class="text-center">Ref</th>
				<th width="15%" class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($leads as $value) {
				$bg = "";

					if($value['user_id'] != 0)
						$bg = 'background-color: rgba(95, 190, 170, 0.25)!important';

					if ($value['lead_type'] == "home_buyers")
						$bull = 'b';
					else if ($value['lead_type'] == "home_sellers")
						$bull = 's';
					else
						$bull = 's';

			?><tr style="<?php echo $bg ?>">
					<td name="lead_date"><?php echo $value['lead_date'] ?></td>
					<td name="name"><span class="<?php echo $value['lead_lang'] == 'EN'?'en':'fr' ?>"></span><img style="width: 20px;" src="assets/img/button_<?php echo $bull ?>.png" alt="buyer"><?php echo $value['lead_name'] ?></td>
					<td name="email"><?php echo $value['lead_email'] ?></td>
					<td name="email"><?php echo $value['lead_phone'] ?></td>
					<td name="areas"><?php echo $value['lead_areas'] ?></td>
					<td name="agency"><?php echo $value['lead_agency'] ?></td>
					<td name="comments"><textarea data-id="<?php echo $value['lead_id'] ?>" name="comments" class="comments"><?php echo $value['lead_comments'] ?></textarea></td>
					<td name="lang" class="text-center"><?php echo $value['lead_ref'] ?></td>
					<td class="actions text-center">
						<a href="#" title="View Lead" class="viewLead" data-toggle="modal" data-target="#view-modal" data-id="<?php echo $value['lead_id'] ?>"><i class="fa fa-eye"></i></a>
						<?php if($_SESSION['user']["level"] > 50){ ?>
						<a href="#" data-id="<?php echo $value['lead_id'] ?>" title="Auto Convert Lead" class="text-success auto-convert"><i class="fa fa-check"></i></a>
						<?php } ?>
						<a href="#" title="Send Email to Lead" class="sendEmail"><i class="fa fa-envelope"></i></a>
						<a href="#" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $value['lead_id'] ?>" title="Edit Lead" class="edit-row"><i class="fa fa-pencil"></i></a>
						<?php if($_SESSION['user']["level"] > 20){ ?>
						<a href="#" data-id="<?php echo $value['lead_id'] ?>" title="Delete Lead" class="remove-row"><i class="fa fa-times"></i></a>
						<?php } ?>
					</td>
				</tr>
				<?php
			}
			if($totalLeads == 0)
				echo "<tr><td colspan='6' class='text-center'><i>oupps, no user found. Are you sure you have any?</i></td></tr>"
			?>
		</tbody>
	</table>
</div>


<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-purple">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">View Lead</h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-user-information">
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
										<th>Reference Code</th>
										<td target="ref"></td>
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
					<h2 class="panel-title text-center">Edit Lead Agent</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-lead', 20, 'lead'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-lead-edit', 30, 'edit'); ?>">
						<input type="hidden" name="id" value="">

						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="email"class="col-sm-4 control-label">Email</label>
							<div class="col-sm-7">
								<input type="email" id="email" name="email" class="form-control" required="" parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
							</div>
						</div>
						<div class="form-group">
							<label for="phone"class="col-sm-4 control-label">Phone</label>
							<div class="col-sm-7">
								<input type="text" id="phone" name="phone" class="form-control" required="" placeholder="Phone">
							</div>
						</div>
						<div class="form-group">
							<label for="areas"class="col-sm-4 control-label">Areas</label>
							<div class="col-sm-7">
								<input type="text" id="areas" name="areas" class="form-control" required="" placeholder="Areas">
							</div>
						</div>
						<div class="form-group">
							<label for="agency"class="col-sm-4 control-label">Agency</label>
							<div class="col-sm-7">
								<input type="text" id="agency" name="agency" class="form-control" placeholder="Agency">
							</div>
						</div>
						<div class="form-group">
							<label for="license"class="col-sm-4 control-label">License #</label>
							<div class="col-sm-7">
								<input type="text" id="license" name="license" class="form-control" placeholder="License number">
							</div>
						</div>
						<div class="form-group">
							<label for="board"class="col-sm-4 control-label">Board</label>
							<div class="col-sm-7">
								<input type="text" id="board" name="board" class="form-control" placeholder="Board">
							</div>
						</div>
						 <div class="form-group">
							<label for="ref"class="col-sm-4 control-label">Reference Code</label>
							<div class="col-sm-7">
								<input type="text" id="ref" name="ref" class="form-control" placeholder="Reference Code">
							</div>
						</div>
						<div class="form-group">
							<label for="comments"class="col-sm-4 control-label">Comments</label>
							<div class="col-sm-7">
								<textarea id="comments" name="comments" class="form-control" placeholder="Type comments here"></textarea>
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
								<input id="deleteLead" type="checkbox" class="styledCheckbox" name="deleteLead">
								<label for="deleteLead" style="vertical-align: super;">Delete Lead</label>
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
		columnDefs: [
			{ "orderable": false, "targets": [3,5,6,7,8] }
		],
		fixedHeader: true,
		autoWidth: false,
		responsive: true,
		"bStateSave": true
	});

	$('#status li').on('click', function(e){
		e.preventDefault();

		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLead; ?>">'
			+ '<input type="hidden" name="case" value="<?php echo $postCaseLeadStatus; ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
			+ '<input type="hidden" name="lead" value="' + $(this).data('lead') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});


	$('#view-modal').on('shown.bs.modal', function(e) {
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLead; ?>">'
			+ '<input type="hidden" name="case" value="<?php echo $postCaseLeadSingleView; ?>">'
			+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('#edit-modal').on('show.bs.modal', function(e) {
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLead; ?>">'
			+ '<input type="hidden" name="case" value="<?php echo $postCaseLeadSingleEdit; ?>">'
			+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('a.remove-row').on('click', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this Lead!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: false
		}, function(){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLead; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseLeadDelete; ?>">'
				+ '<input type="hidden" name="id" value="' + id + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});

	$('a.auto-convert').on('click', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		swal({
			title: "Are you sure?",
			text: "This action connot be undone. And the initial payments will be bypassed!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, convert it!",
			closeOnConfirm: false
		}, function(){
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLead; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseLeadConvert; ?>">'
				+ '<input type="hidden" name="id" value="' + id + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});

	$('body').on('blur', 'textarea[name="comments"]',function() {
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-lead', 20, 'lead'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-lead-comments', 20, 'comments'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
			+ '<input type="hidden" name="comments" value="' + $(this).val() + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('select').select2();
});
</script>
