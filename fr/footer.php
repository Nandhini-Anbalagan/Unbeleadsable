<!-- FOOTER
  ============================================== -->
  <footer class="footer">

    <!-- Footer Widgetized -->
    <div class="footer-widgets">
      <div class="container">
        <div class="row">
          <div class="col-md-4 widget widget-contact">
            <h5 class="widget-title">SIEGE SOCIALE UNBELEADSABLE.COM</h5>
            <div class="list-group">
              <p class="list-group-item"><i class="fa fa-plus-square fa-fw"></i>4420 Levesque E. Suite #100</p>
              <p class="list-group-item"><i class="fa fa-plus-square fa-fw"></i>Laval, Québec, CA H7C 2R1</p>
              <p class="list-group-item"><i class="fa fa-phone-square fa-fw"></i>+1 (514) 923-5323</p>            
              <p class="list-group-item"><i class="fa fa-pencil-square fa-fw"></i>support@unbeleadsable.com</p>
            </div>
            <p><a href="#" style="display:inline-block">Politique de confidentialité et Conditions générales</a></p>
          </div>                
          <div class="col-md-8 widget widget-info">
            <h4 class="widget-title">À propos de Unbeleadsable.com</h4>
            <p class="text-justify">UnbeLEADSable.com est une société établi pour les agents immobiliers, les courtiers et les propriétaires pour générer les prospects vendeurs immobilier de manière la plus efficace possible. Avec sa plate-forme de classe mondiale multilingue et pour les utilisateurs et pour les perspectives, Unbeleadsable est un pionnier dans la saisie de perspectives.  Elle fait attirer plus de clients prospects avec cette caractéristique unique. Cela permet Unbeleadsable d'assurer autant de prospects potentiels possibles à la disposition de ses clients au niveau mondial. Avec son système de suivi automatisé et un système d'analyse prédictive, Unbeleadsable peut segmenter et cibler la meilleure audience de perspective pour assurer les perspectives de la plus haute qualité à nos clients. Unbeleadsable aide les professionnels de l'immobilier générer des prospects tout en construisant une ligne de conduite  d'acheteurs et de vendeurs actuel et à venir.</p>
          </div>                                        
        </div>
      </div>
    </div>

    <!-- Footer Copyright -->
    <div class="footer-copyright">
      <div class="container">
        <div class="row">     
          <div class="col-sm-8">
            <p class="text-dark no-mg pd-t-xs"><?php echo Date("Y"); ?> © Unbeleadsable</p>
          </div>
          <div class="col-sm-4">
            <div class="links text-right">
              <!-- <a class="btn-empty btn-xl no-pd mg-r-sm" href="#"><i class="fa fa-lg fa-twitter-square"></i></a> -->
              <a class="btn-empty btn-xl no-pd mg-r-sm" href="https://www.facebook.com/unbeleadsable/" target="_blank"><i class="fa fa-lg fa-facebook-square"></i></a>
              <!-- <a class="btn-empty btn-xl no-pd mg-r-sm" href="#"><i class="fa fa-lg fa-linkedin-square"></i></a> -->
              <!-- <a class="btn-empty btn-xl no-pd mg-r-sm" href="#"><i class="fa fa-lg fa-vimeo-square"></i></a> -->
              <!-- <a class="btn-empty btn-xl no-pd mg-r-sm" href="#"><i class="fa fa-lg fa-flickr"></i></a>                        -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- SCRIPTS
  ============================================== -->
  <!-- Core Js -->
  <script src="assets/js/jquery-1.8.2.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <!-- Plugins -->
  <script src="assets/js/vendor/waypoints.min.js"></script>
  <script src="assets/js/vendor/counterup.min.js"></script>
  <script src="assets/js/vendor/tinyscroll.min.js"></script>
  <script src="assets/js/vendor/wow.min.js"></script>
  <script src="assets/js/vendor/countdown.min.js"></script> 
  <script src="assets/js/vendor/validator.min.js"></script>   
  <script src="assets/js/vendor/retina.min.js"></script>
  <script src="assets/js/vendor/video-js.js"></script>
  <script src="assets/js/vendor/featherlight.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.nicescroll.js"></script>
  <script type="text/javascript" src="assets/js/jquery.localscroll-1.2.7.js"></script>
  <script type="text/javascript" src="assets/js/jquery.colorbox-min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.scrollTo-1.4.3.1.js"></script>
  <script type="text/javascript" src="assets/js/ccvalidations.js"></script>
  <!-- Main Js -->
  <script src="assets/js/flatbook.js"></script>
  <script type="text/javascript" src="assets/js/custom.js"></script>
  <script>
    $(document).ready(function(){
      $(".ccinfo").show();
      $("a[rel='hint']").colorbox({
        scrolling:false, 
        width: '500px', 
        height: '407px'
      });

      $("input[name=ccn]").bind('paste', function(e) {
        var el = $(this);
        setTimeout(function() {
          var text = $(el).val();
          resetCCHightlight();
          checkNumHighlight(text);
        }, 100);
      });

      $("#agent").change(function() {
      window.location.href="manuel_subscription?id="+$(this).val();
    });

     $("#agent_manuel").change(function() {
      window.location.href="manuel_fees?id="+$(this).val();
    });
    });
  </script>
  <noscript>
    <style>
      .noscriptCase { display:none; }
      #accordion .pane { display:block;}
    </style>
  </noscript>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-45266806-12', 'auto');
    ga('send', 'pageview');

  </script>

</body>
</html>