var lastToast = null;
var removeDisable;
var addCourseProceed = false;

$(document).ready(function(){
	$('#navigation li.dropdown').hover(function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(100);
	}, function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(100);
	});

	$("html").niceScroll({ zindex: 10000 });
	$(".modal").niceScroll({ zindex: 10000 });
	$(".tags-default").niceScroll({ zindex: 10100 });

	$('select.fancy').select2();

	// Message for upcoming links
	$('body').on('click', 'a.troll', function(e){
		if($(this).attr('href') == "#"){
			e.preventDefault();
			generateNotification("That's too bad!! [Coming soon...]", "bottom-right", "info", 5000);
		}
	});

	$('body').on('click', '.checkbox ', function(e){
		var input = $(this).find("input");
		input.prop("checked", !input.prop("checked"));
	});

	$("body").on('submit', "form[data-parsley-validate='']", function(){
		if($(this).parsley().validate())
			return validate(this);

		return false;
	});

	//Image in Bootstrap modal :D
	$('body').on('click', '.imgMod', function(e){
		console.log("boom");
		$('div.modalImg').attr("style", "background-image: url('"+$(this).attr('src')+"')")
		$('#picture-modal').modal('show');
	}).on('click', '.close', function(e){
		$('#picture-modal').modal('hide');
	});

});

