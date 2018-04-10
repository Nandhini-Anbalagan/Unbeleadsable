<?php
include_once("header.php");

require_once '../app/models/phpMailer/vendor/autoload.php';

// Or, using an anonymous function as of PHP 5.3.0
spl_autoload_register(function ($class) {
    require_once("../app/models/$class.class.php");
});

require_once("../app/models/paypal/config.php");

date_default_timezone_set('America/Montreal');

$db = new DBManager();
$lang = "FR";

$lead = $db->getAgentLeadsByID(IDObfuscator::decode($_GET['id']));

$name = explode(" ",$lead['lead_name']);
$last = array_pop($name);
$first = implode(" ", $name);

if(!isset($mess)){ $mess = ""; }

//REQUEST VARIABLES 
$amount = (!empty($_REQUEST["amount"]))?strip_tags(str_replace("'","`",$_REQUEST["amount"])):'150';
$fname = (!empty($_REQUEST["fname"]))?strip_tags(str_replace("'","`",$_REQUEST["fname"])):$first;
$lname = (!empty($_REQUEST["lname"]))?strip_tags(str_replace("'","`",$_REQUEST["lname"])):$last;
$email = (!empty($_REQUEST["email"]))?strip_tags(str_replace("'","`",$_REQUEST["email"])):$lead['lead_email'];
$address = (!empty($_REQUEST["address"]))?strip_tags(str_replace("'","`",$_REQUEST["address"])):'';
$city = (!empty($_REQUEST["city"]))?strip_tags(str_replace("'","`",$_REQUEST["city"])):'';
$country = (!empty($_REQUEST["country"]))?strip_tags(str_replace("'","`",$_REQUEST["country"])):'CA';
$state = (!empty($_REQUEST["state"]))?strip_tags(str_replace("'","`",$_REQUEST["state"])):'';
$zip = (!empty($_REQUEST["zip"]))?strip_tags(str_replace("'","`",$_REQUEST["zip"])):'';


//FORM SUBMISSION PROCESSING 
if(!empty($_POST["process"]) && $_POST["process"]=="yes")
  require_once("../app/models/paypal/form.processing.php");
 
include_once "../app/models/paypal/javascript.validation.php"; 

