<?php
date_default_timezone_set("America/Montreal");

require 'phpMailer/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require 'phpMailer/vendor/autoload.php';

abstract class Functions{
	CONST EMAIL_REGEX = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
		/*
		*	Function to remove any empty string field to null.
		*	@list: the list of data to check passed by reference.
		*/
		public static function convertToNull(Array &$list){
			foreach($list as $key => $value){
				if($value == "")
					$list[$key] = NULL;
			}
		}

		/*
		*	Function to delete a file.
		*	@file: location of the file to delete.
		*/
		public static function deleteFile($file){
			if($file != NULL && $file != ""){
				if(file_exists($file)){
					unlink($file);
					return true;
				}
			}
			return false;
		}

		public static function encode($str){
			$rev1 = strrev($str);
			$base64 = base64_encode($rev1);
			$rot1 = str_rot13($base64);
			$rev2 = strrev($rot1);
			$rot2 = str_rot13($rev2);
			return $rot2;
		}

		public static function decode($str){
			$rot2 = str_rot13($str);
			$rev2 = strrev($rot2);
			$rot = str_rot13($rev2);
			$base64 = base64_decode($rot);
			$rev1 = strrev($base64);
			return $rev1;
		}

		/*
		*   Function to do the actual validation.
		*   @data: array of the post.
		*   @key: name of the key in the array.
		*   @type: type of validation.
		*   @errorMsg: The error message container to update to become the error message to return.
		*/
		private static function doValidateField($data, $key, $type, &$errorMsg){
			if(!isset($data[$key])){
				$errorMsg = "Please make sure the field " . $key . " is set.";
				return false;
			}

			# Reset the error message
			$errorMsg = "-1";

			if(is_numeric($type)){
				if(STATIC::isEmpty($data[$key]))
					$errorMsg = "The ". $data[$key] ." cannot be empty.";
				else if(strlen(trim(strip_tags(html_entity_decode($data[$key])))) < $type)
					$errorMsg = "The ". $data[$key] ." must be at least " . $type . " characters.";
			}

			else if($type == "empty"){
				if(STATIC::isEmpty($data[$key]))
					$errorMsg = "The ". str_replace(array("_fk", "_"), array("", " "), $data[$key]) ." cannot be empty.";
			}


			else if($type == "email"){
				if(STATIC::isEmpty($data[$key]))
					$errorMsg = "The ". str_replace(array("_fk", "_"), array("", " "), $data[$key]) ." cannot be empty.";
				else if (!preg_match(STATIC::EMAIL_REGEX,$data[$key])){
					$errorMsg = $data[$key] . "is not a valid email address.";
				}
			}

			# Unknown validation type
			else
				$errorMsg = "Unknown type: " . $type;

			return $errorMsg == "-1";
		}

		/*
		*	Function to check if a string is empty.
		*	@string: the string to check.
		*	@trim: true to trim white spaces, otherwise false. (Default: true)
		*/
		public static function isEmpty($string, $trim = true){
			return is_null($string) ? true : ($trim ? strlen(trim($string)) < 1 : strlen($string) < 1);
		}

		/*
		*	Function to check if a page is available to guests.
		*	@page: name of the page to check.
		*/
		public static function isPublicPage($page){
			switch($page){
				case "404":
				case "core":
				case "forgot-password":
				case "login":
				case "reset-password":
				case "unsubscribe":
				case "receipt":
				case "payment":
				case "paiement":
				case "index":
				case "fb":
				case "maintenance":
				return true;
				default:
				return false;
			}
		}

		/*
		*	Function to check if a page an admin page.
		*	@page: name of the page to check.
		*/
		public static function isAdminPage($page){
			switch($page){
				case "leads":
				case "areas":
				case "agents":
				case "agent_budget":
				case "invoices":
				case "users":
				case "landings":
				case "emails":
					return true;
				default:
					return false;
			}
		}

		/*
		*   Function to check if a field is valid.
		*   @data: array of the post.
		*   @key: name of the key in the array.
		*   @type: type of validation.
		*   @errorMsg: The error message container to update to become the error message to return.
		*/
		public static function isValidField(Array $data, $key, $type, &$errorMsg){
			return STATIC::doValidateField($data, $key, $type, $errorMsg);
		}

