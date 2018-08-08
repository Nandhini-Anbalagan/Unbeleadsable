<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="page-title pull-left m-b-20"><?php echo $tr['completed_leads'] ?> : <small id="smallRange"><?php echo $tr['all'] ?></small></h4>

				<div class="dropdown pull-right">
					<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="fa fa-plus"></span> Actions <span class="caret"></span></button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="javascript:void(0)" id="delete"><span class="fa fa-trash"></span> <?php echo $tr['delete'] ?></a></li>
						<li><a href="javascript:void(0)" id="sendEmail"><span class="fa fa-send"></span> <?php echo $tr['send_email'] ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="print/completed/single" target="_blank"><span class="fa fa-file-pdf-o"></span> <?php echo $tr['print_list'] ?></a></li>
						<li><a href="export/completed"><span class="fa fa-file-excel-o"></span> <?php echo $tr['download_list'] ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="print/completed/all" target="_blank"><span class="fa fa-file-text"></span> <?php echo $tr['print_all'] ?></a></li>
						<li><a href="export/all"><span class="fa fa-file"></span> <?php echo $tr['download_all'] ?></a></li>
					</ul>
				</div>
				<a id="daterange" class="pull-right btn btn-success" style="margin-right: 10px;"><span class="fa fa-filter"></span> <?php echo $tr['filter'] ?></a>
				<?php if($_SESSION['user']['agent_slug'] == "home_sellers"): ?>
				<a class="pull-right btn btn-warning" data-toggle="modal" data-target="#add-new-lead-modal" style="margin-right: 10px;"><span class="fa fa-plus"></span> <?php echo $tr['add_new_lead'] ?></a>
				<?php endif; ?>
				<a href="archived-leads" class="pull-right btn btn-primary" style="margin-right: 10px;"><span class="fa fa-clock-o"></span> Archives</a>
				<div class="clearfix"></div>
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="completed-leads-wrapper">
								<?php
									if($_SESSION['user']['agent_slug'] == "home_sellers")
										require_once('load/completed-leads.php');
									else
										require_once('load/completed-leads-buyers.php');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="add-new-lead-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center"><?php echo $tr['add_new_lead'] ?></h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-manual-add', 30, 'manual-add'); ?>">
						<input type="hidden" name="agent_id" value="<?php echo $_SESSION['user']['agent_id'] ?>">
						<input type="hidden" name="type" value="<?php echo $_SESSION['user']['agent_slug'] ?>">
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label"><?php echo $tr['name'] ?></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" required=""  id="name" name="name">
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="col-sm-3 control-label"><?php echo $tr['email'] ?></label>
							<div class="col-sm-9">
								<input type="email" id="email" name="email" required="" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label for="phone" class="col-sm-3 control-label"><?php echo $tr['phone'] ?></label>
							<div class="col-sm-9">
								<input type="text" id="phone" name="phone" required="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-3 control-label"><?php echo $tr['address'] ?></label>
							<div class="col-sm-9">
								<input type="text" id="address" name="address" required="" class="form-control" >
							</div>
						</div>

						<div class="form-group">
							<label for="notes" class="col-sm-3 control-label"><?php echo $tr['notes'] ?></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label for="selling" class="col-sm-3 control-label"><?php echo $tr['selling_in'] ?></label>
							<div class="col-sm-5">
								<select class="form-control fancy" name="selling" id="selling" >
									<?php if($_SESSION['user']['agent_lang'] == "EN"){ ?>
									<option value="0">Not Selected</option>
									<option value="1"></option>
									<option value="2">3-6 months</option>
									<option value="3">6-12 months</option>
									<option value="4">12+ months</option>
									<option value="5">Just curious</option>
									<option value="6">Refinancing</option>
									<?php }else{ ?>
									<option value="0">Non séléctionné</option>
									<option value="1">1-3 Mois</option>
									<option value="2">3-6 Mois</option>
									<option value="3">6-12 Mois</option>
									<option value="4">12+ Mois</option>
									<option value="5">Par Curiosité</option>
									<option value="6">Refinancement</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-3" for="status"><?php echo $tr['status'] ?></label>
							<div class="col-sm-5">
								<select class="form-control fancy" name="status" id="status">
									<?php
										foreach ($status as $k => $s){
											echo '<option value="'.$s['id'].'">'.($_SESSION['user']['agent_lang'] == "EN"?$s['name_en']:$s['name_fr']).'</option>';
										}
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-3" for="type"><?php echo $tr['type'] ?></label>
							<div class="col-sm-5">
								<select class="form-control fancy" name="type" id="type">
									<option><?php echo $tr['seller'] ?></option>
									<option><?php echo $tr['buyer'] ?></option>
									<option><?php echo $tr['buyer_seller'] ?></option>
									<option><?php echo $tr['reft'] ?></option>
									<option><?php echo $tr['rental'] ?></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-3" for="lang"><?php echo $tr['language'] ?></label>
							<div class="col-sm-5">
								<select class="form-control fancy" name="lang" id="lang">
									<option value="e">English</option>
									<option value="f">Français</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12 text-center">
								<button type="submit" class="btn btn-success waves-effect waves-light" name="status">Save</button>
								<button data-dismiss="modal" aria-hidden="true" class="btn btn-danger waves-effect waves-light" name="delete">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('load/compose-email.php'); ?>

<script>
	$(document).ready(function(){
		$('select').select2();

		$('body').on('click','.selling li a', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-selling', 20, 'selling'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="text" value="' + $(this).data('value') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('click','.selectFunnel li a:not(.notMe)', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-selectFunnel', 20, 'selectFunnel'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="funnel" value="' + $(this).data('value') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('click','.status li a', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-status', 20, 'status'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('lead') + '">'
				+ '<input type="hidden" name="status" value="' + $(this).data('status') + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('click','.type li a', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-type', 20, 'type'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="text" value="' + $(this).text() + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		 $('body').on('change','.funnel', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-funnel', 20, 'funnel'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="switch" value="' + $(this).prop("checked") + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('blur', 'textarea[name="comments"]',function() {
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-comments', 20, 'comments'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
				+ '<input type="hidden" name="comments" value="' + $(this).val() + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('change', '#selectAll', function(e){
			var table= $(e.target).closest('table');
			$('input:checkbox',table).prop('checked',this.checked);
		});

		$('body').on('click', '#delete', function(e){
			var ids = "";
			$('input:checkbox',"#leads_datatable").each(function () {
				if(this.checked && $(this).val() != "on")
					ids += $(this).val() + ",";
			});
			ids = ids.substring(0, ids.length - 1);

			if(ids != ""){
				swal({
					title: "Are you sure?",
					text: "You want to delete this lead?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, delete it!",
					closeOnConfirm: false
				}, function(){
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
						'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
						+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-delete-bulk', 20, 'delete-bulk'); ?>">'
						+ '<input type="hidden" name="ids" value="' + ids + '">');
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
				});
			}else
				generateNotification('Please select at least one lead.', 'bottom-right', 'error', 5000, true);
		});

		$('body').on('click', '#sendEmail', function(e){
			e.preventDefault();
			var emails = "", ids = "";
			$('input:checkbox',"#leads_datatable").each(function () {
				if(this.checked && $(this).val() != "on" && $(this).parents('tr').find('td:nth-child(3) a').html() != "" && emails.indexOf($(this).parents('tr').find('td:nth-child(3) a').html()) === -1){
					emails += $(this).parents('tr').find('td:nth-child(3) a span.email').html() + ",";
					ids += $(this).val()+ ",";
				}
			});

			emails = emails.substring(0, emails.length - 1);
			ids = ids.substring(0, ids.length - 1);

			if(emails != ""){
				$('#compose-modal').modal('show');
				$('[name="to"]').val(emails);
				$('[name="ids"]').val(ids);
			}else
				generateNotification('Please select at least one lead.', 'bottom-right', 'error', 5000, true);
		});

		$('#daterange').daterangepicker({
			autoUpdateInput: false,
			opens: "left",
			drops: "down",
			maxDate: moment(),
			showDropdowns: true,
			ranges: {
				"<?php echo $tr['today'] ?>": [moment(), moment()],
				"<?php echo $tr['yesterday'] ?>": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				"<?php echo $tr['sevenDays'] ?>": [moment().subtract(6, 'days'), moment()],
				"<?php echo $tr['thirtyDays'] ?>": [moment().subtract(29, 'days'), moment()],
				"<?php echo $tr['thisMonth'] ?>": [moment().startOf('month'), moment().endOf('month')],
				"<?php echo $tr['lastMonth'] ?>": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-primary',
			cancelClass: 'btn-danger',
			<?php if($agent['agent_lang'] == "FR"){ ?>
			local: 'fr',
			locale: {
				cancelLabel: 'Annuler',
				applyLabel: 'Lancer',
				customRangeLabel: "Personnalisée"

			}
			<?php } ?>
		});

		$('#daterange').on('apply.daterangepicker', function(ev, picker) {
			var sDate = picker.startDate.format('YYYY-MM-DD'), eDate = picker.endDate.format('YYYY-MM-DD'), range = "";

			if(sDate != eDate)
				range = sDate + ' - ' + eDate;
			else
				range = sDate;

			$("#smallRange").text(picker.chosenLabel + " (" + range +")");
			$('#completed-leads-wrapper').load("load/completed-leads.php?range="+encodeURI(range));
			return false;
		});

		$('#daterange').on('cancel.daterangepicker', function(ev, picker) {
			$("#smallRange").text("<?php echo $tr['all'] ?>");
			$('#completed-leads-wrapper').load("load/completed-leads.php");
		});
	});
</script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>