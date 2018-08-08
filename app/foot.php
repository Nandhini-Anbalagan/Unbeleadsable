<!-- Message Modal -->
<div id="feedbackModal" class="modal fade" style="display:none;" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center"><?php echo $tr['having_issue'] ?></h2>
				</div>
				<div class="panel-body">
					<form onsubmit="return validate(this);">
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agent', 20, 'agent'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-agent-feedback', 20, 'feedback'); ?>">
						<div class="form-group">
							<label for="feedback_name"><?php echo $tr['name'] ?></label>
							<input type="text" id="feedback_name" name="name" class="form-control" value="<?php echo isset($_SESSION['user'])?$_SESSION['user']['agent_name']:'' ?>" placeholder="John Doe"  readonly />
						</div>
						<div class="form-group">
							<label for="feedback_email"><?php echo $tr['email'] ?></label>
							<input type="email" id="feedback_email" name="email" class="form-control" value="<?php echo isset($_SESSION['user'])?$_SESSION['user']['agent_email']:'' ?>" placeholder="email@email.com"  readonly />
						</div>
						<div class="form-group">
							<label for="feedback_subject"><?php echo $tr['subject'] ?></label>
							<input type="text" id="feedback_subject" name="subject" class="form-control" />
						</div>
						<div class="form-group">
							<label for="feedback_message"><?php echo $tr['message'] ?></label>
							<textarea id="feedback_message" name="message" class="form-control" placeholder="Your message..." rows="5" required></textarea>
						</div>
						<div class="form-group text-right submit-group">
							<button class="btn btn-primary waves-effect waves-light"><?php echo $tr['send_message'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- New Area Modal -->
<div id="requestNewArea" class="modal fade" style="display:none;" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-success">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center"><?php echo $tr['request_new_area'] ?></h2>
				</div>
				<div class="panel-body">
					<form onsubmit="return validate(this);">
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-agent-requestNewArea', 20, 'requestNewArea'); ?>">
						<input type="hidden" name="buyer_option" value="sellers">
						<div class="form-group">
							<label for="desiredArea"><?php echo $tr['desire_area'] ?></label>
							<input type="text" id="desiredArea" name="desiredArea" class="form-control" />
						</div>
						<div class="form-group">
							<label for="desiredState"><?php echo $tr['desire_state'] ?></label>
							<!--<input type="text" id="desiredState" name="desiredState" class="form-control" />-->
							<select class="form-control" id="desiredState" name="desiredState" required>
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
						<!-- <div class="form-group row text-center">
							<div class="col-xs-4">
								<div>
									<label class="radio-label">
										Buyer
										<input class="radio-custom" type="radio" name="buyer" value="buyer">
									</label>
								</div>
							</div>
							<div class="col-xs-4">
								<div>
									<label class="radio-label">
										Seller
										<input class="radio-custom" type="radio" name="buyer" value="seller">
									</label>
								</div>
							</div>
							<div class="col-xs-4">
								<div>
									<label class="radio-label">
										Both
										<input class="radio-custom" type="radio" checked name="buyer" value="both">
									</label>
								</div>
							</div>
						</div> -->

						<div class="form-group text-right submit-group">
							<button class="btn btn-success waves-effect waves-light"><?php echo $tr['submit_request'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
		<?php require_once('load/misc/cookie-success-message.php'); ?>
		<?php require_once('load/misc/cookie-error-message.php'); ?>

		<!-- Bootstrap Core JavaScript -->
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- jQuery -->
		<script src="assets/js/detect.js"></script>
		<script src="assets/js/fastclick.js"></script>
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/plugins/raphael/raphael-min.js"></script>
		<script src="assets/js/toastr.min.js"></script>
		<script src="assets/js/waves.js"></script>
		<script src="assets/js/jquery.slimscroll.js"></script>
		<script src="assets/js/jquery.nicescroll.js"></script>
		<script src="assets/plugins/peity/jquery.peity.min.js"></script>

		<script src="assets/plugins/switchery/dist/switchery.min.js"></script>

		<script src="assets/js/jquery.app.js"></script>
		<script src="assets/js/jquery.core.js?v=<?php echo time() ?>"></script>

		<!-- Core -->
		<script src="assets/js/core.js?v=<?php echo time() ?>"></script>

		<!-- Parsleyjs -->
		<script type="text/javascript" src="assets/js/parsley.min.js"></script>

		<!-- Sweet-Alert  -->
		<script src="assets/plugins/sweetalert/dist/sweetalert.min.js"></script>

		<!-- Select2 -->
		<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>

		<!-- Bootstrap Input Mask -->
		<script src="assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>

		<!-- Auto Numeric -->
		<script src="assets/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>
		<script src="assets/plugins/tinymce/tinymce.min.js"></script>

	<?php if(in_array($page, $datatablePages)){ ?>
		<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
		<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
		<script src="assets/plugins/datatables/jszip.min.js"></script>
		<script src="assets/plugins/datatables/pdfmake.min.js"></script>
		<script src="assets/plugins/datatables/vfs_fonts.js"></script>
		<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
		<script src="assets/plugins/datatables/buttons.print.min.js"></script>
		<script src="assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>

		<script src="assets/plugins/moment/moment.js"></script>
		<?php if(isset($agent) && $agent['agent_lang'] == "FR"){ ?>
		<script src="assets/plugins/moment/moment.fr.js"></script>
		<?php } ?>
		<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<?php }else if($page == "agent_lead"){ ?>
		<script src="assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
		<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<?php } ?>
		<script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
		<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
		<script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

<!--		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDSOaCXDTQy_VXFflgZg19OwFqLIUmZ1eM&amp;libraries=places&language=en&sensor=false"></script>-->
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyB8hEM4oF88dSUvW3MidSqSlbDf4oxwRXI&amp;libraries=places&language=en&sensor=false"></script>
		<script>
			google.maps.event.addDomListener(window, 'load', function(){
				var input = document.getElementById('address');
				var autocomplete = new google.maps.places.Autocomplete(input);
			});
		</script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-45266806-12', 'auto');
		  ga('send', 'pageview');

		</script>

		<?php echo (isset($_SESSION['admin']) && isset($_SESSION['user']))?"<script>console.log(".json_encode($_SESSION['user']).")</script>":'';?>
	</body>
</html>