		/*
		*   Function to check if a list of fields is valid
		*   @data: the post array
		*   @key: list of the keys
		*   @type: list of the types of validation
		*   @errorMsg: the container of the error message that the function will return
		*/
		public static function isValidFields(Array $data, Array $keys, Array $types, &$errorMsg){
			if(count($keys) != count($types)){
				$errorMsg = "Please make sure you have the same amount of key and type." ;
				return false;
			}

			# Loop through each keys
			for($i=0; $i<count($keys); $i++){
				$key = $keys[$i];
				$type = $types[$i];

				if(!STATIC::doValidateField($data, $key, $type, $errorMsg))
					return false;
			}

			return true;
		}

		/*
		*	Function to generate a success message that will be displayed on next page load.
		*	Note: recommended to use with redirect
		*/
		public static function generateErrorMessage($message){
			setcookie('error-message', $message, time() + 60 * 60, "/");
		}

		/*
		*	Function to generate a success message that will be displayed on next page load.
		*	Note: recommended to use with redirect
		*/
		public static function generateSuccessMessage($message){
			setcookie('success-message', $message, time() + 60 * 60, "/");
		}

		/*
		*	Function to get the string of a course status.
		*	@status: status of the course
		*/
		public static function getSellingIn($id, $lang){
			if($lang == "EN"){
				switch($id){
					case 0:
					return "Not Selected";
					case 1:
					return "1-3 Months";
					case 2:
					return "3-6 Months";
					case 3:
					return "6-12 Months";
					case 4:
					return "More than 12 Months";
					case 5:
					return "Just Curious";
					case 6:
					return "Refinancing";

				}
			}else{
				switch($id){
					case 0:
					return "Non séléctionné";
					case 1:
					return "1-3 Mois";
					case 2:
					return "3-6 Mois";
					case 3:
					return "6-12 Mois";
					case 4:
					return "Plus que 12 Mois";
					case 5:
					return "Juste curieux";
					case 6:
					return "Refinancement";

				}
			}
		}

		public static function displaySellingIn($id,$lang){
			$options = "";

			if($lang == "EN"){
				$options = "<option value='0' ". $id == 0?'selected':'' ." data-id='".$id."'>Not Selected</option>
				<option value='1' ". $id == 1?'selected':'' ." data-id='".$id."'>1-3 Months</option>
				<option value='2' ". $id == 2?'selected':'' ." data-id='".$id."'>3-6 Months</option>
				<option value='3' ". $id == 3?'selected':'' ." data-id='".$id."'>6-12 Months</option>
				<option value='4' ". $id == 4?'selected':'' ." data-id='".$id."'>More than 12 Months</option>
				<option value='5' ". $id == 5?'selected':'' ." data-id='".$id."'>Just Curious</option>
				<option value='6' ". $id == 6?'selected':'' ." data-id='".$id."'>Refinancing</option>";
			}else{
				$options = "<option value='0' ". $id == 0?'selected':'' ." data-id='".$id."'>Non séléctionné</option>
				<option value='1' ". $id == 1?'selected':'' ." data-id='".$id."'>1-3 Mois</option>
				<option value='2' ". $id == 2?'selected':'' ." data-id='".$id."'>3-6 Mois</option>
				<option value='3' ". $id == 3?'selected':'' ." data-id='".$id."'>6-12 Mois</option>
				<option value='4' ". $id == 4?'selected':'' ." data-id='".$id."'>Plus que 12 Mois</option>
				<option value='5' ". $id == 5?'selected':'' ." data-id='".$id."'>Par curiosité</option>
				<option value='6' ". $id == 6?'selected':'' ." data-id='".$id."'>Refinancement</option>";
			}

			return $options;
		}

		public static function search_array($needle, $haystack) {
			 if(in_array($needle, $haystack))
				return true;

			 foreach($haystack as $element)
				if(is_array($element) && self::search_array($needle, $element))
					return true;

			return false;
		}

		//TODO: fix me when there time please
		public static function blacklist_lookup($needle, $haystack) {
			 foreach($haystack as $element)
				$element['emails'] == $needle;
					return true;

			return false;
		}

		public static function getActualYears($v = ""){
			$html = "";
			for($i=date("Y");$i<date("Y", strtotime(date("Y")." +10 years"));$i++){
				$selected = $i == $v?'selected':'';
				$html .= '<option value="'.$i.'"'. $selected .'>'.$i.'</option>';
			}
			return $html;
		}

		public static function monthArray(){
			$months = array();
			$months["01"] = "January";
			$months["02"] = "February"; 
			$months["03"] = "March"; 
			$months["04"] = "April"; 
			$months["05"] = "May"; 
			$months["06"] = "June"; 
			$months["07"] = "July"; 
			$months["08"] = "August"; 
			$months["09"] = "September"; 
			$months["10"] = "October"; 
			$months["11"] = "November"; 
			$months["12"] = "December"; 

			return $months;
		}

