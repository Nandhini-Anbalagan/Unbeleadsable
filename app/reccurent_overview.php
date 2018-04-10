<?php
require_once('header.php');
require_once('load/misc/dynamic-form.php');
require_once('load/top-menu.php'); 

if($_SESSION['user']['level'] <= 50)
	Functions::redirect("agents");
?>

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 col-md-offset-2">
				<div class="card-box">
					<h2 class="page-title m-b-20">Renewal Overview</h2>
					<table class="table table-hover" id="reccuringTable">
						<thead>
							<tr>
								<th></th>
								<th>Agent Name</th>
								<th>Date Subscribe</th>
								<th>Last Billed</th>
								<th>Next Billing</th>
								<th>Trie(s)</th>
								<th>Status</6ath>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($db->getReccuringOverview() as $key => $value) {
									$tr = "<tr>";
										$tr .="<td>" . ($key+1) . "</td>";
										$tr .="<td>" . $value['agent_name'] . " (Acc #". $value['agent_fk'] .") - ".$value['area_name']."<br><i>". $value['agent_email'] . "</i></td>";
										$tr .="<td class='text-center'>" . substr($value['agent_date'], 0, 10) . "</td>";
										$tr .="<td class='text-center'>" . substr($value['invoice_date'], 0, 10) . "</td>";
										$tr .="<td class='text-center'>" . substr($value['next_billing'], 0, 10) . "</td>";
										$tr .="<td class='text-center'>" . $value['counter'] . "</td>";
										$tr .="<td>" . $value['status'] . "</td>";
									$tr .= "</tr>";
									echo $tr;
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){
		$('#reccuringTable').dataTable({
			"aaSorting": [],
			"bStateSave": true,
		});
	})
</script>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>