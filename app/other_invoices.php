<?php
require_once('header.php');
require_once('load/top-menu.php');
require_once('load/misc/dynamic-form.php');
?>

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card-box table-responsive">
					<div class="row">
						<div class="col-md-12 m-b-20">
							<h2 class="page-title pull-left">Any Other Invoices</h2>
							 <a class="btn btn-danger waves-effect waves-light pull-right m-r-5" data-toggle="collapse" data-target="#advanceSearch">Advance Search <i class="fa fa-search"></i></a>
							<?php if($_SESSION['user']['level'] > 50){ ?>
							<div class="dropdown pull-right m-r-5">
								<button type="button" class="dropdown-toggle btn btn-inverse waves-effect waves-light" data-toggle="dropdown" ><i class="fa fa-usd"></i> &nbsp;Any Payments
								<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="any_payment">Make a Payment</a></li>
									<li><a href="other_invoices">View Invoices</a></li>
								</ul>
							</div>
							<?php } ?>

						</div>
					</div>
					<div class="row">
						<div class="col-md-12 m-b-20">
							<div class="well collapse" id="advanceSearch">
								<h3>Advance Search</h3>
								<form role="form" id="searchInvoice">
									<div class="form-group col-sm-3">
										<label>Month</label>
										<select class="form-control" name="month">
											<option value="-1" style="display: none;">All Months</option>
											<?php foreach(Functions::monthArray() as $key => $month){ ?>
											<option value="<?php echo $key; ?>"><?php echo $month; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label>Year</label><br>
										<select class="form-control" name="year">
											<option value="-1" style="display: none;">All Years</option>
											<?php for($i=0; $i<=(date("Y") - 2016); $i++){ ?>
											<option value="<?php echo 2016 + $i; ?>"><?php echo 2016 + $i; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-sm-4">
										<label>Date Range</label>
										<input class="form-control input-daterange-datepicker" type="text" name="daterange" value="All Dates">
									</div>
									<div class="form-group col-sm-2">
										<label>&nbsp;</label><br>
										<button type="submit" class="btn btn-block btn-danger waves-effect waves-light" name="search">Search</button>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 m-b-20">
							<div id="invoices-wrapper"><?php require_once('load/other_invoices.php'); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(function(){
				$('select').select2();
				//Date range picker
				$('.input-daterange-datepicker').daterangepicker({
					autoUpdateInput: false,
					opens: "left",
					drops: "down",
					showDropdowns: true,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					buttonClasses: ['btn', 'btn-sm'],
					applyClass: 'btn-primary',
					cancelClass: 'btn-danger'
				});

				$('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
					$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
				});

				$('.input-daterange-datepicker').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('All Dates');
				});


				$('#searchInvoice').submit(function(e){
					e.preventDefault();
					$('#invoices-wrapper').load("load/other_invoices.php?search&"+$(this).serialize());
					return false;
				});
			});
		</script>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>