?>


  <!-- HEADER
  ============================================== -->
  <header class="home-header" style="height: 300px;"><h1 style="padding-top: 100px;font-size: 80px;">Paiement</h1></header>

  <!-- CONTENT
  ============================================== -->
  <div id="new-payment-wrapper" class="conten" role="main">
  <!-- Pricing Offers-->
    <section class="bg-light" id="pricing-offers">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Tarification</h2>
          <p class="section-lead">Plan de tarification pour vendeurs perspectifs</p>  
          <div class="divider"></div>        
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="pricing-table wow fadeInUp">
              <div class="panel-heading">
                <h4 class="plan-title">FRAIS D'INSTALLATION 
                  <span class="hidden-sm">PAIEMENT UNIQUE</span>
                </h4>
                <p class="plan-price" style="font-size: 35px;">
                  <sup>$</sup>49.99 USD
                </p>
              </div>
              <div class="panel-body">
                <ul class="plan-features">
                  <li>Page d’accueil</li>
                  <li>Configuration de panneau</li> 
                  <li>Et bien plus</li>
                </ul>
              </div>
              <div class="panel-footer">
                <!-- <p><a class="btn btn-primary" href="#">Purchase now</a></p> -->
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="pricing-table emphasized wow fadeInDown">
              <div class="panel-heading">
                <h4 class="plan-title">FRAIS D'ABONNEMENT 
                  <span class="hidden-sm">RÉCURRENT CHAQUE MOIS</span>
                </h4>
                <p class="plan-price" style="font-size: 35px;">
                  <sup>$</sup>99.99 USD
                </p>
              </div>
              <div class="panel-body">
                <ul class="plan-features">
                  <li>Outil gestion de relations clients</li>
                  <li>Suivi</li>
                  <li>Outils marketing</li>
                  <li>Communauté de soutien</li>
                  <li>Et bien plus</li> 
                </ul>
              </div>
              <div class="panel-footer">
                <!-- <p><a class="btn btn-primary btn-lg" href="#">Purchase now</a></p> -->
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="pricing-table wow fadeInUp">
              <div class="panel-heading">
                <h4 class="plan-title">CAMPAGNE PUBLICITAIRE 
                  <span class="hidden-sm">OBTENIR PLUS DE PROSPECTS SUR LES MEDIAS SOCIAUX</span>
                </h4>
                <p class="plan-price" style="font-size: 35px;">
                  Budget Publicitaire
                </p>
              </div>
              <div class="panel-body">
                <ul class="plan-features">
                  <li>Annoncez sur Facebook</li>
                  <li>Annoncez sur Twitter</li>
                  <li>Annoncez sur Instagram</li>
                </ul>
              </div>
              <div class="panel-footer">
                <!-- <p><a class="btn btn-primary" href="#">Purchase now</a></p> -->
              </div>
            </div>
          </div>
        </div>   
      </div>
    </section>
	<section class="container-fluid">
	    <div class="row payments">
	      <div class="col-sm-6 col-sm-offset-3">
	        <?php if($lead): ?>
	        <?php echo $mess; ?>
	        <form id="ff1" name="ff1" method="post" action="" enctype="multipart/form-data" onsubmit="return checkForm();" class="pppt_form">
	            <input type="hidden" name="lead_id" value="<?php echo $_GET['id'] ?>">
	            <input type="hidden" name="user_id" value="<?php echo IDObfuscator::encode($lead['user_id']) ?>">
	            
	            <!-- PAYMENT BLOCK -->
	            <h2 class="current">Information du paiement</h2>
	            <div class="pane" style="display:block">
	              <table class="table_custom" style="width: 100%">
	                <tr>
	                  <td><h4>Frais d'installation <small>Paiement unique/small></h4></td>
	                  <td><h4>$<?php echo INSTALLATION ?> USD</h4></td>
	                </tr>

	                <tr>
	                  <td><h4>Frais d'abonnement <small>Récurrent chaque mois</small></h4></td>
	                  <td><h4>$<?php echo SUBSCRIPTION ?> USD</h4></td>
	                </tr>

	                <tr>
	                  <td><h4>Campaigne publicitaire <small>Récurrent chaque mois</small></h4></td>
	                  <td><h4>$ <input class="form-control" name="amount" id="amount" type="number" min="150" step="50" value="<?php echo $amount;?>" onkeyup="checkFieldBack(this);" style="display:inline; width: 50%; margin: 0;"/> USD</h4></td>
	                </tr>
	              </table>

	            </div>
	            <!-- PAYMENT BLOCK -->

	            <!-- BILLING BLOCK -->
	            <h2>Détails de facturation</h2>
	            <div class="pane">
	              <div class="form-group">
	                <label>Prénom:</label>
	                <input class="form-control" name="fname" id="fname" type="text"  value="<?php echo $fname;?>" onkeyup="checkFieldBack(this);" />
	              </div>
	              <div class="form-group">
	                <label>Nom:</label>
	                <input class="form-control" name="lname" id="lname" type="text"  value="<?php echo $lname;?>" onkeyup="checkFieldBack(this);" />
	              </div>
	              <div class="form-group">
	                <label>Courriel:</label>
	                <input class="form-control" name="email" id="email" type="text"  value="<?php echo $email;?>" onkeyup="checkFieldBack(this);" />
	              </div>
	              <div class="form-group">
	                <label>Adresse:</label>
	                <input class="form-control" name="address" id="address" placeholder="Enter your address" type="text"  value="<?php echo $address;?>" onkeyup="checkFieldBack(this);" />
	              </div>
	              <div class="halfDiv" style="margin-right: 10px;">
	                <div class="form-group">
	                  <label>Ville:</label>
	                  <input class="form-control" name="city" id="city" type="text"  value="<?php echo $city;?>" onkeyup="checkFieldBack(this);" />
	                </div>
	                <div class="form-group">
	                  <label>Pays:</label>
	                  <select class="form-control" name="country" id="country" onchange="checkFieldBack(this);"> 
	                   <option value="">Veuillez sélectionner</option> 
	                   <option value="US" <?php echo $country=="US"?"selected":""?>>United States</option>
	                   <option value="CA" <?php echo $country=="CA"?"selected":""?>>Canada</option>
	                 </select>
	                </div>

	             </div>

	             <div class="halfDiv">
	              <div class="form-group">
	               <label>État/Province:</label>
	               <select class="form-control" name="state" id="state" onchange="checkFieldBack(this);">
	                <option value="">Veuillez sélectionner</option>
	                <optgroup label="Canadian Provinces">
	                 <option value="AB"  <?php echo $state=="AB"?"selected":""?>>Alberta</option>
	                 <option value="BC"  <?php echo $state=="BC"?"selected":""?>>British Columbia</option>
	                 <option value="MB"  <?php echo $state=="MB"?"selected":""?>>Manitoba</option>
	                 <option value="NB"  <?php echo $state=="NB"?"selected":""?>>New Brunswick</option>
	                 <option value="NF"  <?php echo $state=="NF"?"selected":""?>>Newfoundland</option>
	                 <option value="NT"  <?php echo $state=="NT"?"selected":""?>>Northwest Territories</option>
	                 <option value="NS"  <?php echo $state=="NS"?"selected":""?>>Nova Scotia</option>
	                 <option value="NVT"  <?php echo $state=="NVT"?"selected":""?>>Nunavut</option>
	                 <option value="ON"  <?php echo $state=="ON"?"selected":""?>>Ontario</option>
	                 <option value="PE"  <?php echo $state=="PE"?"selected":""?>>Prince Edward Island</option>
	                 <option value="QC"  <?php echo $state=="QC"?"selected":""?>>Quebec</option>
	                 <option value="SK"  <?php echo $state=="SK"?"selected":""?>>Saskatchewan</option>
	                 <option value="YK"  <?php echo $state=="YK"?"selected":""?>>Yukon</option>
	               </optgroup>
	               <optgroup label="US States">
	                 <option value="AL"  <?php echo $state=="AL"?"selected":""?>>Alabama</option>
	                 <option value="AK"  <?php echo $state=="AK"?"selected":""?>>Alaska</option>
	                 <option value="AZ"  <?php echo $state=="AZ"?"selected":""?>>Arizona</option>
	                 <option value="AR"  <?php echo $state=="AR"?"selected":""?>>Arkansas</option>
	                 <option value="BVI"  <?php echo $state=="BVI"?"selected":""?>>British Virgin Islands</option>
	                 <option value="CA"  <?php echo $state=="CA"?"selected":""?>>California</option>
	                 <option value="CO"  <?php echo $state=="CO"?"selected":""?>>Colorado</option>
	                 <option value="CT"  <?php echo $state=="CT"?"selected":""?>>Connecticut</option>
	                 <option value="DE"  <?php echo $state=="DE"?"selected":""?>>Delaware</option>
	                 <option value="FL"  <?php echo $state=="FL"?"selected":""?>>Florida</option>
	                 <option value="GA"  <?php echo $state=="GA"?"selected":""?>>Georgia</option>
	                 <option value="GU"  <?php echo $state=="GU"?"selected":""?>>Guam</option>
	                 <option value="HI"  <?php echo $state=="HI"?"selected":""?>>Hawaii</option>
	                 <option value="ID"  <?php echo $state=="ID"?"selected":""?>>Idaho</option>
	                 <option value="IL"  <?php echo $state=="IL"?"selected":""?>>Illinois</option>
	                 <option value="IN"  <?php echo $state=="IN"?"selected":""?>>Indiana</option>
	                 <option value="IA"  <?php echo $state=="IA"?"selected":""?>>Iowa</option>
	                 <option value="KS"  <?php echo $state=="KS"?"selected":""?>>Kansas</option>
	                 <option value="KY"  <?php echo $state=="KY"?"selected":""?>>Kentucky</option>
	                 <option value="LA"  <?php echo $state=="LA"?"selected":""?>>Louisiana</option>
	                 <option value="ME"  <?php echo $state=="ME"?"selected":""?>>Maine</option>
	                 <option value="MP"  <?php echo $state=="MP"?"selected":""?>>Mariana Islands</option>
	                 <option value="MPI"  <?php echo $state=="MPI"?"selected":""?>>Mariana Islands (Pacific)</option>
	                 <option value="MD"  <?php echo $state=="MD"?"selected":""?>>Maryland</option>
	                 <option value="MA"  <?php echo $state=="MA"?"selected":""?>>Massachusetts</option>
	                 <option value="MI"  <?php echo $state=="MI"?"selected":""?>>Michigan</option>
	                 <option value="MN"  <?php echo $state=="MN"?"selected":""?>>Minnesota</option>
	                 <option value="MS"  <?php echo $state=="MS"?"selected":""?>>Mississippi</option>
	                 <option value="MO"  <?php echo $state=="MO"?"selected":""?>>Missouri</option>
	                 <option value="MT"  <?php echo $state=="MT"?"selected":""?>>Montana</option>
	                 <option value="NE"  <?php echo $state=="NE"?"selected":""?>>Nebraska</option>
	                 <option value="NV"  <?php echo $state=="NV"?"selected":""?>>Nevada</option>
	                 <option value="NH"  <?php echo $state=="NH"?"selected":""?>>New Hampshire</option>
	                 <option value="NJ"  <?php echo $state=="NJ"?"selected":""?>>New Jersey</option>
	                 <option value="NM"  <?php echo $state=="NM"?"selected":""?>>New Mexico</option>
	                 <option value="NY"  <?php echo $state=="NY"?"selected":""?>>New York</option>
	                 <option value="NC"  <?php echo $state=="NC"?"selected":""?>>North Carolina</option>
	                 <option value="ND"  <?php echo $state=="ND"?"selected":""?>>North Dakota</option>
	                 <option value="OH"  <?php echo $state=="OH"?"selected":""?>>Ohio</option>
	                 <option value="OK"  <?php echo $state=="OK"?"selected":""?>>Oklahoma</option>
	                 <option value="OR"  <?php echo $state=="OR"?"selected":""?>>Oregon</option>
	                 <option value="PA"  <?php echo $state=="PA"?"selected":""?>>Pennsylvania</option>
	                 <option value="PR"  <?php echo $state=="PR"?"selected":""?>>Puerto Rico</option>
	                 <option value="RI"  <?php echo $state=="RI"?"selected":""?>>Rhode Island</option>
	                 <option value="SC"  <?php echo $state=="SC"?"selected":""?>>South Carolina</option>
	                 <option value="SD"  <?php echo $state=="SD"?"selected":""?>>South Dakota</option>
	                 <option value="TN"  <?php echo $state=="TN"?"selected":""?>>Tennessee</option>
	                 <option value="TX"  <?php echo $state=="TX"?"selected":""?>>Texas</option>
	                 <option value="UT"  <?php echo $state=="UT"?"selected":""?>>Utah</option>
	                 <option value="VT"  <?php echo $state=="VT"?"selected":""?>>Vermont</option>
	                 <option value="USVI"  <?php echo $state=="USVI"?"selected":""?>>VI  U.S. Virgin Islands</option>
	                 <option value="VA"  <?php echo $state=="VA"?"selected":""?>>Virginia</option>
	                 <option value="WA"  <?php echo $state=="WA"?"selected":""?>>Washington</option>
	                 <option value="DC"  <?php echo $state=="DC"?"selected":""?>>Washington, D.C.</option>
	                 <option value="WV"  <?php echo $state=="WV"?"selected":""?>>West Virginia</option>
	                 <option value="WI"  <?php echo $state=="WI"?"selected":""?>>Wisconsin</option>
	                 <option value="WY"  <?php echo $state=="WY"?"selected":""?>>Wyoming</option>
	               </optgroup>
	               <option value="N/A"  <?php echo $state=="N/A"?"selected":""?>>Other</option>
	             </select>
	             </div>
	             <div class="form-group">
	               <label>ZIP/Code Postal:</label>
	               <input class="form-control" name="zip" id="zip" type="text" class="small-field"  value="<?php echo $zip;?>" onkeyup="checkFieldBack(this);" />
	             </div>
	        </div>
	           

	         </div>
	         <!-- BILLING BLOCK -->

	         <!-- CREDIT CARD BLOCK -->
	         <h2>Informations sur la carte de crédit</h2>
	         <div class="pane cc">
	          <div class="cc_holder">
	          <div class="form-group">
	           <label>Type de carte de crédit:</label>
	           <input name="cctype" type="radio" value="V" class="lft-field" /> <img src="assets/images/ico_visa.jpg" align="absmiddle" class="lft-field cardhide V" />
	           <input name="cctype" type="radio" value="M" class="lft-field" /> <img src="assets/images/ico_mc.jpg" align="absmiddle" class="lft-field cardhide M" />
	           <!-- <input name="cctype" type="radio" value="A" class="lft-field" /> <img src="assets/images/ico_amex.jpg" align="absmiddle" class="lft-field cardhide A" /> -->
	           <?php if($enable_paypal){ ?>
	             <input class="form-control" name="cctype" type="radio" value="D" class="lft-field" /> <img src="assets/images/ico_disc.jpg" align="absmiddle" class="lft-field cardhide D" />
	             <input class="form-control" name="cctype" type="radio" value="PP" class="lft-field isPayPal"  /> <img src="images/ico_paypal.png" width="37" height="11"  align="absmiddle" class="lft-field paypal cardhide PP"  />
	             <?php } ?>
	           </div>
	           </div>
	           <div class="ccinfo">
	           <div class="form-group">
	            <label>Numéro de la carte:</label>
	            <input class="form-control" name="ccn" id="ccn" type="text"  onkeyup="checkNumHighlight(this.value);checkFieldBack(this);noAlpha(this);" value="" onkeypress="checkNumHighlight(this.value);noAlpha(this);" onblur="checkNumHighlight(this.value);" onchange="checkNumHighlight(this.value);" maxlength="16" />
	            </div>
	            <span class="ccresult"></span>
	            <div class="form-group">
	            <label>Nom sur la carte:</label>
	            <input class="form-control" name="ccname" id="ccname" type="text"  onkeyup="checkFieldBack(this);"  />
	            </div>
	            <div class="form-group">
	            <label>Date d'expiration:</label><br>
	            <div class="halfDiv" style="margin-right: 10px;">
	            	<select class="form-control" name="exp1" id="exp1" class="input-field eight" onchange="checkFieldBack(this);">
	            		<option value="">Mois</option>
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
	            <div class="halfDiv">
	            	<select class="form-control" name="exp2" id="exp2" class="input-field eight" onchange="checkFieldBack(this);">
	            		<option value="">Année</option>
	            		<?php echo Functions::getActualYears();   ?>
	            	</select>
	            </div>
	            </div>
	            <div class="form-group">
	            <label>CVV:</label><br>
	            <input class="form-control" style="width: 49%;display: inline-block;" name="cvv" id="cvv" type="text" maxlength="5" class="input-field eight"  onkeyup="checkFieldBack(this);noAlpha(this);"  />
	            <a href="hint.php" rel="hint" class="noscriptCase"><img src="assets/images/ico_question.jpg" align="absmiddle" border="0" /></a>
	            </div>
	            <noscript>
	              <a href="hint.php" target="_blank"><img src="assets/images/ico_question.jpg" align="absmiddle" border="0" /></a>
	            </noscript>
	          </div>

	          <label><input type="checkbox" name="tos" required/>&nbsp;&nbsp;J'ai bien lu les <a href="#" target="_blank">Conditions de services</a></label><br><br>
	          <button class="btn btn-primary btn-lg btn-block" type="submit">
	          <i class="fa fa-next"></i> Continuer
	          </button>
	          <!-- <input type="submit" name="submit" value="Proceed" class="button" style="width:100%;"/> -->
	          <input type="hidden" name="process" value="yes" />  
	        </div>
	        <!-- CREDIT CARD BLOCK -->

	        </form> 
	      <?php else: ?>
	      <h2>Désolé, l'ID de l'agent n'est pas reconnu ...</h2>
	      <?php endif ?>      
	      </div>
	    </div>
	  </div>
	</section>

<?php include("footer.php") ?>
  