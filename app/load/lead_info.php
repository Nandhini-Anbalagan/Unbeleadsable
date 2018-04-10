
<div class="clearfix"></div>
<form class="form-horizontal" onsubmit="return validate(this)" role="form">
	<input type="hidden" name="id" value="<?php echo $leadID ?>">
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-edit', 20, 'edit'); ?>">
	<input type="hidden" name="home_id" value="<?php echo $lead['id']; ?>">

	<div class="form-group">
		<label class="control-label col-sm-4" for="lang"><?php echo $tr['language'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="lang" id="lang">
				<option <?php echo $lead['lang'] == "e"?'selected':'' ?> value="e">English</option>
				<option <?php echo $lead['lang'] == "f"?'selected':'' ?> value="f">Français</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="name"><?php echo $tr['name'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="name" name="name" value="<?php echo $lead['name'] ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="phone"><?php echo $tr['phone'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="phone" name="phone" value="<?php echo $lead['phone'] ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="email"><?php echo $tr['email'] ?></label>
		<div class="col-sm-8">
			<input type="email" class="form-control input-sm" id="email" name="email" value="<?php echo $lead['email'] ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="street"><?php echo $tr['street'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="street" name="street" value="<?php echo isset($address[0])?$address[0]:'' ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="city"><?php echo $tr['city'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="city" name="city" value="<?php echo isset($address[1])?$address[1]:'' ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="province"><?php echo $tr['province'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="province" name="province" value="<?php echo array_shift($prov) ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="postal"><?php echo $tr['postal'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="postal" name="postal" value="<?php echo join(" ", $prov) ?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="selling"><?php echo $tr['selling_in'] ?></label>
		<div class="col-sm-8">

			<select class="form-control input-sm fancy" name="selling" id="selling">
				<?php if($agent['agent_lang'] == "EN"){ ?>
				<option <?php echo $lead['selling'] == "0"?'selected':'' ?> value="0">Not Selected</option>
				<option <?php echo $lead['selling'] == "1"?'selected':'' ?> value="1"></option>
				<option <?php echo $lead['selling'] == "2"?'selected':'' ?> value="2">3-6 months</option>
				<option <?php echo $lead['selling'] == "3"?'selected':'' ?> value="3">6-12 months</option>
				<option <?php echo $lead['selling'] == "4"?'selected':'' ?> value="4">12+ months</option>
				<option <?php echo $lead['selling'] == "5"?'selected':'' ?> value="5">Just curious</option>
				<option <?php echo $lead['selling'] == "6"?'selected':'' ?> value="6">Refinancing</option>
				<?php }else{ ?>
				<option <?php echo $lead['selling'] == "0"?'selected':'' ?> value="0">Non séléctionné</option>
				<option <?php echo $lead['selling'] == "1"?'selected':'' ?> value="1">1-3 Mois</option>
				<option <?php echo $lead['selling'] == "2"?'selected':'' ?> value="2">3-6 Mois</option>
				<option <?php echo $lead['selling'] == "3"?'selected':'' ?> value="3">6-12 Mois</option>
				<option <?php echo $lead['selling'] == "4"?'selected':'' ?> value="4">12+ Mois</option>
				<option <?php echo $lead['selling'] == "5"?'selected':'' ?> value="5">Par Curiosité</option>
				<option <?php echo $lead['selling'] == "6"?'selected':'' ?> value="6">Refinancement</option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="range"><?php echo $tr['value_range'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="range" name="range" value="<?php echo $lead['value_range'] ?>" placeholder="Value Range">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="eppraisal"><?php echo $tr['eppraisal'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="eppraisal" name="eppraisal" value="<?php echo $lead['value_epp'] ?>" placeholder="Eppraisal Value">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="beds"><?php echo $tr['beds'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="beds" name="beds" value="<?php echo $lead['beds'] ?>" placeholder="Beds">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="baths"><?php echo $tr['baths'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="baths" name="baths" value="<?php echo $lead['baths'] ?>" placeholder="Baths">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="sqft"><?php echo $tr['sqft'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="sqft" name="sqft" value="<?php echo $lead['sqft'] ?>" placeholder="SQFT">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="buying"><?php echo $tr['buying_frame'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="buying" id="buying">
				<option <?php echo $lead['buying_frame'] == ""?'selected':'' ?> value=""><?php echo $tr['not_selected'] ?></option>
				<option <?php echo $lead['buying_frame'] == "ASAP"?'selected':'' ?> value="ASAP"><?php echo $tr['asap'] ?></option>
				<option <?php echo $lead['buying_frame'] == "1-3 Months"?'selected':'' ?> value="1-3 Months"><?php echo $tr['1_3_months'] ?></option>
				<option <?php echo $lead['buying_frame'] == "3-6 Months"?'selected':'' ?> value="3-6 Months"><?php echo $tr['3_6_months'] ?></option>
				<option <?php echo $lead['buying_frame'] == "6-12 Months"?'selected':'' ?> value="6-12 Months"><?php echo $tr['6_12_months'] ?></option>
				<option <?php echo $lead['buying_frame'] == "Not sure"?'selected':'' ?> value="Not sure"><?php echo $tr['not_sure'] ?></option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="price"><?php echo $tr['price_range'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="price" name="price" value="<?php echo $lead['price_range'] ?>" placeholder="Price Range">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="neighborhoods"><?php echo $tr['neighborhoods'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="neighborhoods" name="neighborhoods" value="<?php echo $lead['neighborhood'] ?>" placeholder="Neighborhoods">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="prequalified"><?php echo $tr['prequalified'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="prequalified" id="prequalified">
				<option value=""></option>
				<option <?php echo $lead['prequalified'] == "Yes"?'selected':'' ?> value="Yes">Yes</option>
				<option <?php echo $lead['prequalified'] == "No"?'selected':'' ?> value="No">No</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="lender"><?php echo $tr['lender'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="lender" name="lender" value="<?php echo $lead['lender'] ?>" placeholder="Lender">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="lender_email"><?php echo $tr['lender_email'] ?></label>
		<div class="col-sm-8">
			<input type="email" class="form-control input-sm" id="lender_email" name="lender_email" value="<?php echo $lead['lender_email'] ?>" placeholder="Lender Email">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="lender_phone"><?php echo $tr['lender_phone'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="lender_phone" name="lender_phone" value="<?php echo $lead['lender_phone'] ?>" placeholder="Lender Phone">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="loan_type"><?php echo $tr['loan_type'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="loan_type" name="loan_type" value="<?php echo $lead['loan_type'] ?>" placeholder="Loan Type">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="credit"><?php echo $tr['credit'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="credit" id="credit">
				<option value=""></option>
				<option <?php echo $lead['credit'] == "Excellent"?'selected':'' ?> value="Excellent">Excellent</option>
				<option <?php echo $lead['credit'] == "Good"?'selected':'' ?> value="Good">Good</option>
				<option <?php echo $lead['credit'] == "Fair"?'selected':'' ?> value="Fair">Fair</option>
				<option <?php echo $lead['credit'] == "Poor"?'selected':'' ?> value="Poor">Poor</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="sell"><?php echo $tr['planning_to_sell'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="planning_sell" id="sell">
				<option value=""></option>
				<option <?php echo $lead['planning_sell'] == "Not Sure"?'selected':'' ?> value="Not Sure">Not Sure</option>
				<option <?php echo $lead['planning_sell'] == "Yes"?'selected':'' ?> value="Yes">Yes</option>
				<option <?php echo $lead['planning_sell'] == "No"?'selected':'' ?> value="No">No</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="alert"><?php echo $tr['property_alerts'] ?></label>
		<div class="col-sm-8">
			<select class="form-control input-sm fancy" name="alert_setup" id="alert">
				<option value=""></option>
				<option <?php echo $lead['alert_setup'] == "Yes"?'selected':'' ?> value="Yes">Yes</option>
				<option <?php echo $lead['alert_setup'] == "No"?'selected':'' ?> value="No">No</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="other_contact"><?php echo $tr['other_contact'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="other_contact" value="<?php echo $lead['other_contact'] ?>" name="other_contact" placeholder="Other Contact">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="other_contact_phone"><?php echo $tr['other_contact_phone'] ?></label>
		<div class="col-sm-8">
			<input type="text" class="form-control input-sm" id="other_contact_phone" name="other_contact_phone" value="<?php echo $lead['other_contact_phone'] ?>" placeholder="Phone">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4" for="other_contact_email"><?php echo $tr['other_contact_email'] ?></label>
		<div class="col-sm-8">
			<input type="email" class="form-control input-sm" id="other_contact_email" name="other_contact_email" value="<?php echo $lead['other_contact_email'] ?>" placeholder="Email">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-4">
			<button type="submit" class="btn btn-success btn-block"><?php echo $tr['save'] ?></button>
		</div>
		<div class="col-sm-4">
			<button type="button" class="btn btn-danger btn-block"><?php echo $tr['cancel'] ?></button>
		</div>
	</div>
</form>