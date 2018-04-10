<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="page-title pull-left m-b-20"><?php echo $tr['partial_leads'] ?> : <small id="smallRange"><?php echo $tr['all'] ?></small></h4>

				<div class="dropdown pull-right">
					<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="fa fa-plus"></span> Actions <span class="caret"></span></button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="javascript:void(0)" id="delete"><span class="fa fa-trash"></span> <?php echo $tr['delete'] ?></a></li>
						<li><a href="javascript:void(0)" id="sendEmail"><span class="fa fa-send"></span> <?php echo $tr['send_email'] ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="print/partial/single" target="_blank"><span class="fa fa-file-pdf-o"></span> <?php echo $tr['print_list'] ?></a></li>
						<li><a href="export/partial"><span class="fa fa-file-excel-o"></span> <?php echo $tr['download_list'] ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="print/partial/all" target="_blank"><span class="fa fa-file-text"></span> <?php echo $tr['print_all'] ?></a></li>
						<li><a href="export/all"><span class="fa fa-file"></span> <?php echo $tr['download_all'] ?></a></li>
					</ul>
				</div>

				<a id="daterange" class="pull-right btn btn-success" style="margin-right: 10px;"><span class="fa fa-filter"></span> <?php echo $tr['filter'] ?></a>

				<div class="clearfix"></div>
				<div class="card-box">
					<div class="row">
						<div class="col-md-12">
							<div id="partial-leads-wrapper">
								<?php
									if($_SESSION['user']['agent_slug'] == "home_sellers")
										require_once('load/partial-leads.php');
									else if($_SESSION['user']['agent_slug'] == "home_buyers")
										require_once('load/partial-leads-buyers.php');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('load/compose-email.php'); ?>

<script>
	 $(document).ready(function(){

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
			$('#partial-leads-wrapper').load("load/partial-leads.php?range="+encodeURI(range), function(){
				initDataTable();
			});
			return false;
		});

		$('#daterange').on('cancel.daterangepicker', function(ev, picker) {
			$("#smallRange").text("<?php echo $tr['all'] ?>");
			$('#partial-leads-wrapper').load("load/partial-leads.php", function(){
				initDataTable();
			});
		});
	});

	$('body').on('change', '#selectAll', function(e){
		var table= $(e.target).closest('table');
		$('input:checkbox',table).prop('checked',this.checked);
	});

	$('body').on('click','.selling li a', function(e){
		e.preventDefault(); 
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
			+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-selling', 20, 'selling'); ?>">'
			+ '<input type="hidden" name="id" value="' + $(this).data('id') + '">'
			+ '<input type="hidden" name="text" value="' + $(this).data('value') + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});

	$('body').on('click', '#delete', function(e){
		var ids = "";
		$('input:checkbox',"#partial_datatable").each(function () {
			if(this.checked && $(this).val() != "on")
				ids += $(this).val() + ",";
		});
		ids = ids.substring(0, ids.length - 1);
		console.log("ids: ", ids);
		if(ids != ""){
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover these leads!",
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
		var emails = "";
		$('input:checkbox',"#partial_datatable").each(function () {
			if(this.checked && $(this).val() != "on" && $(this).parents('tr').find('td:nth-child(3) a').html() != "" && emails.indexOf($(this).parents('tr').find('td:nth-child(3) a').html()) === -1)
				emails += $(this).parents('tr').find('td:nth-child(3) a span.email').html() + ",";
			
		});
		
		emails = emails.substring(0, emails.length - 1);
		
		if(emails != ""){
			$('#compose-modal').modal('show');
			$('[name="to"]').val(emails);
		}else
			generateNotification('Please select at least one lead.', 'bottom-right', 'error', 5000, true);
	});

	function initDataTable(){
		$('#partial_datatable').dataTable({
			<?php if($agent['agent_lang'] == "FR"){ ?>
			"language": {
				"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
			},
			<?php } ?>
			"aoColumnDefs": [{
			  "bSortable": false, 
			  "aTargets": [0,1,3,4,6]
			}],
			"aaSorting": [],
			"bStateSave": true
		});
	}
	
</script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>