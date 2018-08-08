<?php
if(file_exists("../head.php")){
	include("../head.php");
	$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
}
if($_SESSION['user']['level'] >= 50)
	$callRecords = $db->getCalls();
else
	$callRecords = $db->getCallsByCountry($_SESSION['user']['user_country']);


# Tokenizer container
$postActionCalls = Tokenizer::add('post-action-calls', 20, 'calls');
$postCaseCallsDelete = Tokenizer::add('post-case-calls-delete', 30, 'delete');
$postCaseCallsSingle = Tokenizer::add('post-case-calls-single', 30, 'single');
$postCaseCallsSingleConvert = Tokenizer::add('post-case-calls-single-convert', 30, 'single-convert');
$postCaseCallsAdd = Tokenizer::add('post-case-calls-add', 30, 'add');
$postCaseCallsConvert = Tokenizer::add('post-case-calls-convert', 30, 'convert');
$postCaseCallsEdit = Tokenizer::add('post-case-calls-edit', 30, 'edit');

include "convert-to-lead-modal.php";

?>

<div class="m-b-30">
	<h2 class="page-title pull-left">Call Center</h2>
	<button class="btn btn-danger waves-effect waves-light pull-right m-l-10" data-toggle="modal" data-target="#add-modal">New Call Record <i class="fa fa-plus"></i></button>
</div>

<div class="p-20">
	<table class="table table-striped table-responsive m-0" id="datatable">
		<thead>
		<tr>
			<th width="25%">Name</th>
			<th width="20%">Phone</th>
			<th width="10%">Source</th>
			<th width="15%">Desired Area</th>
			<th width="10%">Status</th>
			<th width="15%">Notes</th>
			<th width="5%" class="text-center">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($callRecords as $value) {
			?>
			<tr>
				<td><?php echo $value['call_name']; ?></td>
				<td><?php echo $value['call_phone']; ?></td>
				<td><?php echo $value['call_source']; ?></td>
				<td><?php echo $value['call_desired_area']; ?></td>
				<td><?php switch($value['call_state']){
					case "1":
						echo "Contacted";
						break;
					case "2":
						echo "Not interested";
						break;
					case "3":
						echo "Call back";
						break;
					case "4":
						echo "Not Contacted";
						break;
					case "5":
						echo "Wrong info";
						break;
					case "6":
						echo "Accepted";
						break;
					} ?></td>
				<td><?php echo nl2br($value['call_notes']); ?></td>
				<td class="actions text-center">
					<a href="#" data-toggle="modal" data-target="#convert-lead-modal" data-id="<?php echo $value['call_id'] ?>" title="Convert to lead" class="on-default edit-row"><i class="fa fa-user-plus"></i></a>
					<a href="#" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $value['call_id'] ?>" title="Edit Call Record" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
					<?php if($_SESSION['user']["level"] > 20){ ?>
					<a href="#" data-id="<?php echo $value['call_id'] ?>" class="on-default remove-row"><i class="fa fa-times"></i></a>
					<?php } ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</div>

<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Add New Record</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseCallsAdd; ?>">

						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-4 control-label">Phone Number</label>
							<div class="col-sm-7">
								<input type="text" id="phone" name="phone" class="form-control" required="" data-parsley-length="[7,15]" placeholder="Phone Number">
							</div>
						</div>
						<div class="form-group">
							<label for="source" class="col-sm-4 control-label">Source</label>
							<div class="col-sm-7">
								<input type="text" id="source" name="source" class="form-control" data-parsley-length="[0,50]" placeholder="Source">
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-4 control-label">Desired Area</label>
							<div class="col-sm-7">
								<input type="text" id="address" name="desired_area" class="form-control" required="" data-parsley-length="[2,50]" placeholder="Desired Area">
							</div>
						</div>
						<div class="form-group">
							<label for="notes" class="col-sm-4 control-label">Notes</label>
							<div class="col-sm-7">
								<textarea type="text" id="notes" name="notes" class="form-control" data-parsley-length="[0,100]" placeholder="Notes"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="status" class="col-sm-4 control-label">Status</label>
							<div class="col-sm-7">
								<select type="text" id="status" name="status" class="form-control">
									<option value="1">Contacted</option>
									<option value="2">Not interested</option>
									<option value="3">Call back</option>
									<option value="4">Not Contacted</option>
									<option value="5">Wrong info</option>
									<option value="6">Accepted</option>
								</select>
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
						<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseCallsEdit; ?>">
						<input type="hidden" name="id" value="">

						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" required="" data-parsley-length="[<?php echo User::MIN_NAME_LENGTH . "," . User::MAX_NAME_LENGTH ?>]" id="name" name="name" placeholder="Name">
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-4 control-label">Phone Number</label>
							<div class="col-sm-7">
								<input type="text" id="phone" name="phone" class="form-control" required="" data-parsley-length="[7,15]" placeholder="Phone Number">
							</div>
						</div>
						<div class="form-group">
							<label for="source" class="col-sm-4 control-label">Source</label>
							<div class="col-sm-7">
								<input type="text" id="source" name="source" class="form-control" data-parsley-length="[0,50]" placeholder="Source">
							</div>
						</div>
						<div class="form-group">
							<label for="desired_area" class="col-sm-4 control-label">Desired Area</label>
							<div class="col-sm-7">
								<input type="text" id="desired_area" name="desired_area" class="form-control" required="" data-parsley-length="[2,50]" placeholder="Desired Area">
							</div>
						</div>
						<div class="form-group">
							<label for="notes" class="col-sm-4 control-label">Notes</label>
							<div class="col-sm-7">
								<textarea type="text" id="notes" name="notes" class="form-control" data-parsley-length="[0,100]" placeholder="Notes"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="status" class="col-sm-4 control-label">Status</label>
							<div class="col-sm-7">
								<select type="text" id="status" name="status" class="form-control">
									<option value="1">Contacted</option>
									<option value="2">Not interested</option>
									<option value="3">Call back</option>
									<option value="4">Not Contacted</option>
									<option value="5">Wrong info</option>
									<option value="6">Accepted</option>
								</select>
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

        $('#datatable').DataTable({
            language: {
                emptyTable: "Oopps, no records found, are you sure you have any?"
            },
            order: [],
            columnDefs: [
                { "orderable": false, "targets": [-1] }
            ],
            fixedHeader: true,
            autoWidth: false,
            responsive: true
        });

		$('#convert-lead-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseCallsSingleConvert; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('#edit-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseCallsSingle; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('a.remove-row').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this record!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: false
			}, function(){
				$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">'
					+ '<input type="hidden" name="case" value="<?php echo $postCaseCallsDelete; ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$('#<?php echo $dynamicFormId; ?>').submit();
				$('#<?php echo $dynamicFormId; ?>').empty();
			});
		});

		$('select').select2();
	});
</script>
