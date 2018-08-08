<?php 
	# Function to auload an object class

$appsecret_proof = hash_hmac(
  'sha256',
  'EAAElyDlIGywBAGKC1pMwQLLHa7x6eHVFvKuvYLZBV2PXp33GzVS2KbudOypig27uxZBBFFLDGHZCn9ewStziSZAq98FW6l6w4P4DIZBo9IZCMKYiZACqZBBc93q2P3SQ9xZBIEfziutvM3gtAEbVg9Vzfaf2uWW1R1r4JL3eouAz6ZCAZDZD',
  "b21e02302f8fe68f791d7564b5536310"
);
echo $appsecret_proof;

	/*$access_token = "13cdf3681a872cbd15ed749a9c342276";
	$app_id = "323016861424428";
	$app_secret = "b21e02302f8fe68f791d7564b5536310";
	$account_id = 'act_239134311';
	
	include("models/FacebookAds/Api.php");

	Api::init($app_id, $app_secret, $access_token);
	$api = Api::instance();*/

?>

