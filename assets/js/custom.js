// $(document).ready(function() {
//   $('#menu, #head').localScroll({
//     hash: true,
//     onAfterFirst: function() {
//       $('html, body').scrollTo({
//         top: '-=25px'
//       }, 'fast');
//     }
//   });
//   $('.sneek-it').click(function(event) {
//     event.preventDefault();
//     $.scrollTo($('#free-samples').offset().top - 80, 1500);
//   });
//
//   $('#button-send').click(function(event) {
//     $('#button-send').html('Sending...');
//     event.preventDefault();
//     $('html, body').scrollTo($('#sign'), 'fast');
//     $.ajax({
//       type: 'POST',
//       url: 'https://unbeleadsable.com/core.php',
//       data: $('#signUpForm').serialize(),
//       datatype: 'json',
//       success: function(res) {
//         console.log(res);
//         if (res.success == '1') {
//           if (res.lang == 'EN') window.location = "/thank_you";
//           else
//             window.location = "/fr/thank_you";
//         } else {
//           $('#button-send').html('<i class="fa fa-send"></i> Submit');
//           $('#error').html(res.msg);
//           $('#error').show();
//           $('#success').hide();
//         }
//       },
//       error: function(e) {
//         $('#button-send').html('<i class="fa fa-send"></i> Submit');
//         $('#error').show("Database Error. Please standby!");
//         console.log(e);
//       }
//     });
//   });
//
//   $('#button-send-contact').click(function(event) {
//     $(this).html('Sending...');
//     event.preventDefault();
//     $('html, body').scrollTo($('#contact'), 'fast');
//     $.ajax({
//       type: 'POST',
//       url: 'https://unbeleadsable.com/core.php',
//       data: $('#contactForm').serialize(),
//       datatype: 'json',
//       success: function(res) {
//         console.log(res);
//         if (res.success == '1') {
//           $('#button-send-contact').html('<i class="fa fa-send"></i> Submit');
//           $('#successContact').show();
//           $('#errorContact').hide();
//         } else {
//           $('#button-send-contact').html('<i class="fa fa-send"></i> Submit');
//           $('#errorContact').html(res.msg);
//           $('#errorContact').show();
//           $('#successContact').hide();
//         }
//       },
//       error: function(e) {
//         $('#button-send-contact').html('<i class="fa fa-send"></i> Submit');
//         $('#errorContact').show("Database Error. Please standby!");
//         console.log(e);
//       }
//     });
//   });
//
//   $('#button-pay').click(function(event) {
//     $('#button-pay').html('Paying...');
//     event.preventDefault();
//     $('html, body').scrollTo($('#payment'), 'fast');
//     $.ajax({
//       type: 'POST',
//       url: 'https://unbeleadsable.com/core.php',
//       data: $('#payForm').serialize(),
//       datatype: 'json',
//       success: function(res) {
//         if (res.success == '1') {
//           $('#button-send').html('<i class="fa fa-send"></i> Process');
//           $('#success').show();
//           $('#error').hide();
//         } else {
//           $('#button-send').html('<i class="fa fa-send"></i> Process');
//           $('#error').html(res.msg);
//           $('#error').show();
//           $('#success').hide();
//         }
//       },
//       error: function(e) {
//         $('#button-send').html('<i class="fa fa-send"></i> Process');
//         $('#error').show();
//       }
//     });
//   });
// });
//
// function valemail(email) {
//   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//   return re.test(email);
// }


$(document).ready(function() {

  $('#menu, #head').localScroll({
    hash: true,
    onAfterFirst: function() {
      $('html, body').scrollTo({
        top: '-=25px'
      }, 'fast');
    }
  });
  $('.scrollToSign').on('click', function() {
    $(window).scrollTop($('#contact-us').offset().top - 100);
    //$.scrollTo($('#contact-us').offset().top-80, 1500);
  });
  $('.scrollToVideo').on('click', function() {
    $(window).scrollTop($('#book-features').offset().top - 100);
    //$.scrollTo($('#book-features').offset().top-80, 1500);
  });

  $('#button-send').click(function(event) {
    $('#button-send').html('Sending...');
    event.preventDefault();

    $('html, body').scrollTo($('#sign'), 'fast');
    $.ajax({
      type: 'POST',
      url: 'https://unbeleadsable.com/core.php',
      data: $('#signUpForm').serialize(),
      datatype: 'json',
      success: function(res) {
        if (res.success == '1') {
          if (res.lang == 'EN')
            window.location = "/thank_you";
          else
            window.location = "/fr/thank_you";

        } else {
          $('#button-send').html('<i class="fa fa-send"></i> Submit');
          $('#error').html(res.msg);
          $('#error').show();
          $('#success').hide();
        }
      },
      error: function(e) {
        $('#button-send').html('<i class="fa fa-send"></i> Submit');
        $('#error').show("Database Error. Please standby!");
        console.log(e);
      }
    });
  });

	function contactUsHandler(value1, value2) {
			$('html, body').scrollTo($('#contact'), 'fast');
			$.ajax({
				type: 'POST',
				url: 'https://unbeleadsable.com/core.php',
				data: $('#contactForm' + value2).serialize(),
				datatype: 'json',
				success: function(res) {
					console.log(res);
					if (res.success == '1') {
						$('#button-send-contact' + value1).html('<i class="fa fa-send"></i> Submit');
						$('#successContact' + value2).show();
						$('#errorContact' + value2).hide();
					} else {
						$('#button-send-contact' + value1).html('<i class="fa fa-send"></i> Submit');
						$('#errorContact' + value2).html(res.msg);
						$('#errorContact' + value2).show();
						$('#successContact' + value2).hide();
					}
				},
				error: function(e) {
					$('#button-send-contact' + value1).html('<i class="fa fa-send"></i> Submit');
					$('#errorContact' + value2).show("Database Error. Please standby!");
					console.log(e);
				}
			});

	}
	// Jquery contact Us page handler
	$('#button-send-contact').click(function(event) {
		$(this).html('Sending...');
		event.preventDefault();

		contactUsHandler("", "");
	});

	// Jquery contact popup handler
	$('#button-send-contact-popup').click(function(event) {
		$(this).html('Sending...');
		event.preventDefault();

		contactUsHandler("-popup", "Popup");
	});

  $('#button-pay').click(function(event) {
    $('#button-pay').html('Paying...');
    event.preventDefault();

    $('html, body').scrollTo($('#payment'), 'fast');
    $.ajax({
      type: 'POST',
      url: 'https://unbeleadsable.com/core.php',
      data: $('#payForm').serialize(),
      datatype: 'json',
      success: function(res) {
        if (res.success == '1') {
          $('#button-send').html('<i class="fa fa-send"></i> Process');
          $('#success').show();
          $('#error').hide();
        } else {
          $('#button-send').html('<i class="fa fa-send"></i> Process');
          $('#error').html(res.msg);
          $('#error').show();
          $('#success').hide();
        }
      },
      error: function(e) {
        $('#button-send').html('<i class="fa fa-send"></i> Process');
        $('#error').show();
      }
    });
  });


});


function valemail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
