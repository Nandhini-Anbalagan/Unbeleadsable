<?php 
if(file_exists('../head.php'))
	require_once('../head.php');

	$archieved = $db->getAgentArchievedLead($_SESSION['user']['agent_id'], $_SESSION['user']['agent_slug']);

?>

<div class="row">
	<div class="col-md-12">
		<table id="archieve_datatable" class="table table-striped table-responsive table-bordered">
			<thead>
				<tr>
					<th width="10px" class="no-sort"><input id="selectAll" data-toggle="tooltip" data-placement="right" data-original-title="Select All" type="checkbox" class="styledCheckbox"></th>
					<th><?php echo $tr['name_contact'] ?></th>
					<?php if($_SESSION['user']['agent_slug'] == "home_sellers"): ?>
					<th class="no-sort"><?php echo $tr['address'] ?></th>
					<?php endif ?>
					<th class="no-sort"><?php echo $tr['notes'] ?></th>
					<th><?php echo $tr['source'] ?></th>
					<th><?php echo $tr['date'] ?></th>
					<th class="text-center no-sort">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($archieved as $l) { 
					if($_SESSION['user']['agent_slug'] == "home_sellers"):
						$address = explode(",", $l['address']);
						$street = $address[0];
						array_shift($address);
						
						$noApt = explode(" ", $l['address']);

						if(strpos($noApt[0], "#") === 0)
							array_shift($noApt);

						$add = implode(" ", $noApt);

						
						$a = explode(",", $add);
						$s = $a[0];
						$c = count($a)>0?$a[count($a) - 1]:'';
						$z = count($a)>1?$a[count($a) - 2]:'';
					endif;

				?>
					<tr>
						<td style="padding: 8px">
							<input type="checkbox" class="styledCheckbox" value="<?php echo $l['id'] ?>">
						</td>
						<td style="position: relative" data-name="<?php echo $l['name'] ?>" data-lang="<?php echo $l['lang'] ?>" data-email="<?php echo $l['email'] ?>"><span class="<?php echo $l['lang'] == 'e'?'en':'fr' ?>"></span><a href="lead/<?php echo IDObfuscator::encode($l['id']) ?>"><span style="color: #E51937;font-weight:bold"><?php echo $l['name'] . "</span><br>" . $l['phone'] . "<br><span class='email'>" .  $l['email'] ."</span>" ?></a></td>
						
						<?php if($_SESSION['user']['agent_slug'] == "home_sellers"): ?>
						<td><?php echo $street . "<br>" . implode(",", $address) ?></td>
						<?php endif ?>
						<td>
							<form>
								<textarea class="form-control" name="comments" data-id="<?php echo $l['id'] ?>"><?php echo $l['comments'] ?></textarea>
							</form>
						</td>
						<td><?php echo Functions::getSource($l['source']) ?></td>
						<td  data-order="<?php echo date_format(date_create($l['date']), 'Ymd') ?>"><?php echo date_format(date_create($l['date']), 'F jS Y') . "<br>" . date_format(date_create($l['date']), 'h:i A') ?></td>
						<td class="text-center">
							<a data-id="<?php echo $l['id'] ?>" title="Restore" class="on-default restore"><i class="fa fa-refresh"></i></a>
							<a data-id="<?php echo $l['id'] ?>" title="Delete Forever" class="on-default delete text-danger"><i class="fa fa-times"></i></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

		</div>
	</div>

<script>
	$(document).ready(function(){
		$('#archieve_datatable').DataTable({
			<?php if($agent['agent_lang'] == "FR"){ ?>
			"language": {
				"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
			},
			<?php } ?>
			"order": [],
			columnDefs: [ { orderable: false, targets: [0,1,2,3,4]}],
			"bStateSave": true,
		});

		$('body').on('change', '#selectAll', function(e){
			var table= $(e.target).closest('table');
			$('input:checkbox',table).prop('checked',this.checked);
		});

		$('body').on('click','.restore', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({
				title: "Are you sure?",
				text: "You want to recovre this lead?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, recover it!",
				closeOnConfirm: false
			}, function(){
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
					'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
					+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-recover', 20, 'recover'); ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
			});
		});

		$('body').on('click','.delete', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			swal({
				title: "Are you sure?",
				text: "You want to delete this lead forver?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: false
			}, function(){
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
					'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
					+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-delete-forever', 20, 'delete-forever'); ?>">'
					+ '<input type="hidden" name="id" value="' + id + '">');
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
				$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
			});
		});


		$('body').on('click', '#delete', function(e){
			var ids = "";
			$('input:checkbox',"#archieve_datatable").each(function () {
				if(this.checked && $(this).val() != "on")
					ids += $(this).val() + ",";
			});
			ids = ids.substring(0, ids.length - 1);

			if(ids != ""){
				swal({
					title: "Are you sure?",
					text: "You will not be able to recover these leads!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, delete them!",
					closeOnConfirm: false
				}, function(){
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
						'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
						+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-delete-forever-bulk', 20, 'delete-forever-bulk'); ?>">'
						+ '<input type="hidden" name="ids" value="' + ids + '">');
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
				});
			}else
				generateNotification('Please select at least one lead.', 'bottom-right', 'error', 5000, true);
		});

		$('body').on('click', '#recover', function(e){
			var ids = "";
			$('input:checkbox',"#archieve_datatable").each(function () {
				if(this.checked && $(this).val() != "on")
					ids += $(this).val() + ",";
			});
			ids = ids.substring(0, ids.length - 1);

			if(ids != ""){
				swal({
					title: "Are you sure?",
					text: "You want to restore these leads?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, retore them!",
					closeOnConfirm: false
				}, function(){
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
						'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
						+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-recover-bulk', 20, 'recover-bulk'); ?>">'
						+ '<input type="hidden" name="ids" value="' + ids + '">');
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
				});
			}else
				generateNotification('Please select at least one lead.', 'bottom-right', 'error', 5000, true);
		});
	});
</script>