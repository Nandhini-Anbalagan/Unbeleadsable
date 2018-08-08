<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content p-0 b-0">
			<div class="panel panel-color panel-primary">
				<div class="panel-heading">
					<button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
					<h2 class="panel-title text-center"><?php echo $page == "partial-leads"?$tr['edit_partial_lead']:$tr['edit_address_capture'] ?></h2>
				</div>
				<div class="panel-body">
					<?php if($page == "partial-leads"){ ?>
					<p class="text-center text-danger"><?php echo $tr['edit_lead_body_hint_partial'] ?></p>
					<?php }else{ ?>
					<p class="text-center text-danger"><?php echo $tr['edit_lead_body_hint_address'] ?></p>
					<?php } ?>
					<form class="form-horizontal" role="form" data-parsley-validate="" novalidate>
						<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-agentLead', 20, 'agentLead'); ?>">
						<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-partial-edit', 30, 'partial-edit'); ?>">
						<input type="hidden" name="lead_id" value="">

						<div class="form-group">
							<label for="name" class="col-sm-3 control-label"><?php echo $tr['name'] ?></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" required=""  id="name" name="name" placeholder="Name">
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="col-sm-3 control-label"><?php echo $tr['email'] ?></label>
							<div class="col-sm-9">
								<input type="email" id="email" name="email" class="form-control"  parsley-type="email" data-parsley-length="[<?php echo User::MIN_EMAIL_LENGTH . "," . User::MAX_EMAIL_LENGTH ?>]" placeholder="Enter a valid e-mail">
							</div>
						</div>

						<div class="form-group">
							<label for="phone" class="col-sm-3 control-label"><?php echo $tr['phone'] ?></label>
							<div class="col-sm-9">
								<input type="text" id="phone" name="phone" class="form-control"  data-parsley-length="[10,15]" placeholder="Phone">
							</div>
						</div>
						<div class="form-group">
							<label for="address" class="col-sm-3 control-label"><?php echo $tr['address'] ?></label>
							<div class="col-sm-9">
								<input type="text" id="address" name="address" class="form-control"  placeholder="Address">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3" for="selling"><?php echo $tr['selling_in'] ?></label>
							<div class="col-sm-9"> 

								<select class="form-control input-sm fancy" name="selling" id="selling"> 
									<?php if($_SESSION['user']['agent_lang'] == "EN"){ ?>
									<option value="0">Not Selected</option>
									<option value="1">1-3 months</option>
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
							<label class="control-label col-sm-3" for="lang"><?php echo $tr['language'] ?></label>
							<div class="col-sm-5"> 
								<select class="form-control input-sm fancy" name="lang" id="lang"> 
									<option value="e">English</option>
									<option value="f">Français</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<button type="submit" class="btn btn-success waves-effect waves-light" name="status"><?php echo $tr['save'] ?></button>
								<button type="submit" class="btn btn-danger waves-effect waves-light" name="delete"><?php echo $tr['delete'] ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>