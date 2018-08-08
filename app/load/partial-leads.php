<?php 
if(file_exists('../head.php'))
	require_once('../head.php');
if(isset($_GET['range']))
	$leads = $db->filterDateAgentsPartialLead($_SESSION['user']['agent_id'], 'home_sellers', $_GET['range']);
else
	$leads = $db->getAgentsPartialLead($_SESSION['user']['agent_id'], 'home_sellers'); 

?>

<table id="partial_datatable" class="table table-striped table-responsive table-bordered">
	<thead>
		<tr>
			<th style="padding: 5px;">
				<input id="selectAll" data-toggle="tooltip" data-placement="right" data-original-title="Select All" type="checkbox" class="styledCheckbox">
			</th>
			<th><?php echo $tr['action'] ?></th>
			<th><?php echo $tr['name_contact'] ?></th>
			<th class="no-sort"><?php echo $tr['notes'] ?></th>
			<th class="no-sort"><?php echo $tr['address'] ?></th>
			<th><?php echo $tr['selling_in'] ?></th>
			<th><?php echo $tr['source'] ?></th>
			<th><?php echo $tr['date'] ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leads as $l) { 
			$address = explode(",", $l['address']);
			$street = $address[0];
			array_shift($address);

			$noApt = explode(" ", $l['address']);

			if(strpos($noApt[0], "#") === 0)
				array_shift($noApt);

			$add = implode(" ", $noApt);

			$a = explode(",", $add);
			$s = $a[0];
			$c = array_key_exists((count($a) - 1),$a)?$a[count($a) - 1]:"";
			$z = array_key_exists((count($a) - 2),$a)?$a[count($a) - 2]:"";
		?>
			<tr>
				<td style="padding: 5px"><input type="checkbox" class="styledCheckbox" value="<?php echo $l['id'] ?>"></td>
				<td>
					<div class="grouped">
						<?php if(trim($c) == "Canada"){ ?>
						<a href="http://www.canada411.ca/search/?stype=si&where=<?php echo strtolower(str_replace(" ", "+", $add)) ?>" target="_blank" class="btn btn-primary">411</a>
						<?php }else if(trim($c) == "USA"){ ?>
						<a href="http://www.411.com/search/FindNearby?street=<?php echo strtolower(str_replace(" ", "+", $s)) ?>&where=<?php echo $z ?>" target="_blank" class="btn btn-primary">411</a>
						<?php } ?>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit-modal" data-id="<?php echo $l['id'] ?>" title="Edit Lead">Edit</button>
					</div>
				</td>
				<td><span class="<?php echo $l['lang'] == 'e'?'en':'fr' ?>"></span><a href="javascript:void(0)"><?php echo $l['name'] . "<br>" . $l['phone'] . "<br><span class='email'>" .  $l['email'] ."</span>" ?></a></td>
				<td>
					<form>
						<textarea class="form-control" name="comments" data-id="<?php echo $l['id'] ?>"><?php echo $l['comments'] ?></textarea>
					</form>
				</td>
				<td><a href="https://www.google.com/maps/place/<?php echo str_replace(array(',',' ','#'), array('','+','%23'),$add); ?>" target="_blank"> <?php echo $street . "<br>" . implode(",", $address) ?></a></td>
				<td><?php echo Functions::getSellingIn($l['selling'], $agent['agent_lang']); ?> 
				<br>
				<div class="btn-group dropdown">
					<button type="button" class="btn btn-white btn-xs dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
					<ul class="dropdown-menu selling" role="menu">
						<?php if($agent['agent_lang'] == "EN"){ ?>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="0">Not Selected</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="1">1-3 months</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="2">3-6 months</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="3">6-12 months</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="4">12+ months</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="5">Just curious</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="6">Refinancing</a></li> 
						<?php }else{ ?>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="0">Non séléctionné</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="1">1-3 Mois</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="2">3-6 Mois</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="3">6-12 Mois</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="4">12+ Mois</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="5">Par Curiosité</a></li>
						<li><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="6">Refinancement</a></li>
						<?php } ?>
					</ul>
				</div>
			</td>
				<td><?php echo Functions::getSource($l['source']) ?></td>
				<td  data-order="<?php echo date_format(date_create($l['date']), 'Ymd') ?>"><?php echo date_format(date_create($l['date']), 'F jS Y') . "<br>" . date_format(date_create($l['date']), 'h:i A') ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

<?php require_once("editAgentLeadModal.php"); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#partial_datatable').dataTable({
			<?php if($agent['agent_lang'] == "FR"){ ?>
			"language": {
				"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
			},
			<?php } ?>
			 "order": [],
			columnDefs: [ { "orderable": false, "targets": [0,1,2,3,4]}],
			"bStateSave": true
		});

		$('select').select2();

		$('#edit-modal').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-singlePartial', 20, 'singlePartial'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('click','.selling li a', function(e){
			e.preventDefault(); 
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-selling', 20, 'selling'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="text" value="' + $(this).text() + '">');
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