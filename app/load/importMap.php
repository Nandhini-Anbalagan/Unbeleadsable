<?php
	if(file_exists("../head.php"))
		include("../head.php");

	if(!isset($_GET['file']))
		die();

	$csv = array_map('str_getcsv', file('../temp/' . $_GET['file']));
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
<h3><?php echo $tr['lead_map'] ?></h3>
<?php foreach ($columnsMap as $col => $val) { ?>
<div class="form-group" style="margin-bottom: 5px;">
	<label class="col-sm-3 col-sm-offset-1 control-label"><?php echo $val ?></label>
	<div class="col-sm-4">
		<select class="form-control" name="<?php echo $col ?>">
			<option value="1000">Select One</option>
			<option value="1000">N/A</option>
			<?php foreach ($th as $key => $value) {
				echo '<option value="'.$key.'" '.(utf8_encode($value) == utf8_encode($val)?'selected':'') .'>'.utf8_encode($value).'</option>';
			} ?>
		</select>
	</div>
</div>
<?php } ?>

<label class="col-sm-3 col-sm-offset-1 control-label"></label>
<div class="col-sm-4">
	<button class="btn btn-block btn-success" id="validateMap"><?php echo $tr['validate_mapping'] ?></button>
</div>
<div class="clearfix"></div>
<hr>
<script>
	$(function(){
		$('select').select2();
	});
</script>