		// function static detectCardType($number){
		// 	$t = "";

		// 	$types = array(
		// 		'V'=>"/^4[0-9]{12}(?:[0-9]{3})?$/",
		// 		'M'=>"/^5[1-5][0-9]{14}$/",
		// 		'A'=>"/^3[47][0-9]{13}$/",
		// 		'DI'=>"/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/",
		// 		'D'=>"/^6(?:011|5[0-9]{2})[0-9]{12}$/"
		// 	);

		// 	foreach ($types as $key => $value)
		// 		if(preg_match($value,$number))
		// 			$t = $key;

		// 	return $t;
		// }

		/*
		*	Function to get everything after a certain string.
		*	@data: the string to search in
		*	@search: the string you are searching
		*/
		public static function getStringAfter($data, $search){
			if($data == NULL || strpos($data, $search) === false)
				return null;

			return substr($data, strpos($data, $search) + strlen($search));
		}

		/*
			Function to get the source
		*/
		public static function getSource($s){
			$src = "";
			switch ($s) {
				case 'w':
					$src = "Website";
					break;
				case 'f':
					$src = "Facebook";
					break;
				case 'g':
					$src = "Google";
					break;
				case 'm':
					$src = "Manual";
					break;
			}

			return $src;
		}

		/*
		*	Function to move a file.
		*	@target: the file to move.
		*	@destination: the location we want to move the file to.
		*/
		public static function moveFile($target, $destination){
			if($target != NULL && $destination != NULL){
				if(file_exists($target)){
					copy($target, $destination);
					unlink($target);
					return true;
				}
			}
			return false;
		}

		/*
		*	Function to redirect the user to a page and exit the current page.
		*	@destination: page to redirect after the website url.
		*	@delay: delay before redirect. (Default: 0)
		*/
		public static function redirect($destination = "", $delay = 0){
			echo '<meta http-equiv="refresh" content="' . $delay . ';url=' . Config::WEBSITE_URL . '/' . $destination . '">';
			exit;
		}

		/*
		*	Function to send en email.
		*	@from: Sender of the email.
		*	@to: Receiver of the email.
		*	@subject: Subject of the email.
		*	@message: Message of the email.
		*/
		public static function sendEmail($from, $to, $subject, $message, $name = Config::WEBSITE_TITLE, $inner = true){
			$logo = $inner==true?'<div style="margin:15px 0px;"><img src="' . str_replace('/app', '', Config::WEBSITE_URL) . '/assets/img/logo.png" alt="Logo"></div>':'';
			$sub = $inner==false?'<a href="' . Config::WEBSITE_URL . '/unsubscribe/' . $to . '" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">Unsubscribe</a>':'';
			$newsubject='=?UTF-8?B?'.base64_encode($subject).'?=';
			/*$headers = 'From: ' . $name . ' <' . $from . ">\r\n"  . 'Reply-to: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'Content-type: text/html';*/
			$headers    = array(
				'MIME-Version: 1.0',
				'Content-Type: text/html; charset="UTF-8";',
				'Content-Transfer-Encoding: 7bit',
				'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
				'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
				'From: ' . $name . ' <' . $from . '>',
				'Reply-To: ' . $from,
				'Return-Path: ' . $from,
				'X-Mailer: PHP v' . phpversion(),
				'X-Originating-IP: ' . $_SERVER['SERVER_ADDR']
				);

			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>' . Config::WEBSITE_TITLE . '</title>

				<style type="text/css">
					img {
						max-width: 100%;
					}
					body {
						-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
					}
					body {
						background-color: #f6f6f6;
					}
					@media only screen and (max-width: 640px) {
						body {
							padding: 0 !important;
						}
						h1 {
							font-weight: 800 !important; margin: 20px 0 5px !important;
						}
						h2 {
							font-weight: 800 !important; margin: 20px 0 5px !important;
						}
						h3 {
							font-weight: 800 !important; margin: 20px 0 5px !important;
						}
						h4 {
							font-weight: 800 !important; margin: 20px 0 5px !important;
						}
						h1 {
							font-size: 22px !important;
						}
						h2 {
							font-size: 18px !important;
						}
						h3 {
							font-size: 16px !important;
						}
						.container {
							padding: 0 !important; width: 100% !important;
						}
						.content {
							padding: 0 !important;
						}
						.content-wrap {
							padding: 10px !important;
						}
						.invoice {
							width: 100% !important;
						}
					}
				</style>
			</head>

			<body style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

				<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
					<td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
						' . $logo . '<div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
						' . $message . '<div class="footer" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
						<table width="100%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="aligncenter content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">'.$sub.'</td>
						</tr></table></div></div>
					</td>
					<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
				</tr></table></body>
				</html>';
				//return mail($to, $newsubject, $message, implode("\n", $headers));
				$mail = new PHPMailerOAuth;
				$mail->CharSet = 'UTF-8';
				#$mail->isSMTP();
				#$mail->SMTPDebug = 0; #0: no output, 1: server, 2:server and client
				/*$mail->Debugoutput = 'html';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;
				$mail->AuthType = 'XOAUTH2';
				$mail->oauthUserEmail = "support@unbeleadsable.com";
				$mail->oauthClientId = "813964004785-f1qbj7relgf69jbvkgmjukn6v785hdbg.apps.googleusercontent.com";
				$mail->oauthClientSecret = "p-zST0auclr5Ky-TiKSP1U0d";
				$mail->oauthRefreshToken = "1/qMHAzVOeVUxiNQx3S1gKL0Yxu86MX2KC0F3shvyvqvY";*/
				$mail->setFrom($from, $name);
				$mail->addReplyTo($from, $name);
				$mail->addAddress($to, '');
				$mail->Subject = $newsubject;
				$mail->msgHTML($message);
				//$mail->addAttachment('images/phpmailer_mini.png');
				return $mail->send();
			}

