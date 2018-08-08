<?php
if(file_exists('../head.php'))
	require_once('../head.php');

$status = isset($_GET['status'])?$_GET['status']:1;
$tasks = $db->getAgentTasks($_SESSION['user']['agent_id'], $status);

//OMG, this should sooooo not be here, for the love of god please remove it.
$evals = array();
foreach ($db->getEvaluationsSent() as $key => $value){
	$evals[$value['id_e']] = $value['lead_fk'];
}

?>

<div class="row">
	<div class="col-md-12">
		<table id="task_datatable" class="table table-striped table-responsive table-bordered">
			<thead>
				<tr>
					<th width="10px" class="no-sort">
						<div class="checkbox checkbox-primary checkbox-single">
							<input id="selectAll" type="checkbox">
							<label for="action-checkbox">&nbsp;</label>
						</div>
						<div class="btn-group dropdown">
							<button type="button" class="btn btn-white btn-xs dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="javascript:void(0)" id="delete"><?php echo $tr['delete_selected'] ?></a></li>
							</ul>
						</div>
					</th>
					<th width="30%"><?php echo $tr['notes'] ?></th>
					<th width="20%"><?php echo $tr['leads'] ?></th>
					<th><?php echo $tr['due'] ?></th>
					<th class="text-center"><?php echo $tr['importance'] ?></th>
					<th class="text-center"><?php echo $tr['done'] ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tasks as $t) {
					$evalSent = "";

					if(in_array($t['id'], $evals)){
						$singleEval = $db->getEvaluation(array_search($t['id'],$evals));
						$eval = "<table class='table-condensed table-responsive'>";
						$eval .= "<tr><th>".$tr['low_value'].": </th><td>$".number_format($singleEval['low'], 2) ."</td></tr>";
						$eval .= "<tr><th>".$tr['high_value'].": </th><td>$".number_format($singleEval['high'], 2) ."</td></tr>";
						$eval .= "<tr><th>".$tr['muni_value'].": </th><td>$".number_format($singleEval['municipality'], 2) ."</td></tr>";

						if($singleEval['com'] != "")
							$eval .= "<tr><th colspan='2'>".$tr['comments'].": </th></tr><tr><td colspan='2'style='text-align: justify'>".nl2br(substr($singleEval['com'], 0, 300) . " ...") ."</td></tr>";

						$eval .= "</table>";
						$evalSent ='<span data-toggle="popover" data-trigger="hover" data-placement="right"  data-html="true" data-content="'.$eval.'" class="evalSent fa fa-file-text" data-title="<h3 style=\'text-align:center; text-transform: uppercase; color:#E51937 \'>'.$tr['evaluationSent'].'</h3>"></span>';
					}

				?>
					<tr>
						<td><div class="checkbox checkbox-primary m-r-15"><input type="checkbox" value="<?php echo $t['task_id'] ?>"><label for="checkbox2"></label></div></td>
						<td><?php echo $t['note'] ?></td>
						<td style="position: relative"><span class="<?php echo $t['lang'] == 'e'?'en':'fr' ?>"></span><?php echo $evalSent ?><a href="lead/<?php echo IDObfuscator::encode($t['id']) ?>"><span style="color: #E51937;font-weight:bold"><?php echo $t['name'] . "</span><br>" . $t['phone'] . "<br><span class='email'>" .  $t['email'] ."</span>" ?></a></td>
						<td data-order="<?php echo date_format(date_create($t['dateTime']), 'Ymd') ?>"><?php echo date_format(date_create($t['dateTime']), 'F jS Y H:i') ?></td>
						<td class="text-center">
							<?php
								if($t['importance'] == 1)
									echo '<span class="label label-danger">'.$tr['high'].'</span>';
								else if($t['importance'] == 2)
									echo '<span class="label label-warning">'.$tr['medium'].'</span>';
								else if($t['importance'] == 3)
									echo '<span class="label label-success">'.$tr['low'].'</span>';
							?>
						</td>
						<td class="text-center">
							<div class="checkbox checkbox-primary m-r-15">
								<input id="checkbox1" class="done" data-id="<?php echo $t['task_id'] ?>" <?php echo $t['task_status'] == "2"?'checked':'' ?> type="checkbox">
								<label for="checkbox1"></label>
							</div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			$('#task_datatable').dataTable({
				<?php if($_SESSION['user']['agent_lang'] == "FR"){ ?>
				"language": {
					"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
				},
				<?php } ?>
				"aaSorting": [],
				columnDefs: [{ orderable: false, targets: [0,5]}],
				"bStateSave": true
			});

		});
		$('body').on('change','.done', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-taskDone', 20, 'taskDone'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="done" value="' + $(this).prop("checked") + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		})
	</script>