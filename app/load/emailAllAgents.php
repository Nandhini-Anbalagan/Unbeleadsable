
<div id="allEmaillModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center">Email All Agents</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-email', 20, 'email'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-emailAllAgent', 30, 'emailAllAgent'); ?>">
						<div class="form-group">
							<label for="to" class="col-lg-2 control-label">To</label>
							<div class="col-lg-10">
								<div class="tags-default">
									<input type="text" name="to" value="<?php echo $db->getAllAgentEmails()['emails'] ?>" data-role="tagsinput"/>
								</div>
								<!-- <textarea class="form-control"><?php echo $emails['emails'] ?></textarea> -->
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="col-lg-2 control-label"> Content</label>
							<div class="col-lg-10">
								<textarea id="compose-textarea2" name="content" class="form-control" placeholder="Content..."></textarea>
								[agent_name] for agent_name
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-primary waves-effect waves-light">Send</button>
								<button type="reset" class="btn btn-danger waves-effect waves-light m-l-5 cancel" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
	
		tinymce.init({
			selector: "#compose-textarea2",
			theme: "modern",
			height:200,
			menubar: false,
			plugins: [
				'advlist autolink lists link charmap anchor',
				'visualblocks paste jbimages textcolor jbimages'
			],
			toolbar: "fontsizeselect forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages"
		});
	});
</script>