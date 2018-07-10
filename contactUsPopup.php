<!-- Bootstrap contact us popup modal -->
<div class="modal fade" id="contactUsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Contact Us</h4>
    </div>
    <div class="modal-body row">
      <!-- Contact Form -->
      <div id="formDiv">
        <form id="contactFormPopup" class="container-fluid">
          <div class="row">
            <h5 class="col-6 formHeader">Please complete the form below in order to contact us</h5>
          </div>
          <br>
          <input type="hidden" name="contactUs">
          <input type="hidden" name="lang" value="EN">
          <div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="text" name="name" id="inputName1" placeholder="Your Name.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="email" name="email" id="inputEmail1" placeholder="Email Address.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="text" name="phone" id="inputPhone1" placeholder="Phone Number.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="hidden" name="human" id="inputHuman1">
            </div>
          </div>
          <div class=" form-group row">
            <textarea class="form-control col-sm-6" rows="7" name="message" id="inputMessage1" placeholder="Your Message.." required></textarea>
          </div>
          <div id="btnSubmit" class="">
            <a id="button-send-contact-popup" class="btn btn-primary btn-lg" type="submit">
              <i class="fa fa-send"></i> Submit
            </a>
          </div>
          <div class="row text-center">
            <div id="successContactPopup" hidden="true">Message sent! We'll get in touch with you shortly.</div>
            <div id="errorContactPopup"></div>
          </div>
        </form>
      </div>

      <!-- Contact Info -->
      <div id="contactPopupInfo" class="container">
        <h5 class="formHeader">Unbeleadsable.com Head Office</h5>
        <div id="contactPopupP" class="contactInfo">
          <p><i class="fa fa-plus-square fa-fw col-sm-6"></i>4420 Levesque E. Suite #100</p>
          <p>Laval, Québec, CA H7C 2R1</p>
          <p><i class="fa fa-phone-square fa-fw col-sm-6"></i><a href="tel:+15149235323" class="ahover">1 (514) 923-5323</a></p>
          <p><i class="fa fa-pencil-square fa-fw col-sm-6"></i><a href="mailto:support@unbeleadsable.com" class="ahover">support@unbeleadsable.com</a></p>
        </div>
        <!-- Google Maps -->
        <div id="map"></div>
      </div>
    </div>
  </div>
</div>
</div>

<script>
  function initMap() {
    var uluru = {lat: 45.6014996, lng: -73.6502636};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      center: uluru,
      scrollwheel: false
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
    map.addListener('click', function(){
      map.setOptions({scrollwheel: true});
    });
    map.addListener('mouseout', function(){
      map.setOptions({scrollwheel: false});
    });
  }
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8hEM4oF88dSUvW3MidSqSlbDf4oxwRXI&callback=initMap">
</script>


<link rel="stylesheet" href="/assets/css/contactUsPopup.css">