		/*
		*	Function to show a human readable date.
		*	@original_date: date to be converted in string or epoch time.
		*/
		public static function userFriendlyDate($date){
			if($date == NULL)
				return "Never";

			if(!is_numeric($date))
				$date = strtotime($date);

			$today = time();
			$YEAR = date('Y', $date);
			$MONTH = date('m', $date);
			$DAY = date('d', $date);

			$CURRENT_YEAR = date('Y', $today);
			$CURRENT_MONTH = date('m', $today);
			$CURRENT_DAY = date('d', $today);

			/*if($CURRENT_YEAR != $YEAR)
				return date("F Y", $date);
			else if($CURRENT_MONTH != $MONTH)
				return date("F d", $date);
			else
			return date("F jS", $date) . " at " . date("h:i a", $date);*/

			return date('F jS, Y', $date);
		}

		/*
			Function to convert a blog image into a real image and save it in a folder on the server and replace the blog image with and image scr
			@att: the folder of the image to be saved in
			@string: the original string where the blog is found
			@overwrite: if new image should overwrite existing image
		*/
		public static function save_images($att, $string, $overwrite = true) {
			if (!function_exists('imagecreatefromstring'))
				throw new \Exception("This script requires the imagecreatefromstring function from the GD extension (http://www.php.net/manual/en/book.image.php).");

			// This part could probably be better
			$types = array(
				'image/jpeg' => 'jpg',
				'image/png'  => 'png',
				'image/gif'  => 'gif',
			);

			$DOM = new DOMDocument;
			$DOM->loadHTML($string);
			// Find all the img tags
			$items = $DOM->getElementsByTagName('img');

			$usedFilenames = array();

			for ($i = 0; $i < $items->length; $i++) {
				$src = $items->item($i)->getAttribute('src');
				$alt = $items->item($i)->getAttribute('alt');
				// Only the ones with data: urls
				if (preg_match('/^data:/',$src)) {
					// Deconstruct it, get all the parts
					$semicolon_place = strpos($src, ';');
					$comma_place = strpos($src, ',');
					$type = substr($src,5,$semicolon_place-5);
					$base64_data = substr($src, $comma_place+1);
					$data = base64_decode($base64_data);
					$md5 = md5($data);
					$path = "../cdn/$att";

					if (!file_exists($path)) {
						mkdir($path, 0777, true);
					}

					if (empty($alt)) $filename = $md5;
					else $filename = urlencode(str_replace(' ', '_', strtolower(substr($alt, 0, 30))));
					if (in_array($filename, $usedFilenames)) {
						$filename .= '_'.$md5;
					}
					$usedFilenames[] = $filename;

					//convert image to JPG
					$imgDataFromClipboard = imagecreatefromstring($data);
					if ($imgDataFromClipboard) {
						$path .= "/{$filename}.jpg";

						//save as JPG
						if ($overwrite || !file_exists($path)) {
							imagejpeg($imgDataFromClipboard, $path, 90); //90% quality
						}
					}
					else {
						throw new \Exception("Error retrieving image data");
					}
					$items->item($i)->setAttribute('src', "https://unbeleadsable.com/cdn/$att/{$filename}.jpg");
				}
			}
			$string = $DOM->saveHTML();
			return $string;
		}
	}
	?>