<?php isset($_SESSION['teammate'])?die("Access Denied"):'' ?>
<h2 class="page-title pull-left"><?php echo $tr['subscription_payment'] ?></h2>
<button class="btn btn-danger waves-effect waves-light pull-right" data-toggle="collapse" data-target="#newSubscriptionCard" aria-expanded="false" aria-controls="newSubscriptionCard"><i class="fa fa-plus"></i>&nbsp;<?php echo $tr['new'] ?></button>
<button class="btn btn-info waves-effect waves-light pull-right" style="margin-right: 10px;" data-toggle="collapse" data-target="#updateSubscriptionCard" aria-expanded="false" aria-controls="newSubscriptionCard"><i class="fa fa-refresh"></i>&nbsp;<?php echo $tr['update'] ?></button>
<div class="clearfix"></div>
<form id="newSubscriptionCard" class="form-horizontal collapse m-t-15 well" role="form" data-parsley-validate="" novalidate>
	<h3><?php echo $tr['new_payment'] ?>:</h3>
	<hr>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-payments-add', 30, 'add'); ?>">
	<input type="hidden" name="agent_fk" value="<?php echo $_SESSION['user']['agent_id'] ?>">
	<input type="hidden" name="payment" value="1">

	<div class="form-group">
		<div class="col-sm-9">
			<input type="text" class="form-control" required="" data-parsley-length="[5,50]" name="name" placeholder="<?php echo $tr['name_card'] ?>">
		</div>
		<div class="col-sm-3">
			<select class="form-control fancy" name="type" required="">
				<option value="">Type</option>
				<option value="VISA">VISA</option>
				<option value="MASTERCARD">MASTERCARD</option>
				<!-- <option value="DINERS CLUB">DINERS CLUB</option>
				<option value="DISCOVER">DISCOVER</option>
				<option value="AMEX">AMEX</option> -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-6">
			<input type="text" class="form-control" required="" name="num" placeholder="<?php echo $tr['card_number'] ?>">
		</div>
		<div class="col-sm-2">
			<select class="form-control fancy" name="mm" required="">
				<option value="">MM</option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
		</div>
		<div class="col-sm-2">
			<select class="form-control fancy" required="" name="year">
				<option value=""><?php echo $tr['yyyy'] ?></option>
				<?php
					for($i=date("Y");$i<date("Y", strtotime(date("Y")." +10 years"));$i++){
						$selected = $i == $v?'selected':'';
						echo'<option value="'.$i.'"'. $selected .'>'.$i.'</option>';
					}
				?>
			</select>
		</div>
		<div class="col-sm-2">
			<input type="number" min="0" max="9999" class="form-control" required="" name="cvv" placeholder="<?php echo $tr['cvv'] ?>">
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

