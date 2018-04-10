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
			<input type="hidden" name="case" value="<?php echo Tokenizer::add('post-case-landing-edit-seller', 20, 'edit-seller'); ?>">
			<input type="hidden" name="id">
			<div class="row">
				<div class="col-md-6">
					<h3><?php echo $tr['english'] ?></h3>
					<div class="form-group">
						<label for="city_en"><?php echo $tr['city'] ?></label>
						<input type="text" class="form-control" id="city_en" name="city_en" placeholder="<?php echo $tr['city'] ?>" required>
					</div>
					<div class="form-group">
						<label for="title_en"><?php echo $tr['title'] ?></label>
						<input type="text" class="form-control" id="title_en" name="title_en" placeholder="<?php echo $tr['title'] ?>" required>
					</div>
					<div class="form-group">
						<label for="sub_title_1_en"><?php echo $tr['sub_title'] ?> 1</label>
						<input type="text" class="form-control" id="sub_title_1_en" name="sub_title_1_en" placeholder="<?php echo $tr['sub_title'] ?> 1" required>
					</div>
					<div class="form-group">
						<label for="sub_title_2_en"><?php echo $tr['sub_title'] ?> 2</label>
						<input type="text" class="form-control" id="sub_title_2_en" name="sub_title_2_en" placeholder="<?php echo $tr['sub_title'] ?> 2" required>
					</div>
					<div class="form-group">
						<label for="agent_title_en"><?php echo $tr['agent_title'] ?></label>
						<input type="text" class="form-control" id="agent_title_en" name="agent_title_en" placeholder="<?php echo $tr['agent_title'] ?>" required>
					</div>
					<div class="form-group">
						<label for="final_text_en"><?php echo $tr['final_text'] ?></label>
						<textarea class="form-control" id="final_text_en" name="final_text_en" placeholder="<?php echo $tr['final_text'] ?>" rows="7" required></textarea>           
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
					<h3>French</h3>
					<div class="form-group">
						<label for="city_fr"><?php echo $tr['city'] ?></label>
						<input type="text" class="form-control" id="city_fr" name="city_fr" placeholder="<?php echo $tr['city'] ?>" required>
					</div>
					<div class="form-group">
						<label for="title_fr"><?php echo $tr['title'] ?></label>
						<input type="text" class="form-control" id="title_fr" name="title_fr" placeholder="<?php echo $tr['title'] ?>" required>
					</div>
					<div class="form-group">
						<label for="sub_title_1_fr"><?php echo $tr['sub_title'] ?> 1</label>
						<input type="text" class="form-control" id="sub_title_1_fr" name="sub_title_1_fr" placeholder="<?php echo $tr['sub_title'] ?> 1" required>
					</div>
					<div class="form-group">
						<label for="sub_title_2_fr"><?php echo $tr['sub_title'] ?> 2</label>
						<input type="text" class="form-control" id="sub_title_2_fr" name="sub_title_2_fr" placeholder="<?php echo $tr['sub_title'] ?> 2" required>
					</div>
					<div class="form-group">
						<label for="agent_title_fr"><?php echo $tr['agent_title'] ?></label>
						<input type="text" class="form-control" id="agent_title_fr" name="agent_title_fr" placeholder="<?php echo $tr['agent_title'] ?>" required>
					</div>
					<div class="form-group">
						<label for="final_text_fr">Final Text</label>
						<textarea class="form-control" id="final_text_fr" name="final_text_fr" placeholder="Final Text" rows="7" required></textarea>           
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
			<h4><?php echo $tr['sub_title'] ?> 1: <span target="sub_title_1_en" class="small"></span></h4>
			<h4><?php echo $tr['sub_title'] ?> 2: <span target="sub_title_2_en" class="small"></span></h4>
			<h4><?php echo $tr['agent_name'] ?>: <span target="agent_name" class="small"></span></h4>
			<h4><?php echo $tr['agent_phone'] ?>: <span target="agent_phone" class="small"></span></h4>
			<h4><?php echo $tr['agent_email'] ?>: <span target="agent_email" class="small"></span></h4>
			<h4><?php echo $tr['agent_title'] ?>: <span target="agent_title_en" class="small"></span></h4>
			<h4><?php echo $tr['final_text'] ?>: <span target="final_text_en" class="small"></span></h4>
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
			<h4><?php echo $tr['sub_title'] ?> 1: <span target="sub_title_1_fr" class="small"></span></h4>
			<h4><?php echo $tr['sub_title'] ?> 2: <span target="sub_title_2_fr" class="small"></span></h4>
			<h4><?php echo $tr['agent_name'] ?>: <span target="agent_name" class="small"></span></h4>
			<h4><?php echo $tr['agent_phone'] ?>: <span target="agent_phone" class="small"></span></h4>
			<h4><?php echo $tr['agent_email'] ?>: <span target="agent_email" class="small"></span></h4>
			<h4><?php echo $tr['agent_title'] ?>: <span target="agent_title_fr" class="small"></span></h4>
			<h4><?php echo $tr['final_text'] ?>: <span target="final_text_fr" class="small"></span></h4>
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