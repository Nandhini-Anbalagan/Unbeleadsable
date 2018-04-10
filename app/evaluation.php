<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>
<?php $evals = $db->getEvaluations($_SESSION['user']['agent_id']); ?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="page-title pull-left m-b-20"><?php echo $tr['evaluation_form'] ?></h4>
				
				<?php if(COUNT($evals) > 0): ?>
				<a href="evaluation/print/all" target="_blank" class="btn btn-danger waves-effect waves-light pull-right"><i class="fa fa-file-text"></i>&nbsp;<?php echo $tr['print_all_evaluations'] ?></a>
				<?php endif; ?>
				<div class="clearfix"></div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="card-box">
							<!-- <h3><?php echo $tr['info'] ?></h3> -->
							<form id="evaluation" class="form-horizontal" role="form" data-parsley-validate="" novalidate>
								<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
								<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-email-evaluation', 30, 'email-evaluation'); ?>">
								<input type="hidden" name="lead">
								<div class="form-group">
									<label><?php echo $tr['archieved_evaluations'] ?></label>
									<select id="archieve" class="form-control fancy">
										<option value=""><?php echo $tr['select_archieved_evaluation'] ?></option>
										<?php foreach ($evals as $l) { ?>
										<option value="<?php echo IDObfuscator::encode($l['id_e']) ?>"><?php echo $l['name'] . " - " . $l['email'] ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="form-group">
									<label><?php echo $tr['create_new_evaluation'] ?></label>
									<select id="new" class="form-control fancy">
										<option value=""><?php echo $tr['select_completed_lead'] ?></option>
										<?php foreach ($db->getAgentsLead($_SESSION['user']['agent_id'], $_SESSION['user']['agent_slug']) as $l) { ?>
										<option value="<?php echo $l['id'] ?>"><?php echo $l['name'] . " - " . $l['email'] ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label><?php echo $tr['low_value'] ?>:</label>
									<input type="number" name="low" class="form-control" required>
								</div>
								<div class="form-group">
									<label><?php echo $tr['high_value'] ?>:</label>
									<input type="number" name="high" class="form-control" required>
								</div>
								<div class="form-group">
									<label><?php echo $tr['muni_value'] ?>:</label>
									<input type="number" name="muni" class="form-control" required>
								</div>
								<div class="form-group">
									<label><?php echo $tr['comments'] ?>:</label>
									<textarea class="form-control" name="comments" style="height: 100px; resize: vertical"></textarea>
								</div>
								<button id="preview" class="btn btn-primary"><?php echo $tr['preview'] ?></button>
								<button value="submit" class="btn btn-success"><?php echo $tr['send'] ?></button>
								<a class="btn btn-default" target="_blank" id="printSingle"><?php echo $tr['print'] ?></a>
							</form>
							
						</div>
					</div>
					<div class="col-md-8">
						<div class="card-box">
							<h3><?php echo $tr['preview'] ?></h3>
							<div id="preview-wrapper"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>

$('body').on('click','#preview', function(e){
	e.preventDefault(); 
	$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
		+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-agentLead-evaluation-preview', 20, 'evaluation-preview'); ?>">'
		+ '<input type="hidden" name="id" value="' + $("input[name='lead']").val() + '">'
		+ '<input type="hidden" name="low" value="' + $("input[name='low']").val() + '">'
		+ '<input type="hidden" name="high" value="' + $("input[name='high']").val() + '">'
		+ '<input type="hidden" name="muni" value="' + $("input[name='muni']").val() + '">'
		+ '<input type="hidden" name="comments" value="' + $("textarea[name='comments']").val() + '">');
	$('#<?php echo $dynamicFormId; ?>').submit();
	$('#<?php echo $dynamicFormId; ?>').empty();
});

$('body').on('change','#archieve', function(e){
	e.preventDefault(); 
	$('#new').select2("val", "");
	$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">'
		+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-evaluation-archieve', 20, 'evaluation-archieve'); ?>">'
		+ '<input type="hidden" name="id" value="' + $(this).val() + '">');
	$('#<?php echo $dynamicFormId; ?>').submit();
	$('#<?php echo $dynamicFormId; ?>').empty();
	$("#printSingle").attr("href", "/app/evaluation/print/"+$(this).val())
});

$('body').on('change','#new', function(e){
	e.preventDefault(); 
	var form = $('#evaluation');
	form.find('input[name="lead"]').val($(this).val());
	form.find('input[name="low"]').val("");
	form.find('input[name="high"]').val("");
	form.find('input[name="muni"]').val("");
	form.find('textarea[name="comments"]').val("");
	$('#archieve').select2("val", "");
});

</script>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>