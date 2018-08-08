<?php
require_once('header.php');
require_once('load/top-menu.php');
require_once('load/misc/dynamic-form.php');

$leadID = (int)trim(IDObfuscator::decode($_GET['id']));
$lead = $db->getSingleAgentsLead($leadID);
$status = $db->getAgentStatus($_SESSION['user']['agent_id']);
$sta = $db->getStatus($lead['status'])['name_en'];
$address = explode(",", $lead['address']);

$pro = isset($address[2])?$address[2]:'';
$prov = explode(" ", trim($pro));
$msg = $db->getMessageHistory($leadID);

$leads = $db->getAgentsLead($_SESSION['user']['agent_id'], $_SESSION['user']["agent_slug"]);
$key = -1;

foreach($leads as $k => $l)
	if ($l['id'] == $leadID)
		$key = $k;

$prev = $key+1<COUNT($leads)?$leads[$key+1]:false;
$next = $key-1>=0?$leads[$key-1]:false;

$postActionAgentLead = Tokenizer::add('post-action-agentLead', 20, 'agentLead');
$postCaseAgentLeadViewMsg = Tokenizer::add('post-case-agentLead-viewMsg', 30, 'viewMsg');

?>
<div class="wrapper m-t-0">
	<div class="container">
		<ul class="pager">
			<?php if($next): ?>
				<li class="previous"><a href="lead/<?php echo IDObfuscator::encode($next['id']) ?>"><i class="fa fa-angle-left pull-left"></i><b><?php echo $tr['prev'] . "<br>" . $next['name'] ?></b></a></li>
			<?php endif;
			if($prev):
			?>
				<li class="next"><a href="lead/<?php echo IDObfuscator::encode($prev['id']) ?>"><i class="fa fa-angle-right pull-right"></i><b><?php echo $tr['next'] . "<br>" . $prev['name'] ?></b></a></li>
			<?php endif; ?>
		</ul>
		<div class="row">
			<div class="col-sm-5">
				<div id="lead_top" class="card-box">
					<?php require_once("load/lead_top.php"); ?>
				</div>

				<div id="lead_info" class="card-box">
					<?php require_once("load/lead_info.php"); ?>
				</div>

				<?php require_once("load/lead_tasks.php") ?>

			</div>

			<div class="col-sm-7">
				<div class="card-box">
					<?php require_once("load/lead_right.php") ?>
				</div>

				<div class="card-box">
					<h2>Messages sent to Lead</h2>
					<hr>
					<?php
					foreach ($msg as $m) { ?>
					<a href="" class="msgHover" data-toggle="modal" data-target="#view-msg" data-id="<?php echo $m['id'] ?>">
						<div class="well" style="padding: 0px 20px;">
							<h4><small><i class="fa fa-send"></i>&nbsp;<?php echo $m['type'] ?></small><small class="pull-right"><i class="fa fa-clock-o"></i> &nbsp;<?php echo $m['date'] ?></small></h4>
							<h3><?php echo $m['subject'] ?></h3>
						</div>
					</a>
					<?php } ?>
				</div>
			</div>

		</div>
	</div>
</div>

	<div id="view-msg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content p-0 b-0">
				<div class="panel panel-color panel-primary">
					<div class="panel-heading">
						<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
						<h2 class="panel-title text-center"><?php echo $tr['view_message'] ?></h2>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" style="overflow: hidden;">
								<table class="table dataTable" style="table-layout: fixed;" >
									<tbody>
										<tr>
											<th width="15%"><?php echo $tr['date'] ?></th>
											<td target="date"></td>
										</tr>
										<tr>
											<th><?php echo $tr['subject'] ?></th>
											<td target="subject"></td>
										</tr>
										<tr>
											<th><?php echo $tr['message'] ?></th>
											<td target="message" style="word-wrap:break-word"></td>
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

<script>
	$(function(){
		$('#datepicker').datepicker({
			autoclose: true,
			todayHighlight: true,
			format: "yyyy-mm-dd",
			startDate: "today"
		});

		$('.clockpicker').clockpicker({
			donetext: 'Done'
		});

		$('#view-msg').on('show.bs.modal', function(e) {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionAgentLead; ?>">'
				+ '<input type="hidden" name="case" value="<?php echo $postCaseAgentLeadViewMsg; ?>">'
				+ '<input type="hidden" name="id" value="' + $(e.relatedTarget).data('id') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});
	});
</script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>