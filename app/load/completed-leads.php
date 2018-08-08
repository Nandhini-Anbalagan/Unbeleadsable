<?php
if(file_exists('../head.php'))
	require_once('../head.php');

if(isset($_GET['range']))
	$leads = $db->filterDateAgentsLead($_SESSION['user']['agent_id'], 'home_sellers', $_GET['range']);
else
	$leads = $db->getAgentsLead($_SESSION['user']['agent_id'], 'home_sellers');

$status = $db->getAgentStatus($_SESSION['user']['agent_id']);
$blackLists = $db->getBlacklists();

$postActionStatus = "";
$postCaseChange = "";

//OMG, this should sooooo not be here, for the love of god please remove it.
$evals = array();
foreach ($db->getEvaluationsSent() as $key => $value){
	$evals[$value['id_e']] = $value['lead_fk'];
}

$funnelCat = $db->getFunnelCategories($_SESSION['user']['agent_id']);

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
			<th class="text-uppercase"><?php echo $tr['address'] ?></th>
			<th class="text-uppercase"><?php echo $tr['selling_in'] ?></th>
			<th class="text-uppercase"><?php echo $tr['source'] ?></th>
			<th class="text-uppercase"><?php echo $tr['date'] ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leads as $l) {
			$address = explode(",", $l['address']);
			$street = $address[0];
			array_shift($address);
			$sta_name = $_SESSION['user']['agent_lang'] == "EN"?'name_en':'name_fr';
			
			if($l['status'] != -1)
				$sta = $db->getStatus($l['status'])[$sta_name];
			else
				$sta = $db->getStatus(1)[$sta_name];

			$noApt = explode(" ", $l['address']);

			if(strpos($noApt[0], "#") === 0)
				array_shift($noApt);

			$add = implode(" ", $noApt);
			$evalSent = "";

			if(in_array($l['id'], $evals)){
				$singleEval = $db->getEvaluation(array_search($l['id'],$evals));
				$eval = "<table class='table-condensed table-responsive'>";
				$eval .= "<tr><th>".$tr['low_value'].": </th><td>$".number_format($singleEval['low'], 2) ."</td></tr>";
				$eval .= "<tr><th>".$tr['high_value'].": </th><td>$".number_format($singleEval['high'], 2) ."</td></tr>";
				$eval .= "<tr><th>".$tr['muni_value'].": </th><td>$".number_format($singleEval['municipality'], 2) ."</td></tr>";

				if($singleEval['com'] != "")
					$eval .= "<tr><th colspan='2'>".$tr['comments'].": </th></tr><tr><td colspan='2'style='text-align: justify;'>".nl2br(substr($singleEval['com'], 0, 300) . " ...") ."</td></tr>";

				$eval .= "</table>";
				$evalSent ='<span data-toggle="popover" data-trigger="hover" data-placement="right"  data-html="true" data-content="'.$eval.'" class="evalSent fa fa-file-text" data-title="<h3 style=\'text-align:center; text-transform: uppercase; color:#E51937 \'>'.$tr['evaluationSent'].'</h3>"></span>';
			}
		?>
		<tr>
			<td style="padding: 5px"><input type="checkbox" class="styledCheckbox" value="<?php echo $l['id'] ?>"></td>
			<td>
				<div class="btn-group">
					<div class="dropdown">
						<button type="button" class="btn btn-block btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $sta ?> <span class="caret"></span></button>
						<ul class="dropdown-menu danger status">
						<?php
							foreach ($status as $k => $s){
								if($l['status'] != -1)
									$active = ($l['status'] == $s['id'])?'active':'';
								else
									$active = $k == 0?'active':'';

								echo '<li class="'.$active.'"><a href="javascript:void(0)" data-status="'.$s['id'].'" data-lead="'.$l['id'].'">'.$s[$sta_name].'</a></li>';
							}

						?>
						</ul>
					</div>
					<br>
					<div class="dropdown">
						<button type="button" class="btn btn-block btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $l['type'] ?> <span class="caret"></span></button>
						<ul class="dropdown-menu danger type">
							<li class="<?php echo $l['type'] == $tr['seller']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['seller'] ?></a></li>
							<li class="<?php echo $l['type'] == $tr['buyer']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['buyer'] ?></a></li>
							<li class="<?php echo $l['type'] == $tr['buyer_seller']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['buyer_seller'] ?></a></li>
							<li class="<?php echo $l['type'] == $tr['reft']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['reft'] ?></a></li>
							<li class="<?php echo $l['type'] == $tr['rental']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>"><?php echo $tr['rental'] ?></a></li>
						</ul>
					</div>
				</div>
			</td>
			<td style="position: relative" data-name="<?php echo $l['name'] ?>" data-lang="<?php echo $l['lang'] ?>" data-email="<?php echo $l['email'] ?>"><span class="<?php echo $l['lang'] == 'e'?'en':'fr' ?>"></span><?php echo $evalSent ?><a href="lead/<?php echo IDObfuscator::encode($l['id']) ?>"><span style="color: #E51937;font-weight:bold"><?php echo $l['name'] . "</span><br>" . $l['phone'] . "<br><span class='email'>" .  $l['email'] ."</span>" ?></a></td>
			<td align="center">
				<input type="checkbox" class="funnel" data-id="<?php echo $l['id'] ?>" <?php echo Functions::search_array($l['email'], $blackLists)?'':'checked'; ?> data-plugin="switchery" data-color="#81C868" data-size="small"/>
				<div class="btn-group dropdown">
					<button type="button" class="btn btn-white btn-xs dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
					<ul class="dropdown-menu danger selectFunnel" role="menu">
						<?php foreach ($funnelCat as $funnel) { ?>
						<li class="<?php echo $l['funnels'] == $funnel['id']?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="<?php echo $funnel['id'] ?>"><?php echo $funnel['title'] ?></a></li>
						<?php } ?>
						<li><a class="notMe" href="funnels"><?php echo $tr['create_new_funnel'] ?></a></li>
					</ul>
				</div>
			</td>
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
					<ul class="dropdown-menu danger selling" role="menu">
						<?php if($agent['agent_lang'] == "EN"){ ?>
						<li class="<?php echo $l['status'] == 0?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="0">Not Selected</a></li>
						<li class="<?php echo $l['status'] == 1?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="1">1-3 months</a></li>
						<li class="<?php echo $l['status'] == 2?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="2">3-6 months</a></li>
						<li class="<?php echo $l['status'] == 3?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="3">6-12 months</a></li>
						<li class="<?php echo $l['status'] == 4?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="4">12+ months</a></li>
						<li class="<?php echo $l['status'] == 5?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="5">Just curious</a></li>
						<li class="<?php echo $l['status'] == 6?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="6">Refinancing</a></li>
						<?php }else{ ?>
						<li class="<?php echo $l['status'] == 0?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="0">Non séléctionné</a></li>
						<li class="<?php echo $l['status'] == 1?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="1">1-3 Mois</a></li>
						<li class="<?php echo $l['status'] == 2?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="2">3-6 Mois</a></li>
						<li class="<?php echo $l['status'] == 3?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="3">6-12 Mois</a></li>
						<li class="<?php echo $l['status'] == 4?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="4">12+ Mois</a></li>
						<li class="<?php echo $l['status'] == 5?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="5">Par Curiosité</a></li>
						<li class="<?php echo $l['status'] == 6?'active':'' ?>"><a href="javascript:void(0)" data-id="<?php echo $l['id'] ?>" data-value="6">Refinancement</a></li>
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

		$('')
	});

</script>