<form id="updateSubscriptionCard" class="form-horizontal collapse m-t-15 well" role="form" data-parsley-validate="" novalidate>
	<h3><?php echo $tr['update_payment'] ?>:</h3>
	<hr>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-payments-update', 30, 'update'); ?>">

	<div class="form-group">
		<div class="col-sm-3">
			<select class="form-control fancy" name="card" required="">
				<option value="">*** <?php echo $tr['select'] ?> ***</option>
				<?php
				foreach ($db->getCreditCards(1, $_SESSION['user']['agent_id']) as $value) {
					$dig = substr(str_replace(array("-", " "), "", Functions::decode($value['num'])), -4);
					echo "<option value='".$value['id']."'>**** **** **** " . $dig . "</option>";
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-9">
			<input type="text" class="form-control" required="" data-parsley-length="[5,50]" name="name" placeholder="<?php echo $tr['name_card'] ?>">
		</div>
		<div class="col-sm-3">
			<select class="form-control fancy" name="type" required="">
				<option value="">Type</option>
				<option value="VISA">VISA</option>
				<option value="MASTERCARD">MASTERCARD</option>
				<!-- <option value="DINERS CLUB">DINERS CLUB</option>
				<option value="DISCOVER">DISCOVER</option>
				<option value="AMEX">AMEX</option> -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-6">
			<input type="text" class="form-control" required="" name="num" placeholder="<?php echo $tr['card_number'] ?>">
		</div>
		<div class="col-sm-2">
			<select class="form-control fancy" name="mm" required="">
				<option value="">MM</option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
		</div>
		<div class="col-sm-2">
			<select class="form-control fancy" required="" name="year">
				<option value=""><?php echo $tr['yyyy'] ?></option>
				<?php
					for($i=date("Y");$i<date("Y", strtotime(date("Y")." +10 years"));$i++){
						$selected = $i == $v?'selected':'';
						echo'<option value="'.$i.'"'. $selected .'>'.$i.'</option>';
					}
				?>
			</select>
		</div>
		<div class="col-sm-2">
			<input type="number" min="100" max="9999" class="form-control" required="" name="cvv" placeholder="<?php echo $tr['cvv'] ?>">
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
		<div class="col-sm-3 col-sm-offset-3">
			<input type="submit" value="<?php echo $tr['submit'] ?>" class="form-control btn btn-block btn-success" name="save">
		</div>
		<div class="col-sm-3">
			<input type="submit" id="delete" value="<?php echo $tr['delete'] ?>" class="form-control btn btn-block btn-danger" data-id="" name="delete">
		</div>
	</div>
</form>

<form class="form-horizontal m-t-15 alert alert-warning" role="form" data-parsley-validate="" novalidate style="color: #797979">
	<h3><?php echo $tr['subscription_method'] ?> <br> <small><?php echo $tr['subscription_method_sub'] ?></small></h3>
	<hr>
	<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">
	<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-action-payments-select', 30, 'select'); ?>">
	<input type="hidden" name="agent_fk" value="<?php echo $_SESSION['user']['agent_id'] ?>">

	<div class="form-group">
		<div class="col-sm-6">
			<label><?php echo $tr['primary_method'] ?></label>
			<select class="form-control fancy" name="primary" required="">
				<option value="">*** <?php echo $tr['select'] ?> ***</option>
				<?php
				foreach ($db->getCreditCards(1, $_SESSION['user']['agent_id']) as $value) {
					$dig = substr(str_replace(array("-", " "), "", Functions::decode($value['num'])), -4);
					$sel = $value['selected'] == 1?'selected':'';
					echo "<option value='".$value['id']."' $sel>**** **** **** " . $dig . "</option>";
				}
				?>
			</select>
		</div>
		<div class="col-sm-4">
			<label><?php echo $tr['current_ad_budget'] ?></label>
			<input type="number" class="form-control" name="ad_budget_payment" value="<?php echo $_SESSION['user']['ad_campaign'] ?>" min="0" step="50" placeholder="">
		</div>
		<div class="col-sm-12">
			<p class="text-center"><?php echo $tr['subscription_text'] ?></p>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-6 col-sm-offset-3">
			<input type="submit" value="<?php echo $tr['submit'] ?>" class="form-control btn btn-block btn-success" data-parsley-length="[5,50]" name="name_en">
		</div>
	</div>
</form>

<script>
	$(document).ready(function(){
		$('body').on('change','select[name="card"]', function(e){
			e.preventDefault();
			$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">'
				+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-selectUpdateCard', 20, 'selectUpdateCard'); ?>">'
				+ '<input type="hidden" name="id" value="' + $(this).val() + '">');
			$('#<?php echo $dynamicFormId; ?>').submit();
			$('#<?php echo $dynamicFormId; ?>').empty();
		});

		$('body').on('click', '#delete', function(e){
			e.preventDefault();
			id = $(this).data('id');
			console.log("id: ", id);
			if(id != ""){
				swal({
					title: "Are you sure?",
					text: "You will not be able to recover this credit card!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes, delete it!",
					closeOnConfirm: false
				}, function(){
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").append(
						'<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-payments', 20, 'payments'); ?>">'
						+ '<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-delete-cc', 20, 'delete-cc'); ?>">'
						+ '<input type="hidden" name="id" value="' + id + '">');
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").submit();
					$("#<?php echo Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN); ?>").empty();
				});
			}else
				generateNotification('Please select a credit card to delete.', 'bottom-right', 'error', 5000, true);
		});
	});
</script>