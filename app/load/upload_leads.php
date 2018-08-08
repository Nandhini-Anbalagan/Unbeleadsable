<?php
	# Tokenizer container
	$postActionAgentLead = Tokenizer::add('post-action-agentLead', 20, 'agentLead');
	$postFile = Tokenizer::add('post-case-file', 30, 'file');
?>

<h2 class="page-title"><?php echo $tr['upload_leads'] ?> <br> <small><?php echo $tr['upload_file'] ?></small></h2>
<div class="clearfix"></div>
<form class="form-horizontal" id="uploadForm" role="form" class="p-t-20" onsubmit="return validate(this);">
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-upload_leads', 20, 'upload_leads'); ?>">
	<input type="hidden" name="id" value="<?php echo $_SESSION['user']['agent_id'] ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<ul class="list-unstyled" style="padding-left: 10px; margin-top: 10px">
					<li><?php echo $tr['upload_leads_1'] ?></li>
					<li><?php echo $tr['upload_leads_2'] ?></li>
					<li><?php echo $tr['upload_leads_3'] ?></li>
					<li><?php echo $tr['upload_leads_4'] ?></li>
				</ul>
			</div>
			<div class="form-group">
				<input type="file" class="filestyle" data-buttonbefore="true" data-buttonText="<?php echo $_SESSION['user']['agent_lang'] == 'EN'?'Choose CSV file to upload':'Choisissez le fichier CSV à importer' ?>">
				<input type="hidden" name="uploadedFile">
			</div>

			<div id="mapping"></div>
			<div id="imported" style="min-width: 800px;"></div>

		</div>
	</div>
</form>

<script>
	$(document).ready(function(){
		$('#imported').niceScroll({
			cursorwidth:"5px",
			touchbehavior: true,
			preventmultitouchscrolling: false, 
		});

		$(':file').change(function(){
			var file = this.files[0],
			type = file.type,
			types = ["application/vnd.ms-excel"],
			formData = new FormData();

			formData.append("action", '<?php echo $postActionAgentLead; ?>');
			formData.append("case", '<?php echo $postFile; ?>');
			formData.append("file", file);

			if(types.indexOf(type) != -1){
				$.ajax({
					url: 'core.php',
					type: 'POST',
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						$('input[name="uploadedFile"]').val(response);
						$("#mapping").load("load/importMap.php?file="+response);
					}
				});
			}else{
				$(this).val("");
				generateNotification("Sorry file type not valid!", "bottom-right", "error", 3000, true)
			}
		});

		$("body").on("click", "#validateMap", function(e){
			e.preventDefault();
			$.post('load/imported.php', $("#uploadForm").serialize())
				.success(function(result){
					$("#mapping").hide();
					$("#imported").html(result);
				})
				.error(function(){
					console.log('Error loading page');
				});
			return false;
		});
	});
</script>

<script src="assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>