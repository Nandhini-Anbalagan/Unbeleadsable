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
					<h2 class="page-title m-b-20">Manual Subscription</h2>
					<form id="subscription" class="form-horizontal" role="form" data-parsley-validate="" novalidate >
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-submit-subscription', 30, 'submit-subscription'); ?>">

						<input type="hidden" name="id" value="">
						<input type="hidden" name="user_id" value="">
						<input type="hidden" name="lang" value="">
						<input type="hidden" name="area" value="">

						<!-- PAYMENT BLOCK -->
						<div class="well">
							<h2 class="text-center">Payment Information</h2>
							<table style="width: 100%">
								<tr>
									<td><h3>Subscription Fee <small>Recurring Every Month</small></h3></td>
									<td><h3>$ <input name="subscription" id="subscription" type="number" min="0" step="0.01" value="<?php echo Config::SUBSCRIPTION ?>" style="display:inline; width: 50%; margin: 0;"/> USD</h3></td>
								</tr>

								<tr>
									<td><h3>Ad Campaign <small>Recurring Every Month</small></h3></td>
									<td><h3>$ <input name="amount" id="amount" type="number" min="0" step="0.01" style="display:inline; width: 50%; margin: 0;"/> USD</h3></td>
								</tr>
								<tr>
									<td><h3>Reset Reccurent <small>Automatically charge next month</small></h3></td>
									<td><input name="reccuent" id="reccuent" type="checkbox" checked style="width: 30px; height: 30px; margin-left: 15px;"/></td>
								</tr>
							</table>
						</div>

						<!-- BILLING BLOCK -->
						<h2 class="text-center">Billing Information</h2>
						<div class="form-group">
							<label class="col-sm-3 control-label">Company:</label>
							<div class="col-sm-7">
								<select id="agent" class="form-control fancy">
									<option value="">Please Select</option>
									<?php foreach ($db->getAgents() as $a){
										echo '<option value="'.IDObfuscator::encode($a['agent_id']).'">'.$a['agent_name']."(".$a['assigned_area'].") - [".ucwords(str_replace("home_", "", $a['agent_slug']))."]".'</option>';
									} ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">First Name:</label>
							<div class="col-sm-7">
								<input class="form-control" name="fname" id="fname" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name:</label>
							<div class="col-sm-7">
								<input class="form-control" name="lname" id="lname" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">E-mail:</label>
							<div class="col-sm-7">
								<input class="form-control" name="email" id="email" type="text"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Address:</label>
							<div class="col-sm-7">
								<input class="form-control" name="address" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">City:</label>
							<div class="col-sm-7">
								<input class="form-control" name="city" id="city" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">State/Province:</label>
							<div class="col-sm-7">
								<select class="form-control fancy" name="state" id="state" >
									<option value="">Please Select</option>
									<optgroup label="Canadian Provinces">
										<option value="AB">Alberta</option>
										<option value="BC">British Columbia</option>
										<option value="MB">Manitoba</option>
										<option value="NB">New Brunswick</option>
										<option value="NF">Newfoundland</option>
										<option value="NT">Northwest Territories</option>
										<option value="NS">Nova Scotia</option>
										<option value="NVT">Nunavut</option>
										<option value="ON">Ontario</option>
										<option value="PE">Prince Edward Island</option>
										<option value="QC">Quebec</option>
										<option value="SK">Saskatchewan</option>
										<option value="YK">Yukon</option>
									</optgroup>
									<optgroup label="US States">
										<option value="AL">Alabama</option>
										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="BVI">British Virgin Islands</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>
										<option value="DE">Delaware</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="GU">Guam</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option>
										<option value="MP">Mariana Islands</option>
										<option value="MPI">Mariana Islands (Pacific)</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>
										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>
										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="PR">Puerto Rico</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>
										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="USVI">VI  U.S. Virgin Islands</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="DC">Washington, D.C.</option>
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option>
									</optgroup>
									<option value="N/A">Other</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">ZIP/Postal Code:</label>
							<div class="col-sm-7">
								<input class="form-control" name="zip" id="zip" type="text" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Country:</label>
							<div class="col-sm-7">
								<select class="form-control fancy" name="country" id="country" >
									 <option value="">Please Select</option>
									 <option value="US">United States</option>
									 <option value="CA">Canada</option>
								 </select>
							 </div>
						 </div>

						<!-- CREDIT CARD BLOCK -->
						<h2 class="text-center">Credit Card Information</h2>
						<div class="form-group">
							<label class="col-sm-3 control-label"> Credit Card Type:</label>
							<div class="col-sm-7">
								<input name="cctype" type="radio" value="VISA" /> <img src="../assets/images/ico_visa.jpg" alt="visa" />
								<input name="cctype" type="radio" value="MASTERCARD" /> <img src="../assets/images/ico_mc.jpg" alt="mastercard" />
								<input name="cctype" type="radio" value="AMEX" /> <img src="../assets/images/ico_amex.jpg" alt="amex" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Card Number:</label>
							<div class="col-sm-7">
								<input class="form-control" name="ccn" id="ccn" type="text"  value="" maxlength="16" />
								<span class="ccresult"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name on Card:</label>
							<div class="col-sm-7">
								<input class="form-control" name="ccname" id="ccname" type="text"  value="" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Expiration Date:</label>
							<div class="col-sm-3">
								<select name="exp1" id="exp1" class="form-control fancy">
									<option value="">Month</option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
								</select>
							</div>
							<div class="col-sm-3">
								<select name="exp2" id="exp2" class="form-control fancy" >
									<option value="">Year</option>
									<?php echo Functions::getActualYears($cc_yy); ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">CVV:</label>
							<div class="col-sm-2">
								<input class="form-control" name="cvv" id="cvv" type="text" maxlength="5" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-md-offset-3">
								<input type="submit" name="submit" value="Proceed" class="btn btn-success" style="width:100%;"/>
							</div>
						</div>
						<!-- CREDIT CARD BLOCK -->
						<input type="hidden" name="process" value="yes" />
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('body').on('change','#agent', function(e){
		e.preventDefault();
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agent-subscription', 20, 'agent-subscription'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).val() + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});
</script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>
