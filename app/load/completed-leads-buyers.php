<?php
if(file_exists('../head.php'))
	require_once('../head.php');

if(isset($_GET['range']))
	$leads = $db->filterDateAgentsLead($_SESSION['user']['agent_id'], 'home_buyers', $_GET['range']);
else
	$leads = $db->getAgentsLead($_SESSION['user']['agent_id'], 'home_buyers');

$status = $db->getAgentStatus($_SESSION['user']['agent_id']);
$blackLists = $db->getBlacklists();

$postActionStatus = "";
$postCaseChange = "";

//OMG, this should sooooo not be here, for the love of god please remove it.
$evals = array();
foreach ($db->getEvaluationsSent() as $key => $value){
	$evals[$value['id_e']] = $value['lead_fk'];
}
?>

<table id="leads_datatable" class="table table-striped table-responsive table-bordered">
	<thead>
		<tr>
			<th style="padding: 5px;">
				<input id="selectAll" data-toggle="tooltip" data-placement="right" data-original-title="Select All" type="checkbox" class="styledCheckbox">
			</th>
			<th class="text-uppercase"><?php echo $tr['status'] ?></th>
			<th class="text-uppercase"><?php echo $tr['name_contact'] ?></th>
			<th class="text-uppercase" width="5px"><?php echo $tr['funnels'] ?></th>
			<th class="text-uppercase"><?php echo $tr['notes'] ?></th>
			<th class="text-uppercase"><?php echo $tr['buying_in'] ?></th>
			<th class="text-uppercase"><?php echo $tr['no_beds'] ?></th>
			<th class="text-uppercase"><?php echo $tr['source'] ?></th>
			<th class="text-uppercase"><?php echo $tr['date'] ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leads as $l) {
			if($l['status'] != -1)
				$sta = $db->getStatus($l['status'])['name_en'];
			else
				$sta = $db->getStatus(1)['name_en'];
		?>
		<tr>
			<td style="padding: 5px"><input type="checkbox" class="styledCheckbox" value="<?php echo $l['id'] ?>"></td>
			<td>
				<div class="btn-group">
					<div class="dropdown">
						<button type="button" class="btn btn-block btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $sta ?> <span class="caret"></span></button>
						<ul class="dropdown-menu status">
						<?php
							foreach ($status as $k => $s){
								if($l['status'] != -1)
									$active = ($l['status'] == $s['id'])?'active':'';
								else
									$active = $k == 0?'active':'';

								echo '<li class="'.$active.'"><a href="javascript:void(0)" data-status="'.$s['id'].'" data-lead="'.$l['id'].'">'.$s['name_en'].'</a></li>';
							}

						?>
						</ul>
					</div>
					<br>
					<div class="dropdown">
						<button type="button" class="btn btn-block btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $l['type'] ?> <span class="caret"></span></button>
						<ul class="dropdown-menu type">
							<li class=""><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['seller'] ?></a></li>
							<li class=""><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['buyer'] ?></a></li>
							<li class=""><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['buyer_seller'] ?></a></li>
							<li class=""><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['reft'] ?></a></li>
							<li class=""><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['rental'] ?></a></li>
						</ul>
					</div>
				</div>
			</td>
			<td style="position: relative" data-name="<?php echo $l['name'] ?>" data-lang="<?php echo $l['lang'] ?>" data-email="<?php echo $l['email'] ?>"><span class="<?php echo $l['lang'] == 'e'?'en':'fr' ?>"></span><span style="color: #E51937;font-weight:bold"><?php echo $l['name'] . "</span><br>" . $l['phone'] . "<br><span class='email'>" .  $l['email'] ."</span>" ?></td>
			<td align="center">
				<input type="checkbox" class="funnel" data-id="<?php echo $l['id'] ?>" <?php echo Functions::search_array($l['email'], $blackLists)?'':'checked'; ?> data-plugin="switchery" data-color="#81C868" data-size="small"/>
			</td>
			<td>
				<form>
					<textarea class="form-control" name="comments" data-id="<?php echo $l['id'] ?>"><?php echo $l['comments'] ?></textarea>
				</form>
			</td>
			<td><?php echo Functions::getSellingIn($l['buying'], $agent['agent_lang']); ?>
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
			<td class="text-center"><?php echo $l['bedrooms'] ?></td>
			<td><?php echo Functions::getSource($l['source']) ?></td>
			<td  data-order="<?php echo date_format(date_create($l['date']), 'Ymd') ?>"><?php echo date_format(date_create($l['date']), 'F jS Y') . "<br>" . date_format(date_create($l['date']), 'h:i A') ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script>
	$(document).ready(function(){
		$('#leads_datatable').dataTable({
			<?php if($_SESSION['user']['agent_lang'] == "FR"){ ?>
			"language": {
				"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
			},
			<?php } ?>
			"aaSorting": [],
			columnDefs: [{ orderable: false, targets: [0,1,2,3,4,5]}],
			"bStateSave": true,
		});

		$('body').on('change','.funnel', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-pause', 20, 'pause'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="switch" value="' + $(this).prop("checked") + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});

</script>