/*
*	Function to capitalize the first letter of a string.
*	@string: string to capitalize.
*/
function capitalize(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
*   Function to clear the last toast.
*/
function clearToast(){
	if(lastToast != null)
		toastr.clear(lastToast);
}

/**
*	Function to do a list of actions after an ajax call.
*	action:				Name of the callback action.
*	data:				Data needed for the action as value or associative array.
*	Last updated:		2015-10-13
*/
function executeAction(action, data){
	switch(action){
		case "add-template":
			hideModal();
			tinymce.get('compose-textarea').remove();
			tinymce.get('content-textarea').remove();
			tinymce.get('edit-content-textarea').remove();
			$('.template-container').load('load/templates');
			$('.email-menu').load('load/email-menu');
			break;
		case "get-single-edit-template":
			var modal = $('#edit-template-modal');
			modal.find('input[name="id"]').val(data['email_template_id']);
			modal.find('input[name="name"]').val(data['name']);
			modal.find('input[name="slug"]').val(data['slug']);
			modal.find('input[name="content"]').val(data['content']);
			tinymce.get('edit-content-textarea').setContent(data['content'].replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
			break;
		case "get-single-edit-funnel":
			var modal = $('#edit-template-modal');
			modal.find('select[name="funnel"]').select2("val", data['category']);
			modal.find('input[name="id"]').val(data['funnel_id']);
			modal.find('input[name="name"]').val(data['name']);
			modal.find('input[name="content"]').val(data['content']);
			modal.find('select[name="lang"]').select2("val", data['language']);

			tinymce.get("edit-content-textarea").setContent(data['content'].replace(/&lt;/g, '<').replace(/&gt;/g, '>'), {format: 'raw'});
			
			var interval = data['interval'], frame = -1;

			if(interval == 0){
				frame = 0;
				interval = 0;
			}else if (interval < 24)
				frame = 1;
			else if(interval / 24 < 30){
				frame = 24;
				interval = interval / 24;
			}else{
				frame = 720;
				interval = interval / 720;
			}

			modal.find('select[name="intervalFrame"]').select2("val", frame);
			modal.find('input[name="intervalNum"]').val(interval);

			break;
		case "get-single-view-funnel":
			var modal = $('#view-template-modal');
			var lang = data['language']=="EN"?"English/Anglais":"French/Français"
			//modal.find('td[target="funnel"]').html(data['category']);
			modal.find('td[target="name"]').html(data['name']);
			//modal.find('td[target="language"]').html(lang);
			modal.find('td[target="content"]').html(data['content'].replace(/&lt;/g, '<').replace(/&gt;/g, '>'));

			var interval = data['interval'], frame = -1;

			if(interval == 0){
				frame = "Immediately/Imediatement";
				interval = "";
			}else if (interval < 24)
				frame = "Hour(s)/Heure(s)";
			else if(interval / 24 < 30){
				frame = "Day(s)/Jour(s)";
				interval = interval / 24;
			}else{
				frame = "Month(s)/Mois";
				interval = interval / 720;
			}

			//modal.find('td[target="interval"]').html(interval + " " + frame);

			break
		case "get-single-edit-funnelCat":
			var modal = $('#add-funnel');
			modal.find('#editName').val(data['title']);
			modal.find('#editType').select2("val", data['agent_type']);
			break;
		case "get-single-edit-user":
			var modal = $('#edit-modal');
			modal.find('input[name="user_id"]').val(data['user_id']);
			modal.find('input[name="name"]').val(data['name']);
			modal.find('input[name="username"]').val(data['username']);
			modal.find('input[name="email"]').val(data['email']);
			modal.find('[name="country"]').select2("val", data['country']);
			modal.find('[name="level"]').select2("val", data['level']);

			if(data['level'] == 100){
				modal.find("div#passDiv").show();
				modal.find("div#lvlDiv").hide();
				modal.find("div#delDiv").hide();
			}else{
				modal.find("div#passDiv").hide();
				modal.find("div#lvlDiv").show();
				modal.find("div#delDiv").show();
			}

			break;
		case "get-single-edit-agent-user":
			var modal = $('#user-modal');
			modal.find('input[name="user_id"]').val(data['user_id']);
			modal.find('input[name="username"]').val(data['username']);
			modal.find('input[name="email"]').val(data['email']);
			modal.find("#currentPassword").html("<b>Current (MD5)</b> : " + data['password']);
			
			break;
		case "get-single-edit-agent-lead":
			var modal = $('#edit-modal');
			modal.find('input[name="id"]').val(data['lead_id']);
			modal.find('input[name="name"]').val(data['lead_name']);
			modal.find('input[name="email"]').val(data['lead_email']);
			modal.find('input[name="phone"]').val(data['lead_phone']);
			modal.find('input[name="areas"]').val(data['lead_areas']);
			modal.find('input[name="agency"]').val(data['lead_agency']);
			modal.find('input[name="license"]').val(data['lead_license']);
			modal.find('input[name="board"]').val(data['lead_board']);
			modal.find('input[name="ref"]').val(data['lead_ref']);
			modal.find('textarea[name="comments"]').val(data['lead_comments']);
			modal.find('select[name="lang"]').select2("val", data['lead_lang']);
			break;
		case "get-single-view-agent-lead":
			var modal = $('#view-modal');
			modal.find('td[target="name"]').html(data['lead_name']);
			modal.find('td[target="email"]').html(data['lead_email']);
			modal.find('td[target="phone"]').html(data['lead_phone']);
			modal.find('td[target="areas"]').html(data['lead_areas']);
			modal.find('td[target="agency"]').html(data['lead_agency']);
			modal.find('td[target="license"]').html(data['lead_license']);
			modal.find('td[target="board"]').html(data['lead_board']);
			modal.find('td[target="comments"]').html(data['lead_comments']);
			modal.find('td[target="language"]').html(data['lead_lang']);
			modal.find('td[target="ref"]').html(data['lead_ref']);
			modal.find('td[target="status"]').html(data['status_name']);

			var d = new Date(data['lead_date']);
			var n = d.toDateString();

			modal.find('td[target="date"]').html(n);

			break
		case "get-single-edit-agent":
			var modal = $('#edit-modal');
			modal.find('input[name="id"]').val(data['agent_id']);
			modal.find('input[name="name"]').val(data['agent_name']);
			modal.find('input[name="email"]').val(data['agent_email']);
			modal.find('input[name="phone"]').val(data['agent_phone']);
			modal.find('input[name="address"]').val(data['agent_address']);
			modal.find('input[name="areas"]').val(data['agent_areas']);
			modal.find('input[name="agency"]').val(data['agent_agency']);
			modal.find('input[name="license"]').val(data['agent_license']);
			modal.find('input[name="board"]').val(data['agent_board']);
			modal.find('input[name="ref"]').val(data['agent_ref']);
			modal.find('input[name="camp"]').val(data['campaign_id']);
			modal.find('textarea[name="comments"]').val(data['agent_comments']);
			modal.find('select[name="lang"]').select2("val", data['agent_lang']);
			modal.find('input[name="signature"]').val(data['agent_signature']);
			modal.find('input[name="phone_notification"]').val(data['phone_alert']);
			modal.find('input[name="email_notification"]').val(data['email_alert']);
			modal.find('input[name="avatar"]').val(data['avatar']);
			break;
		case "get-single-edit-partial-lead":
			var modal = $('#edit-modal');
			modal.find('input[name="lead_id"]').val(data['id']);
			modal.find('input[name="name"]').val(data['name']);
			modal.find('input[name="email"]').val(data['email']);
			modal.find('input[name="phone"]').val(data['phone']);
			modal.find('input[name="address"]').val(data['address']);
			modal.find('select[name="selling"]').select2("val", data['selling']);
			modal.find('select[name="lang"]').select2("val", data['lang']);
			break;
		case "get-single-view-agent":
			var modal = $('#view-modal');
			modal.find('td[target="name"]').html(data['agent_name']);
			modal.find('td[target="email"]').html(data['agent_email']);
			modal.find('td[target="phone"]').html(data['agent_phone']);
			modal.find('td[target="address"]').html(data['agent_address']);
			modal.find('td[target="areas"]').html(data['agent_areas']);
			modal.find('td[target="agency"]').html(data['agent_agency']);
			modal.find('td[target="license"]').html(data['agent_license']);
			modal.find('td[target="board"]').html(data['agent_board']);
			modal.find('td[target="ref"]').html(data['agent_ref']);
			modal.find('td[target="camp"]').html(data['campaign_id']);
			modal.find('td[target="comments"]').html(data['agent_comments']);
			modal.find('td[target="language"]').html(data['agent_lang']);

			var d = new Date(data['agent_date']);
			var n = d.toDateString();

			modal.find('td[target="date"]').html(n);

			break
		case "get-single-edit-area":
			var modal = $('#edit-modal');
			modal.find('input[name="id"]').val(data['area_id']);
			modal.find('input[name="areaName"]').val(data['area_name']);
			modal.find('input[name="latlng"]').val(data['area_latlng']);
			var agents = data['area_agents'].split(",");

			var str = [];
			for(var i=0; i<agents.length; i++)
				str.push(agents[i]);


			modal.find('[name="agents[]"]').select2("val", str);
			console.log(str);
			break;
		case "set_credit_card":
			var form = $("#updateSubscriptionCard");
			form.find('input[name="name"]').val(data['name']);
			form.find('input[name="num"]').val(data['num']);
			form.find('input[name="cvv"]').val(data['cvv']);

			form.find('select[name="type"]').select2("val", data['type']);
			form.find('select[name="mm"]').select2("val", data['mm']);
			form.find('select[name="year"]').select2("val", data['year']);

			form.find('input[name="delete"]').data("id", data['id']);
		break;
		case "get-seller-landing-page":
			var content = $('#landingAgentPage');
			var form = $('#landingForm');

			//English Section
			content.find('span[target="city_en"]').html(data['city_en']);
			content.find('span[target="title_en"]').html(data['title_en']);
			content.find('span[target="sub_title_1_en"]').html(data['sub_title_1_en']);
			content.find('span[target="sub_title_2_en"]').html(data['sub_title_2_en']);
			content.find('span[target="agent_name"]').html(data['agent_name']);
			content.find('span[target="agent_phone"]').html(data['agent_phone']);
			content.find('span[target="agent_email"]').html(data['agent_email']);
			content.find('span[target="agent_title_en"]').html(data['agent_title_en']);
			content.find('span[target="final_text_en"]').html(data['final_text_en']);
			content.find('span[target="homeEval_web_en"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=e&s=w");
			content.find('span[target="homeEval_facebook_en"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=e&s=f");
			content.find('span[target="homeEval_google_en"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=e&s=g");

			//French Section
			content.find('span[target="city_fr"]').html(data['city_fr']);
			content.find('span[target="title_fr"]').html(data['title_fr']);
			content.find('span[target="sub_title_1_fr"]').html(data['sub_title_1_fr']);
			content.find('span[target="sub_title_2_fr"]').html(data['sub_title_2_fr']);
			content.find('span[target="agent_name"]').html(data['agent_name']);
			content.find('span[target="agent_phone"]').html(data['agent_phone']);
			content.find('span[target="agent_email"]').html(data['agent_email']);
			content.find('span[target="agent_title_fr"]').html(data['agent_title_fr']);
			content.find('span[target="final_text_fr"]').html(data['final_text_fr']);
			content.find('span[target="homeEval_web_fr"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=f&s=w");
			content.find('span[target="homeEval_facebook_fr"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=f&s=f");
			content.find('span[target="homeEval_google_fr"]').html("https://unbeleadsable.com/home-evaluation/?a="+data['agent_fk']+"&l=f&s=g");
			//Background Image
			content.find('img[target="background"]').attr("src", "uploads/landings/" + data['bg_img']);

			//Form English Section
			form.find('input[name="id"]').val(data['id']);
			form.find('input[name="city_en"]').val(data['city_en']);
			form.find('input[name="title_en"]').val(data['title_en']);
			form.find('input[name="sub_title_1_en"]').val(data['sub_title_1_en']);
			form.find('input[name="sub_title_2_en"]').val(data['sub_title_2_en']);
			form.find('input[name="agent_name"]').val(data['agent_name']);
			form.find('input[name="agent_phone"]').val(data['agent_phone']);
			form.find('input[name="agent_email"]').val(data['agent_email']);
			form.find('input[name="agent_title_en"]').val(data['agent_title_en']);
			form.find('textarea[name="final_text_en"]').val(data['final_text_en']);

			//Form French Section
			form.find('input[name="city_fr"]').val(data['city_fr']);
			form.find('input[name="title_fr"]').val(data['title_fr']);
			form.find('input[name="sub_title_1_fr"]').val(data['sub_title_1_fr']);
			form.find('input[name="sub_title_2_fr"]').val(data['sub_title_2_fr']);
			form.find('input[name="agent_name"]').val(data['agent_name']);
			form.find('input[name="agent_phone"]').val(data['agent_phone']);
			form.find('input[name="agent_email"]').val(data['agent_email']);
			form.find('input[name="agent_title_fr"]').val(data['agent_title_fr']);
			form.find('textarea[name="final_text_fr"]').val(data['final_text_fr']);

			//Form Background Image
			form.find('input:radio[name="defaultBackground"]').val([data['bg_img']]);
			break;
		case "get-buyer-landing-page":
			var content = $('#landingAgentPage');
			var form = $('#landingForm');

			//English Section
			content.find('span[target="city_en"]').html(data['city']);
			content.find('span[target="title_en"]').html(data['title_en']);
			content.find('span[target="sub_title_en"]').html(data['sub_title_en']);
			content.find('span[target="next_button_en"]').html(data['next_button_en']);
			content.find('span[target="email_field_en"]').html(data['email_field_en']);
			content.find('span[target="bedroom_label_en"]').html(data['bedroom_label_en']);
			content.find('span[target="buying_frame_en"]').html(data['buying_frame_en']);
			content.find('span[target="name_label_en"]').html(data['name_label_en']);
			content.find('span[target="name_field_en"]').html(data['name_field_en']);
			content.find('span[target="phone_label_en"]').html(data['phone_label_en']);
			content.find('span[target="phone_field_en"]').html(data['phone_field_en']);
			content.find('span[target="thank_you_en"]').html(data['thank_you_en']);
			content.find('span[target="homeEval_web_en"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=e&s=w");
			content.find('span[target="homeEval_facebook_en"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=e&s=f");
			content.find('span[target="homeEval_google_en"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=e&s=g");

			//French Section
			content.find('span[target="city_fr"]').html(data['city']);
			content.find('span[target="title_fr"]').html(data['title_fr']);
			content.find('span[target="sub_title_fr"]').html(data['sub_title_fr']);
			content.find('span[target="next_button_fr"]').html(data['next_button_fr']);
			content.find('span[target="email_field_fr"]').html(data['email_field_fr']);
			content.find('span[target="bedroom_label_fr"]').html(data['bedroom_label_fr']);
			content.find('span[target="buying_frame_fr"]').html(data['buying_frame_fr']);
			content.find('span[target="name_label_fr"]').html(data['name_label_fr']);
			content.find('span[target="name_field_fr"]').html(data['name_field_fr']);
			content.find('span[target="phone_label_fr"]').html(data['phone_label_fr']);
			content.find('span[target="phone_field_fr"]').html(data['phone_field_fr']);
			content.find('span[target="thank_you_fr"]').html(data['thank_you_fr']);
			content.find('span[target="homeEval_web_fr"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=f&s=w");
			content.find('span[target="homeEval_facebook_fr"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=f&s=f");
			content.find('span[target="homeEval_google_fr"]').html("https://unbeleadsable.com/home-listings/?a="+data['agent_fk']+"&l=f&s=g");
			//Background Image
			content.find('img[target="background"]').attr("src", "uploads/landings/" + data['bg_img']);

			//Form English Section
			form.find('input[name="id"]').val(data['id']);
			form.find('input[name="city_en"]').val(data['city']);
			form.find('input[name="title_en"]').val(data['title_en']);
			form.find('input[name="sub_title_en"]').val(data['sub_title_en']);
			form.find('input[name="next_button_en"]').val(data['next_button_en']);
			form.find('input[name="email_field_en"]').val(data['email_field_en']);
			form.find('input[name="bedroom_label_en"]').val(data['bedroom_label_en']);
			form.find('input[name="buying_frame_en"]').val(data['buying_frame_en']);
			form.find('input[name="name_label_en"]').val(data['name_label_en']);
			form.find('input[name="name_field_en"]').val(data['name_field_en']);
			form.find('input[name="phone_label_en"]').val(data['phone_label_en']);
			form.find('input[name="phone_field_en"]').val(data['phone_field_en']);
			form.find('input[name="thank_you_en"]').val(data['thank_you_en']);


			//Form French Section
			form.find('input[name="id"]').val(data['id']);
			form.find('input[name="city_fr"]').val(data['city']);
			form.find('input[name="title_fr"]').val(data['title_fr']);
			form.find('input[name="sub_title_fr"]').val(data['sub_title_fr']);
			form.find('input[name="next_button_fr"]').val(data['next_button_fr']);
			form.find('input[name="email_field_fr"]').val(data['email_field_fr']);
			form.find('input[name="bedroom_label_fr"]').val(data['bedroom_label_fr']);
			form.find('input[name="buying_frame_fr"]').val(data['buying_frame_fr']);
			form.find('input[name="name_label_fr"]').val(data['name_label_fr']);
			form.find('input[name="name_field_fr"]').val(data['name_field_fr']);
			form.find('input[name="phone_label_fr"]').val(data['phone_label_fr']);
			form.find('input[name="phone_field_fr"]').val(data['phone_field_fr']);
			form.find('input[name="thank_you_fr"]').val(data['thank_you_fr']);

			//Form Background Image
			form.find('input:radio[name="defaultBackground"]').val([data['bg_img']]);
			break;
		case "show-evaluation-preview":
			$('#preview-wrapper').html(data);
			break;
		case "show-evaluation-archieve":
			var form = $('#evaluation');
			form.find('input[name="lead"]').val(data['id']);
			form.find('input[name="low"]').val(data['low']);
			form.find('input[name="high"]').val(data['high']);
			form.find('input[name="muni"]').val(data['municipality']);
			form.find('textarea[name="comments"]').val(data['com']);
			break;
		case "agent-subscription":
			var form = $('#subscription');
			form.find('input[name="id"]').val(data['id']);
			form.find('input[name="user_id"]').val(data['user']);
			form.find('input[name="area"]').val(data['area']);
			form.find('input[name="lang"]').val(data['lang']);
			form.find('input[name="fname"]').val(data['fname']);
			form.find('input[name="lname"]').val(data['lname']);
			form.find('input[name="email"]').val(data['email']);
			form.find('input[name="address"]').val(data['street']);
			form.find('input[name="city"]').val(data['city']);
			form.find('select[name="country"]').select2("val", data['country']);
			form.find('select[name="state"]').select2("val", data['state']);
			form.find('input[name="zip"]').val(data['zip']);
			form.find('input[name="ccn"]').val(data['cc_num']);
			form.find('input[name="ccname"]').val(data['cc_name']);
			form.find('select[name="exp1"]').select2("val", data['cc_mm']);
			form.find('select[name="exp2"]').select2("val", data['cc_yy']);
			form.find('input[name="cvv"]').val(data['cc_cvv']);
			form.find('input[type=radio][value=' + data['cc_type'] +']').prop('checked', true);
			form.find('input[name="amount"]').val(data['amount']);
			break;
		case "get-group":
			var modal = $('#edit-group-modal');
			modal.find('input[name="name"]').val(data['name']);
			modal.find('input[name="id"]').val(data['id']);
			modal.find('input[name="emails"]').tagsinput('removeAll');

			$.each(data['emails'], function(key, value){
				modal.find('input[name="emails"]').tagsinput('add', value);
			});
			break;
		case "preview-email":
			var modal = $('#email-preview');
			modal.find('.panel-title').html(data['title']);
			modal.find('.panel-body').html(data['content']);
			modal.modal('show');
			break;
		case "select-single-funnel":
			$('input[name="subject"]').val(data['name']);
			$('input[name="content"]').val(data['content']);
			tinymce.get('compose-textarea').setContent(data['content'].replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
			break;
		case "get-single-edit-calls":
			var modal = $('#edit-modal');
			modal.find("input[name=id]").val(data["call_id"]);
			modal.find("input[name=name]").val(data["call_name"]);
			modal.find("input[name=phone]").val(data["call_phone"]);
			modal.find("input[name=source]").val(data["call_source"]);
			modal.find("input[name=desired_area]").val(data["call_desired_area"]);
			modal.find("textarea[name=notes]").val(data["call_notes"]);
			modal.find("select[name=status]").select2("val", data["call_state"]);
			break;
		case "get-single-convert-calls":
			var modal = $('#convert-lead-modal');
			modal.find("h2").text("Convert to a lead");
			modal.find("input[name=id]").val(data["call_id"]);
			modal.find("input[name=name]").val(data["call_name"]);
			modal.find("input[name=phone]").val(data["call_phone"]);
			modal.find("input[name=ref]").val(data["call_source"]);
			modal.find("input[name=areas]").val(data["call_desired_area"]);
			modal.find("textarea[name=notes]").val(data["call_notes"]);
			break;
		case "get-single-msg":
			var modal = $('#view-msg');
			modal.find('td[target="date"]').html(data['date']);
			modal.find('td[target="subject"]').html(data['subject']);
			modal.find('td[target="message"]').html(data['message'] != null?data['message']:"N/A");
			break;
		case "reload-users":
			hideModal();
			$('div#users-wrapper').load('load/users');
			break;
		case "reload-team-users":
			hideModal();
			$('#settingBody').load('load/team');
			break;
		case "reload-leads":
			hideModal();
			$('div#leads-wrapper').load('load/leads');
			break;
		case "reload-agents":
			hideModal();
			$('div#agents-wrapper').load('load/agents');
			break;
		case "reload-areas":
			hideModal();
			$('div#areas-wrapper').load('load/areas');
			break;
		case "reload-groups":
			hideModal();
			tinymce.get('compose-textarea').remove();
			$('div.group-container').load('load/groups');
			$('.email-menu').load('load/email-menu?page=groups');
			break;
		case "reload-status":
			$("#settingBody").load("load/lead_statues.php");
			break;
		case "reload-completed-leads":
			$("#completed-leads-wrapper").load("load/completed-leads.php");
			break;
		case "reload-calls":
			hideModal();
			$("#calls-wrapper").load("load/call-center.php");
			break;
		case "reload-leads-calls":
			hideModal();
			$('#leads-wrapper').load('load/leads');
			$("#calls-wrapper").load("load/call-center.php");
			break;
		case "agent-stats":
			var modal = $('#stats-modal');
			modal.find('h2[target="title"]').html("Stats for: " + data['name']);
			modal.find('td[target="range"]').html(data['range']);
			modal.find('td[target="completed"]').html(data['completed']);
			modal.find('td[target="partial"]').html(data['partial']);
			modal.find('td[target="address"]').html(data['address']);

			if(data['reach'] != "" && data['reach'] != null){
				modal.find('td[target="spent"]').html("$" + data['spent']);
				modal.find('td[target="reach"]').html(data['reach']);
				modal.find('td[target="clicks"]').html(data['clicks']);
				modal.find('td[target="cpc"]').html("$"+Math.round(data['cpc']* 100) / 100);
				modal.find('td[target="ctr"]').html(Math.round(data['ctr']* 100) / 100+"%");
			}else{
				modal.find('td[target="spent"]').html("N/A");
				modal.find('td[target="reach"]').html("N/A");
				modal.find('td[target="clicks"]').html("N/A");
				modal.find('td[target="cpc"]').html("N/A");
				modal.find('td[target="ctr"]').html("N/A");
			}

			break;
		case "reload-partial-leads":
			window.location.reload();
			break;
		case "refresh-funnel":
			hideModal();
			$("#funnel-container").load("load/funnels.php");
			break;
		case "swal":
			var data = $.type(data) != "undefined" ? data : "";
			var message = $.type(data['message']) != "undefined" ? data['message'] : " ";
			var type = $.type(data['type']) != "undefined" ? data['type'] : "info";
			var title = $.type(data['title']) != "undefined" ? data['title'] : capitalize(type);
			swal(title, message, type);

			if($.type(data['next-action']) != "undefined")
				executeAction(data['next-action'], $.type(data['next-action-data']) != "undefined" ? data['next-action-data'] : "");
			break;
		case "hide-modal":
			hideModal();
			break;
		default:
			generateNotification('Unknown callback action: ' + action, 'bottom-right', 'warning', 3000, false);
			break;
	}
}

/**
*	Function to generate a notification.
*	@message:			Message of the notification.
*	@layout:			Default: top-full-width (top-full-width | top-left | top-center | top-right | bottom-left | bottom-center | bottom-right | bottom-full-width)
*	@type:				Default: warning (success | info | warning | error)
*	@duration:			Duration of the notification in miliseconds. Default: 30000 (30 seconds)
*   @isKiller:          True if the last toast will be cleared.  
*   @hasCloseButton:    True to show the close button. Default: false      
*   @callback:          Name of the callback action for the function executeAction.  
*	Last updated: 		2015-12-28
*/
function generateNotification(message, layout, type, duration, isKiller, hasCloseButton, callback){
	// List of possible layouts and types
	var layouts = ["top-full-width", "top-left", "top-center", "top-right", "bottom-left", "bottom-center", "bottom-right", "bottom-full-width"];
	var types = ["success", "info", "warning", "error"];
	
	// Check if the layout exist. If it doesn't, set it to top.
	if($.type(layout) !== "string" || $.inArray(layout, layouts) == -1)
		layout = "top-full-width";
	
	// Check if the type exist. If it doesn't, set it to warning.
	if($.type(type) !== "string" || $.inArray(type, types) == -1)
		type = "warning";
	
	// If duration is not set, set it to 30 seconds.
	if($.type(duration) !== "number" || duration < 0)
		duration = 30000;
	
	// Set killer to true.
	if(($.type(isKiller) !== "boolean" && lastToast != null) || isKiller === true)
		clearToast();

	// Set the close button to false
	if($.type(hasCloseButton) !== "boolean" || hasCloseButton === false)
		hasCloseButton = false;
	
	// Set the toast options
	toastr.options = {
		"closeButton" : hasCloseButton,
		"positionClass" : "toast-" + layout,
		"timeOut" : duration,
		"preventDuplicates": true,
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut",
		"showDuration" : 300,
		"hideDuration" : 300
	};
	
	// Set the callback if there's one
	if($.type(callback) === "string")
		toastr.options.onclick = function(){ executeAction(callback); };

	// Display the toast and save it as the last toast
	lastToast = toastr[type](message);
}

/*
*	Function to generate a random number.
*	@min: minimum inclusive.
*	@max: maximum inclusive.
*/
function generateRandomNumber(min, max){
	if(isNaN(min))
		min = 1;

	if(isNaN(max))
		max = 54;

	return Math.floor(Math.random() * (max - min + 1)) + min;
}

/*
*	Function to generate a random string.
*	@length: length of the string. Default: 20
*	@hasNumbers: true if the string can contain digits. Default: true
*	@intBegin: true if the string can start with a number. Default: false
*	@hasSymbols: true if the string can contain symbols. Default: false
*	@symbolBegin: true if the string can begin with a symbol. Default: false
*/
function generateRandomString(length, hasNumbers, intBegin, hasSymbols, symbolBegin){
	var characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var digits = "012345679";
	var symbols = "!@#$%^&*()-_+=";
	var result = "";
	var invalidStart = "";
	
	// Set the default length to 20
	if(isNaN(length))
		length = 20;

	if($.type(hasNumbers) !== "boolean" && hasNumbers === true){
		// Set the default intBegin to false
		if($.type(intBegin) !== "boolean")
			intBegin = false;

		characters += digits;
	}else{ hasNumbers = false; }

	if($.type(hasSymbols) === "boolean" && hasSymbols === true){
		// Set the default symbolBegin to false
		if($.type(symbolBegin) !== "boolean")
			symbolBegin = false;

		characters += symbols
	}else{ hasSymbols = false; }
	
	// Calculate the invalid start
	if(!intBegin)
		invalidStart += digits;

	if(!symbolBegin)
		invalidStart += symbols;
	
	do{
		result += characters[generateRandomNumber(0, characters.length - 1)];
		
		if(result.length == 1 && invalidStart.indexOf(result) !== -1)
			result = generateRandomString(length, hasNumbers, intBegin, hasSymbols, symbolBegin);
	}while(result.length < length);
	
	return result;
}

/*
*	Function to hide the bootstrap modal.
*/
function hideModal(){
	$('.modal').modal('hide');
	$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();
}

/*
*	Function to initialize the nice scroll plugin.
*/
function initializeNiceScroll(){
	$('div.nicescroll').niceScroll({ cursorcolor: '#98a6ad', cursorwidth:'6px', cursorborderradius: '5px'});
}

/**
*	Function to validate a specific form via ajax.
*	@pForm:				The form to validate.
*	@pContinue:			If true, action will continue. Default: false.
*	@pRemoveDisable:	True to disable all form inputs during the form submission. Default: true
*	Last updated: 		2016-02-03
*/
function validate(pForm, pContinue, pDisable){
	// Check if pContinue is set and has a valid value. If not, set it to false.
	if($.type(pContinue) !== "boolean")
		pContinue = false;
	
	// Check if pDisable is set and has a valid value. If not, set it to true
	if($.type(pDisable) !== "boolean")
		pDisable = true;
	
	// Send the request to the core
	$.ajax({
		url: 'core.php',
		type: 'POST',
		data: new FormData(pForm),
		dataType: 'json',
		contentType: false,      
		cache: false,             
		processData:false,
		beforeSend: function(){
			// Close all notifications
			toastr.clear();
			
			// Add the readonly attribute to each input and disable the buttons
			if(pDisable){
				$('input[type="submit"], button[type="submit"], input').attr('disabled', true);
				$('form input, form select').attr('readonly', 'readonly');
				$('select').select2('disable');
				removeDisable = true;
			}

			// Show the ajax loader
			$('div.loader-holder').fadeIn();
		},
		success: function(data){
			// Display the message unless specified not to
			if($.type(data['no-message']) !== "boolean" || data['no-message'] == false){
				if(data['error'] != -1)
					generateNotification(data['error'], data['layout'], 'error', 3000, true, data['toast-button'], data['toast-callback']);
				else
					generateNotification(data['success'], data['layout'], 'success', data['duration'], data['killer'], data['toast-button'], data['toast-callback']);
			}

			// Check if specified to not clear form
			if($.type(data['reset']) === "boolean" && data['reset'] == true){
				$(pForm).trigger('reset');
				$(pForm).find('select').select2("val", "");
			} 
				
			
			// Set the remove-disable property
			removeDisable = data['remove-disable'];
			
			// Check if there's a redirect
			if($.type(data['redirect']) === "boolean" && data['redirect'] == true)
				setTimeout(function(){
					if($.type(data['refresh']) === "boolean" && data['refresh'] == true)
						window.location.reload();
					else
						window.location.href = data['destination'];
				}, data['delay']);
			
			// Check if there's a callback
			if(data['callback'] != null && data['callback'] != "undefined")
				executeAction(data['callback'], data['callback-data']);
		},
		error: function(xhr, textStatus, errorThrown){
			generateNotification('Response error: ' + xhr.responseText, 'top-full-width', 'error', 30000);
		},
		complete: function(){
			// Hide the ajax loader
			$('div.loader-holder').fadeOut();

			// Remove the readonly/disabled attribute from inputs and buttons
			if(removeDisable){
				$('input[type="submit"], button[type="submit"], input').removeAttr('disabled');
				$('form input, form select').removeAttr('readonly');
				$('select').select2('enable');
			}
		}
	});

return pContinue;
}

/**
*	Function to update the url without refreshing
*	@url:				The extension to add to after the website url without trailing slash
*/
function updateUrl(url){
	history.pushState('data', '', window.location.protocol + "//" + window.location.hostname + url);
}