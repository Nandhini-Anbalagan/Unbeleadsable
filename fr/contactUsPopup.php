<!-- Bootstrap contact us popup modal -->
<div class="modal fade" id="contactUsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Contactez nous</h4>
    </div>
    <div class="modal-body row">
      <!-- Contact Form -->
      <div id="formDiv">
        <form id="contactForm" class="container-fluid">
          <div class="row">
            <h5 class="col-6 formHeader">Veuillez compléter le formulaire ci-dessous pour vous contacter</h5>
          </div>
          <br>
          <input type="hidden" name="contactUs">
          <input type="hidden" name="lang" value="EN">
          <div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="text" name="name" id="inputName1" placeholder="Votre nom.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="email" name="email" id="inputEmail1" placeholder="Votre adresse.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="text" name="phone" id="inputPhone1" placeholder="Votre téléphone.." required>
            </div>
            <div class="form-group row">
              <input class="form-control col-sm-6" type="hidden" name="human" id="inputHuman1">
            </div>
          </div>
          <div class=" form-group row">
            <textarea class="form-control col-sm-6" rows="7" name="message" id="inputMessage1" placeholder="Votre Message.." required></textarea>
          </div>
          <div id="btnSubmit" class="">
            <a id="button-send-contact" class="btn btn-primary btn-lg" type="submit">
              <i class="fa fa-send"></i> Envoyer
            </a>
          </div>
          <div class="row text-center">
            <div id="successContact" class="col-sm-6">Message envoyé! Nous vous contacterons sous peu.</div>
            <div id="errorContact" class="col-sm-6"></div>
          </div>
        </form>
      </div>

      <!-- Contact Info -->
      <div id="contactPopupInfo" class="container">
        <h5 class="formHeader">Siege sociale Unbeleadsable.com</h5>
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
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSOaCXDTQy_VXFflgZg19OwFqLIUmZ1eM&callback=initMap">
</script>


<link rel="stylesheet" href="/assets/css/contactUsPopup.css">
