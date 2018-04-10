<?php include("header.php") ?>

  <!-- HEADER
  ============================================== -->
  <header class="home-header contact" style="height: 300px;"><h1 style="padding-top: 100px; font-size: 80px;">Contact</h1></header>

  <!-- CONTENT
  ============================================== -->
  <div id="contact" class="content" role="main">
    <div class="section bg-shiney padding-sm">
        <div class="container">
          <div class="row text-center">
            <div class="col-sm-8">
              <h5>PLEASE COMPLETE THE FORM BELOW IN ORDER TO CONTACT US</h5>
              <br>
              <form id="contactForm">
                <input type="hidden" name="contactUs">
                <input type="hidden" name="lang" value="EN">
                <div class="col-sm-12">
                  <div class="form-group">
                    <input class="form-control" type="text" name="name" id="inputName1" placeholder="Your Name.." required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="email" name="email" id="inputEmail1" placeholder="Email Address.." required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="phone" id="inputPhone1" placeholder="Phone Number.." required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="hidden" name="human" id="inputHuman1">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <textarea class="form-control" rows="7" name="message" id="inputMessage1" placeholder="Your Message.." required></textarea>
                  </div>
                </div>
                <div class="col-xs-12">
                  <a id="button-send-contact" class="btn btn-primary btn-lg" type="submit">
                    <i class="fa fa-send"></i> Submit
                  </a>
                </div>
                <div class="col-xs-12 text-center">
                  <div id="successContact">Message sent! We'll get in touch with you shortly.</div>
                  <div id="errorContact"></div>
                </div>

              </form>
            </div>
            <div class="col-sm-4 text-left">
              <h5 class="">Unbeleadsable.com Head Office</h5>
              <br>
              <div class="">
                <p><i class="fa fa-plus-square fa-fw"></i>4420 Levesque E. Suite #100</p>
                <p><i class="fa fa-plus-square fa-fw"></i>Laval, Québec, CA H7C 2R1</p>
                <p><i class="fa fa-phone-square fa-fw"></i>+1 (514) 923-5323</p>
                <p><i class="fa fa-pencil-square fa-fw"></i>support@unbeleadsable.com</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <section style="padding: 0;">
    <div id="map" style="height: 350px;"></div>
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
  </section>

<?php include("footer.php") ?>