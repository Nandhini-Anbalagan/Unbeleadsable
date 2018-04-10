<?php
	if(file_exists("../head.php")){
		include("../head.php");
		$dynamicFormId = Tokenizer::get('dynamic-form-id', Tokenizer::GET_TOKEN);
	}
?>

<div class="m-b-30" id="landingAgentPage">
	<h2 class="page-title"><?php echo $tr['landing_page'] ?>
		<?php if($_SESSION['user']['level'] > 50){ ?>
			<span class="small"><a href="javascript:void(0)" title="<?php echo $tr['edit_title'] ?>" class="on-default edit"><i class="fa fa-pencil"></i> &nbsp; <?php echo $tr['edit'] ?></a></span>
		<?php } ?>
	</h2>
	<form role="form" id="landingForm" class="p-t-20" style="display: none" onsubmit="return validate(this);">
		<input type="hidden" name="action" value="<?php echo Tokenizer::add('post-action-landing', 20, 'landing'); ?>">
		<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-landing-edit-buyer', 20, 'edit-buyer'); ?>">
		<input type="hidden" name="id">
		<div class="row">
			<div class="col-md-6">
				<h3><?php echo $tr['english'] ?></h3>
				<div class="form-group">
					<label for="city_en"><?php echo $tr['city'] ?></label>
					<input type="text" class="form-control" id="city_en" name="city_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="title_en"><?php echo $tr['title'] ?></label>
					<input type="text" class="form-control" id="title_en" name="title_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="sub_title_en"><?php echo $tr['sub_title'] ?></label>
					<input type="text" class="form-control" id="sub_title_en" name="sub_title_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="next_button_en"><?php echo $tr['next_button'] ?></label>
					<input type="text" class="form-control" id="next_button_en" name="next_button_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="email_field_en">Email Field</label>
					<input type="text" class="form-control" id="email_field_en" name="email_field_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="bedroom_label_en">Bedrooms label</label>
					<input type="text" class="form-control" id="bedroom_label_en" name="bedroom_label_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="buying_frame_en">Buying Frame Label</label>
					<input type="text" class="form-control" id="buying_frame_en" name="buying_frame_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="name_label_en">Name Label</label>
					<input type="text" class="form-control" id="name_label_en" name="name_label_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="name_field_en">Name Field</label>
					<input type="text" class="form-control" id="name_field_en" name="name_field_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="phone_label_en">Phone Label</label>
					<input type="text" class="form-control" id="phone_label_en" name="phone_label_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="phone_field_en">Phone Field</label>
					<input type="text" class="form-control" id="phone_field_en" name="phone_field_en" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="thank_you_en">Thank You Label</label>
					<input type="text" class="form-control" id="thank_you_en" name="thank_you_en" placeholder="" required>
				</div>

				<div class="form-group">
					<label class="col-sm-12 control-label"><?php echo $tr['background'] ?>s</label>
					<div class="col-sm-6">
						<div class="radio radio-warning">
							<input type="radio" id="bg1" value="default.jpg" name="defaultBackground">
							<label for="bg1" class="imgMod" src="uploads/landings/default.jpg"> <?php echo $tr['background'] ?> 1 </label>
						</div>
						<div class="radio">
							<input type="radio" id="bg2" value="default2.jpg" name="defaultBackground">
							<label for="bg2" class="imgMod" src="uploads/landings/default2.jpg"> <?php echo $tr['background'] ?> 2 </label>
						</div>
						<div class="radio radio-danger">
							<input type="radio" id="bg3" value="default3.jpg" name="defaultBackground">
							<label for="bg3" class="imgMod" src="uploads/landings/default3.jpg"> <?php echo $tr['background'] ?> 3 </label>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="radio radio-danger">
							<input type="radio" id="bg4" value="default4.jpg" name="defaultBackground">
							<label for="bg4" class="imgMod" src="uploads/landings/default4.jpg"> <?php echo $tr['background'] ?> 4 </label>
						</div>
						<div class="radio radio-danger">
							<input type="radio" id="bg5" value="default5.jpg" name="defaultBackground">
							<label for="bg5" class="imgMod" src="uploads/landings/default5.jpg"> <?php echo $tr['background'] ?> 5 </label>
						</div>
						<div class="radio radio-danger">
							<input type="radio" id="bg6" value="default6.jpg" name="defaultBackground">
							<label for="bg6" class="imgMod" src="uploads/landings/default6.jpg"> <?php echo $tr['background'] ?> 6 </label>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="radio radio-info">
							<input type="radio" id="bg7" value="" name="defaultBackground">
							<label for="bg7"> <?php echo $tr['custom_background'] ?> </label>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<h3><?php echo $tr['francais'] ?></h3>
				<div class="form-group">
					<label for="city_en"><?php echo $tr['city'] ?></label>
					<input type="text" class="form-control" id="city_en" name="city_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="title_fr"><?php echo $tr['title'] ?></label>
					<input type="text" class="form-control" id="title_fr" name="title_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="sub_title_fr"><?php echo $tr['sub_title'] ?></label>
					<input type="text" class="form-control" id="sub_title_fr" name="sub_title_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="next_button_fr">Next Button</label>
					<input type="text" class="form-control" id="next_button_fr" name="next_button_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="email_field_fr">Email Field</label>
					<input type="text" class="form-control" id="email_field_fr" name="email_field_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="bedroom_label_fr">Bedrooms label</label>
					<input type="text" class="form-control" id="bedroom_label_fr" name="bedroom_label_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="buying_frame_fr">Buying Frame Label</label>
					<input type="text" class="form-control" id="buying_frame_fr" name="buying_frame_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="name_label_fr">Name Label</label>
					<input type="text" class="form-control" id="name_label_fr" name="name_label_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="name_field_fr">Name Field</label>
					<input type="text" class="form-control" id="name_field_fr" name="name_field_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="phone_label_fr">Phone Label</label>
					<input type="text" class="form-control" id="phone_label_fr" name="phone_label_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="phone_field_fr">Phone Field</label>
					<input type="text" class="form-control" id="phone_field_fr" name="phone_field_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="thank_you_fr">Thank You Label</label>
					<input type="text" class="form-control" id="thank_you_fr" name="thank_you_fr" placeholder="" required>
				</div>
				<div class="form-group">
					<input type="file" class="filestyle" data-buttonbefore="true">
					<em class="mutted"><?php echo $tr['upload_image'] ?></em>
					<input type="hidden" name="uploadedBg">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary waves-effect waves-light pull-right"><?php echo $tr['submit'] ?></button>
				</div>
			</div>
		</div>
	</form>

	<div class="row" id="view">
		<div class="col-md-6">
			<h3><?php echo $tr['english'] ?></h3>
			<h4><?php echo $tr['city'] ?>: <span target="city_en" class="small"></span></h4>
			<h4><?php echo $tr['title'] ?>: <span target="title_en" class="small"></span></h4>
			<h4><?php echo $tr['sub_title'] ?>: <span target="sub_title_en" class="small"></span></h4>
			<h4><?php echo $tr['next_button'] ?>: <span target="next_button_en" class="small"></span></h4>
			<h4><?php echo $tr['email_field'] ?>: <span target="email_field_en" class="small"></span></h4>
			<h4><?php echo $tr['bedroom_label'] ?>: <span target="bedroom_label_en" class="small"></span></h4>
			<h4><?php echo $tr['buying_frame'] ?>: <span target="buying_frame_en" class="small"></span></h4>
			<h4><?php echo $tr['name_label'] ?>: <span target="name_label_en" class="small"></span></h4>
			<h4><?php echo $tr['name_field'] ?>: <span target="name_field_en" class="small"></span></h4>
			<h4><?php echo $tr['phone_label'] ?>: <span target="phone_label_en" class="small"></span></h4>
			<h4><?php echo $tr['phone_field'] ?>: <span target="phone_field_en" class="small"></span></h4>
			<h4><?php echo $tr['thank_you_label'] ?>: <span target="thank_you_en" class="small"></span></h4>
			<br>
			<h3><?php echo $tr['home_eval_link'] ?></h3>
			<h4 class="clickable"><?php echo $tr['website'] ?>: <span target="homeEval_web_en" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
			<h4 class="clickable"><?php echo $tr['facebook'] ?>: <span target="homeEval_facebook_en" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
			<h4 class="clickable"><?php echo $tr['google_adword'] ?>: <span target="homeEval_google_en" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
		</div>
		<div class="col-md-6">
			<h3><?php echo $tr['francais'] ?></h3>
			<h4><?php echo $tr['city'] ?>: <span target="city_fr" class="small"></span></h4>
			<h4><?php echo $tr['title'] ?>: <span target="title_fr" class="small"></span></h4>
			<h4><?php echo $tr['sub_title'] ?>: <span target="sub_title_fr" class="small"></span></h4>
			<h4><?php echo $tr['next_button'] ?>: <span target="next_button_fr" class="small"></span></h4>
			<h4><?php echo $tr['email_field'] ?>: <span target="email_field_fr" class="small"></span></h4>
			<h4><?php echo $tr['bedroom_label'] ?>: <span target="bedroom_label_fr" class="small"></span></h4>
			<h4><?php echo $tr['buying_frame'] ?>: <span target="buying_frame_fr" class="small"></span></h4>
			<h4><?php echo $tr['name_label'] ?>: <span target="name_label_fr" class="small"></span></h4>
			<h4><?php echo $tr['name_field'] ?>: <span target="name_field_fr" class="small"></span></h4>
			<h4><?php echo $tr['phone_label'] ?>: <span target="phone_label_fr" class="small"></span></h4>
			<h4><?php echo $tr['phone_field'] ?>: <span target="phone_field_fr" class="small"></span></h4>
			<h4><?php echo $tr['thank_you_label'] ?>: <span target="thank_you_fr" class="small"></span></h4>
			<br>
			<h3><?php echo $tr['home_eval_link'] ?></h3>
			<h4 class="clickable"><?php echo $tr['website'] ?>: <span target="homeEval_web_fr" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
			<h4 class="clickable"><?php echo $tr['facebook'] ?>: <span target="homeEval_facebook_fr" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
			<h4 class="clickable"><?php echo $tr['google_adword'] ?>: <span target="homeEval_google_fr" class="small" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $tr['click_copy'] ?>"></span></h4>
		</div>
		<div class="col-md-12 text-center">
			<img src="" target="background" class="img-thumbnail img-responsive" alt="" width="600">
		</div>
	</div>
</div>

<script>
	$(function(){
		$('a.edit').click(function(){
			$("#view").toggle();
			$("#landingForm").toggle();
		});

		$('td[name="name"]').click(function(){
			$("#view").show();
			$("#landingForm").hide();
		});

		$('.clickable').click(function(){
			copyToClipboard($(this).find('span'));
			generateNotification("<?php echo $tr['copied'] ?>", "bottom-right", "info", 2000);
		})

		function copyToClipboard(element) {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val($(element).text()).select();
			document.execCommand("copy");
			$temp.remove();
		}
	});
</script>