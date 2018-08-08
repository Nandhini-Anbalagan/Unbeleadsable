<?php
	if(file_exists("../head.php"))
		include("../head.php");

	if(!isset($_POST))
		die("No Post");

	$csv = array_map('str_getcsv', file('../temp/' . trim($_POST['uploadedFile'])));
	$th = array_shift($csv);

	if(empty($th) || COUNT($th) < 4)
		$th = array_shift($csv);

	$columnsMap = array(
		'date' => $tr['date'],
		'type' => $tr['type'],
		'name' => $tr['name'],
		'phone'=>$tr['phone'],
		'email'=>$tr['email'],
		'address'=>$tr['address'],
		'apt'=>$tr['apt'],
		'civic'=>$tr['civic'],
		'street'=>$tr['street'],
		'city'=>$tr['city'],
		'province'=>$tr['province'],
		'postal'=>$tr['postal'],
		'country'=>$tr['country'],
		'selling'=>$tr['selling_in'],
		'comments'=>$tr['notes'],
		'lang'=>$tr['language'],
		'value_range'=>$tr['value_range'],
		'value_epp'=>$tr['eppraisal'],
		'beds'=>$tr['beds'],
		'baths'=>$tr['baths'],
		'sqft'=>$tr['sqft'],
		'buying_frame'=>$tr['buying_frame'],
		'price_range'=>$tr['price_range'],
		'neighborhood'=>$tr['neighborhoods'],
		'prequalified'=>$tr['prequalified'],
		'lender'=>$tr['lender'],
		'lender_phone'=>$tr['lender_phone'],
		'lender_email'=>$tr['lender_email'],
		'loan_type'=>$tr['loan_type'],
		'credit'=>$tr['credit'],
		'planning_sell'=>$tr['planning_to_sell'],
		'other_contact'=>$tr['other_contact'],
		'other_contact_phone'=>$tr['other_contact_phone'],
		'other_contact_email'=>$tr['other_contact_email']
	);
?>

<hr>
<h3><?php echo $tr['lead_validate'] ?></h3>
<table id="datatable" class="table table-striped table-condensed">
	<thead>
		<tr>
			<?php foreach ($columnsMap as $value) {
				echo "<th>" . $value . "</th>";
			} ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($csv as $value) {
			if(COUNT($value) < COUNT($th))
					continue;

			echo "<tr>";

			foreach ($columnsMap as $key => $row){
				if(COUNT($value) < COUNT($th))
					continue;

				echo "<td>" . ($_POST[$key] != 1000?utf8_encode(str_replace("'", "\\'",$value[$_POST[$key]])):'N/A') . "</td>";
			}
			echo "</tr>";
		} ?>
	</tbody>
</table>
<hr>
<div class="form-group">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<button type="submit" class="btn btn-block btn-success waves-effect waves-light"><?php echo $tr['upload_leads'] ?></button>
		 </div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#datatable').DataTable({
			<?php if($agent['agent_lang'] == "FR"){ ?>
			"language": {
				"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
			},
			<?php } ?>
			"ordering": false
		});
	});
</script>