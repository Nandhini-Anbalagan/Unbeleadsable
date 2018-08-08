<?php 
if(file_exists('../head.php'))
	require_once('../head.php');

if(isset($_GET['range']))
	$addresses = $db->filterDateAgentsLeadAddresses($_SESSION['user']['agent_id'], $_GET['range']);
else
	$addresses = $db->getAgentsLeadAddresses($_SESSION['user']['agent_id']);

?>

<div class="row">
	<div class="col-md-12">
		<div class="inline"><span class="bullet green"></span><?php echo $tr['completed_leads'] ?></div>
		<div class="inline"><span class="bullet blue"></span><?php echo $tr['partial_leads'] ?></div>
		<div class="inline"><span class="bullet red"></span><?php echo $tr['address'] ?></div>
		<table id="address_datatable" class="table table-striped table-responsive table-bordered">
			<thead>
				<tr>
					<th width="10px" class="no-sort"><input id="selectAll" data-toggle="tooltip" data-placement="right" data-original-title="Select All" type="checkbox" class="styledCheckbox"></th>
					<th class="text-center">Actions</th>
					<th class="no-sort"><?php echo $tr['address'] ?></th>
					<th class="text-center"><?php echo $tr['view'] ?></th>
					<th class="no-sort"><?php echo $tr['notes'] ?></th>
					<th><?php echo $tr['source'] ?></th>
					<th><?php echo $tr['date'] ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($addresses as $l) { 
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

					if($l['status'] == 1)
						$bull = 'green';
					else
						if($l['name'] == "" AND $l['phone'] == "" AND $l['email'] == "")
							$bull = 'red';
						else
							$bull = 'blue';
						
				?>
					<tr>
						<td style="padding: 8px">
							<input type="checkbox" class="styledCheckbox" value="<?php echo $l['id'] ?>">
						</td>
						<td class="text-center">
							<div class="grouped">
								<?php if(trim($c) == "Canada"){ ?>
								<a href="http://www.canada411.ca/search/?stype=si&where=<?php echo strtolower(str_replace(" ", "+", $add)) ?>" target="_blank" class="btn btn-primary">411</a>
								<?php }else if(trim($c) == "USA" || trim($c) == "États-Unis"){ ?>
								<a href="http://www.411.com/search/FindNearby?street=<?php echo strtolower(str_replace(" ", "+", $s)) ?>&where=<?php echo $z ?>" target="_blank" class="btn btn-primary">411</a>
								<?php } ?>

								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $l['id'] ?>" title="Edit Lead">Edit</button>
							</div>
						</td>
						<td><span class="bullet <?php echo $bull ?>"></span><span class="<?php echo $l['lang'] == 'e'?'en':'fr' ?>"></span><?php echo $street . "<br>" . implode(",", $address) ?></td>
						<td class="text-center"><a href="https://www.google.com/maps/place/<?php echo str_replace(array(',',' ','#'), array('','+','%23'),$add); ?>" target="_blank" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a></td>
						<td>
							<form>
								<textarea class="form-control" name="comments" data-id="<?php echo $l['id'] ?>"><?php echo $l['comments'] ?></textarea>
							</form>
						</td>
						<td><?php echo Functions::getSource($l['source']) ?></td>
						<td  data-order="<?php echo date_format(date_create($l['date']), 'Ymd') ?>"><?php echo date_format(date_create($l['date']), 'F jS Y') . "<br>" . date_format(date_create($l['date']), 'h:i A') ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

		</div>
	</div>

	<?php require_once("editAgentLeadModal.php"); ?>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#address_datatable').DataTable({
				<?php if($agent['agent_lang'] == "FR"){ ?>
				"language": {
					"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
				},
				<?php } ?>
				"order": [],
				columnDefs: [ { orderable: false, targets: [0,1,2,3,4]}],
				"bStateSave": true,
			});

			$('#edit-modal').on('show.bs.modal', function(e) {
				$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
					+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-singlePartial', 20, 'singlePartial'); ?>">'
					+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
				$('#<?php echo $dynamicFormId; ?>').submit();
				$('#<?php echo $dynamicFormId; ?>').empty();
			});

			$('body').on('blur', 'textarea[name="comments"]',function() {
				$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
					+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-comments', 20, 'comments'); ?>">'
					+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
					+ '<input type="hidden" name="comments" value="' + $(this).val() + '">');
				$('#<?php echo $dynamicFormId; ?>').submit();
				$('#<?php echo $dynamicFormId; ?>').empty();
			});

		});
	</script>