<div id="convert-lead-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Add New Lead</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo $postActionCalls; ?>">
						<input type="hidden" name="case" value="<?php echo $postCaseCallsConvert; ?>">
						<div class="col-sm-12">
							<div class="form-group row text-center">
								<div class="col-sm-4">
									<div>
										<label class="radio-label">
											Subscriber
											<input type="radio" class="radio-custom" name="buyer" value="buyer">
										</label>
									</div>
								</div>
								<div class="col-sm-4">
									<div>
										<label class="radio-label">
											Sponsor
											<input type="radio" class="radio-custom" name="buyer" value="seller">
										</label>
									</div>
								</div>
								<div class="col-sm-4">
									<div>
										<label class="radio-label">
											Both
											<input type="radio" class="radio-custom" checked name="buyer" value="both">
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group row">
								<label for="inputName1" class="control-label col-sm-4">Name</label>
								<div class="col-sm-8">
									<input class="form-control" type="text" name="name" id="inputName1" placeholder="Name" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail1" class="control-label col-sm-4">Email</label>
								<div class="col-sm-8">
									<input class="form-control" type="email" name="email" id="inputEmail1" placeholder="E-mail" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPhone1" class="control-label col-sm-4">Phone</label>
								<div class="col-sm-8">
									<input class="form-control" type="text" name="phone" id="inputPhone1" placeholder="Phone" required>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group row">
										<label for="inputAreas1" class="control-label col-sm-4">Area</label>
										<div class="col-sm-8">
											<input class="form-control" type="text" name="areas" id="inputAreas1" placeholder="Area" required>
										</div>
									</div>

									<div class="form-group row">
										<label for="state" class="control-label col-sm-4">State</label>
										<div class="col-sm-8">
											<select class="form-control" name="state" id="state" required>
												<option value="" disabled selected> State/Province </option>
												<optgroup label="Canada">
													<option value="Alberta,CA">Alberta</option>
													<option value="British Columbia,CA">British Columbia</option>
													<option value="Manitoba,CA">Manitoba</option>
													<option value="New Brunswick,CA">New Brunswick</option>
													<option value="Newfoundland and Labrador,CA">Newfoundland and Labrador</option>
													<option value="Nova Scotia,CA">Nova Scotia</option>
													<option value="Northwest Territories,CA">Northwest Territories</option>
													<option value="Nunavut,CA">Nunavut</option>
													<option value="Ontario,CA">Ontario</option>
													<option value="Prince Edward Island,CA">Prince Edward Island</option>
													<option value="Quebec,CA">Quebec</option>
													<option value="Saskatchewan,CA">Saskatchewan</option>
													<option value="Yukon,CA">Yukon</option>
												</optgroup>


												<optgroup label="United States">
													<option value="Alabama,US">Alabama</option>
													<option value="Alaska,US">Alaska</option>
													<option value="Arizona,US">Arizona</option>
													<option value="Arkansas,US">Arkansas</option>
													<option value="California,US">California</option>
													<option value="Colorado,US">Colorado</option>
													<option value="Connecticut,US">Connecticut</option>
													<option value="Delaware,US">Delaware</option>
													<option value="District of Columbia,US">District of Columbia</option>
													<option value="Florida,US">Florida</option>
													<option value="Georgia,US">Georgia</option>
													<option value="Hawaii,US">Hawaii</option>
													<option value="Idaho,US">Idaho</option>
													<option value="Illinois,US">Illinois</option>
													<option value="Indiana,US">Indiana</option>
													<option value="Iowa,US">Iowa</option>
													<option value="Kansas,US">Kansas</option>
													<option value="Kentucky,US">Kentucky</option>
													<option value="Louisiana,US">Louisiana</option>
													<option value="Maine,US">Maine</option>
													<option value="Maryland,US">Maryland</option>
													<option value="Massachusetts,US">Massachusetts</option>
													<option value="Michigan,US">Michigan</option>
													<option value="Minnesota,US">Minnesota</option>
													<option value="Mississippi,US">Mississippi</option>
													<option value="Missouri,US">Missouri</option>
													<option value="Montana,US">Montana</option>
													<option value="Nebraska,US">Nebraska</option>
													<option value="Nevada,US">Nevada</option>
													<option value="New Hampshire,US">New Hampshire</option>
													<option value="New Jersey,US">New Jersey</option>
													<option value="New Mexico,US">New Mexico</option>
													<option value="New York,US">New York</option>
													<option value="North Carolina,US">North Carolina</option>
													<option value="North Dakota,US">North Dakota</option>
													<option value="Ohio,US">Ohio</option>
													<option value="Oklahoma,US">Oklahoma</option>
													<option value="Oregon,US">Oregon</option>
													<option value="Pennsylvania,US">Pennsylvania</option>
													<option value="Rhode Island,US">Rhode Island</option>
													<option value="South Carolina,US">South Carolina</option>
													<option value="South Dakota,US">South Dakota</option>
													<option value="Tennessee,US">Tennessee</option>
													<option value="Texas,US">Texas</option>
													<option value="Utah,US">Utah</option>
													<option value="Vermont,US">Vermont</option>
													<option value="Virginia,US">Virginia</option>
													<option value="Washington,US">Washington</option>
													<option value="West Virginia,US">West Virginia</option>
													<option value="Wisconsin,US">Wisconsin</option>
													<option value="Wyoming,US">Wyoming</option>
												</optgroup>

											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group row">
										<label for="inputAgency1" class="control-label col-sm-4">Company</label>
										<div class="col-sm-8">
											<input class="form-control" type="text" name="agency" id="inputAgency1" placeholder="Companyname">
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label for="inputRef1" class="control-label col-sm-4">Reference Code</label>
										<div class="col-sm-8">
											<input class="form-control" type="text" name="ref" id="inputRef1" placeholder="Refence Code (optional)">
										</div>
									</div>
								</div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="lang" class="control-label col-sm-4">Language</label>
                                        <div class="col-sm-8">
                                            <select class="form-control"  name="lang" id="lang" required>
                                                <option value="EN">English</option>
                                                <option value="FR">French</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="col-sm-offset-4 col-sm-8">
							<button type="submit" class="btn btn-primary waves-effect waves-light">Add</button>
							<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5" data-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
