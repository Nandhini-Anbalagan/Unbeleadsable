
<h2 class="page-title pull-left"><?php echo $tr['budget_payment'] ?></h2>
		<button class="btn btn-danger waves-effect waves-light pull-right" data-toggle="collapse" data-target="#newAdCard" aria-expanded="false" aria-controls="newAdCard"><i class="fa fa-plus"></i>&nbsp;<?php echo $tr['new'] ?></button>
		<div class="clearfix"></div>
		<form id="newAdCard" class="form-horizontal collapse m-t-15 well" role="form" data-parsley-validate="" novalidate>
			<h3><?php echo $tr['new_payment'] ?>:</h3>
			<hr>
			<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-payments-add', 30, 'add'); ?>">
			<input type="hidden" name="agent_fk" value="<?php echo $_SESSION['user']['user_id'] ?>">
			<input type="hidden" name="payment" value="2">

			<div class="form-group">
				<div class="col-sm-12">
					<input type="text" class="form-control" required="" data-parsley-length="[5,50]" name="name" placeholder="<?php echo $tr['name_card'] ?>">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<input type="text" class="form-control" required="" name="num" placeholder="<?php echo $tr['card_number'] ?>">
				</div>
				<div class="col-sm-2">
					<input type="number" min="1" max="12" class="form-control" required="" name="mm" placeholder="MM">
				</div>
				<div class="col-sm-2">
					<input type="number" min="<?php echo date("Y") ?>" max="<?php echo date("Y") + 5 ?>" class="form-control" required="" name="year" placeholder="<?php echo $tr['yyyy'] ?>">
				</div>
				<div class="col-sm-2">
					<input type="number" min="0" max="999" class="form-control" required="" name="cvv" placeholder="<?php echo $tr['cvv'] ?>">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="checkbox checkbox-primary">
	                    <input type="checkbox" id="authorize" value="1" name="authorize" required="">
	                    <label for="authorize" class="small"> <?php echo $tr['authorize_card'] ?></label>
	                </div>
	            </div>
            </div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<input type="submit" value="<?php echo $tr['submit'] ?>" class="form-control btn btn-block btn-success" data-parsley-length="[5,50]" name="name_en">
				</div>
			</div>
		</form>

		<form class="form-horizontal m-t-15 alert alert-warning" role="form" data-parsley-validate="" novalidate style="color: #797979">
			<h3><?php echo $tr['subscription_method'] ?> <br> <small><?php echo $tr['subscription_method_sub'] ?></small></h3>
			<hr>
			<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-payments-select', 30, 'select'); ?>">
			<input type="hidden" name="agent_fk" value="<?php echo $_SESSION['user']['user_id'] ?>">
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<label>Current Monthly Advertising Budget:</label>
					<input type="number" class="form-control" name="ad_budget_payment" value="<?php echo $_SESSION['user']['ad_campaign'] ?>" min="150" step="50" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label><?php echo $tr['primary_method'] ?></label>
					<select class="form-control fancy" name="primary">
						<option value="">*** <?php echo $tr['select'] ?> ***</option>
						<?php 
							foreach ($db->getCreditCards(2, $_SESSION['user']['user_id']) as $value) { 
								$dig = explode("-",Functions::decode($value['num']));
								$sel = $value['selected'] == 1?'selected':'';
								echo "<option value='".$value['id']."' $sel>**** **** **** " . $dig[3] . "</option>";
							}
						?>
					</select>
				</div>
				<div class="col-sm-6">
					<label><?php echo $tr['secondary_method'] ?></label>
					<select class="form-control fancy" name="secondary">
						<option value="">*** <?php echo $tr['select'] ?> ***</option>
						<?php 
							foreach ($db->getCreditCards(2,$_SESSION['user']['user_id']) as $value) { 
								$dig = explode("-",Functions::decode($value['num']));
								$sel = $value['selected'] == 2?'selected':'';
								echo "<option value='".$value['id']."' $sel>**** **** **** " . $dig[3] . "</option>";
							}
						?>
					</select>
				</div>
				<p class="text-center"><?php echo $tr['subscription_text'] ?></p>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<input type="submit" value="<?php echo $tr['submit'] ?>" class="form-control btn btn-block btn-success" data-parsley-length="[5,50]" name="name_en">
				</div>
			</div>
		</form>