<?php 
	abstract class User{
		# Available status
		CONST DELETED   =   0;
		CONST ACTIVE    =   1;
		CONST BANNED    =   2;
		
		# Available user level
		CONST LEVEL_USER        =   0;
		CONST LEVEL_AGENT       =   10;
		CONST LEVEL_MODERATOR   =   20;
		CONST LEVEL_ADMIN       =   50;
		CONST LEVEL_GOD_ADMIN   =   100;
		
		# Username requirements
		CONST MIN_USERNAME_LENGTH = 4;
		CONST MAX_USERNAME_LENGTH = 15;
		
		# Email requirements
		CONST MIN_EMAIL_LENGTH = 6;
		CONST MAX_EMAIL_LENGTH = 100;
		
		# Password requirements
		CONST MIN_PASSWORD_LENGTH = 6;
		CONST MAX_PASSWORD_LENGTH = 20;
		
		# Full name requirements
		CONST MIN_NAME_LENGTH = 4;
		CONST MAX_NAME_LENGTH = 50;
		
		# Function to check if the level is an admin level
		public static function isAdmin($level){ return $level >= User::LEVEL_ADMIN; }
		
		# Function to check if the user is logged in
		public static function isLoggedIn(){ return isset($_SESSION['user']); }

		# Function to get the name of the user level
		public static function getUserLevel($level){
			switch ($level) {
				case self::LEVEL_USER:
					return "user";
				case self::LEVEL_ADMIN:
					return "admin";
				case self::LEVEL_MODERATOR:
					return "moderator";
				case self::LEVEL_GOD_ADMIN:
					return "god";
				case self::LEVEL_AGENT:
					return "agent";
			}
		}
		
		/*
		*   Function to check if a user's field is valid.
		*   @data: array of the post.
		*   @key: name of the key in the array.
		*   @type: type of validation.
		*   @errorMsg: The error message container to update to become the error message to return.
		*/
		public static function isValidField(Array $data, $key, $type, &$errorMsg){
			return User::doValidateField($data, $key, $type, $errorMsg);
		}
		
		/*
		*   Function to check if a user's field is valid
		*   @data: the post array
		*   @key: list of the keys
		*   @type: list of the types of validation
		*   @errorMsg: the container of the error message that the function will return
		*/
		public static function isValidFields(Array $data, Array $keys, Array $types, &$errorMsg){
			if(count($keys) != count($types)){
				$errorMsg = "Please make sure you have the same amount of key and type.";
				return false;
			}
			
			# Loop through each keys
			for($i=0; $i<count($keys); $i++){
				$key = $keys[$i];
				$type = $types[$i];
				
				if(!User::doValidateField($data, $key, $type, $errorMsg))
					return false;
			}
			
			return true;
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
			
			# Username validation
			if($type == "username"){
				if(Functions::isEmpty($data[$key]))
					$errorMsg = "Your username cannot be empty.";
				else if(strlen(trim($data[$key])) < User::MIN_USERNAME_LENGTH || strlen(trim($data[$key])) > User::MAX_USERNAME_LENGTH)
					$errorMsg = "Your username must be between " . User::MIN_USERNAME_LENGTH . " and " . User::MAX_USERNAME_LENGTH . " characters.";
				else if(preg_match('/[^a-zA-Z0-9]/', $data[$key]))
					$errorMsg = "Your username can only contain letters and numbers.";
			}
			
			# Email validation
			else if($type == "email"){
				$data[$key] = trim($data[$key]);
				
				if(Functions::isEmpty($data[$key]))
					$errorMsg = "Your email address cannot be empty.";
				else if(strlen($data[$key]) < User::MIN_EMAIL_LENGTH || strlen($data[$key]) > User::MAX_EMAIL_LENGTH)
					$errorMsg = "Your email address must be between " . User::MIN_EMAIL_LENGTH . " and " . User::MAX_EMAIL_LENGTH . " characters.";
				else if(filter_var($data[$key], FILTER_VALIDATE_EMAIL) === false)
					$errorMsg = "Your email address is invalid.";
			}
			
			# Full name validation
			else if($type == "fullname"){                 
				if(strlen(trim($data[$key])) > User::MAX_NAME_LENGTH)
					$errorMsg = "Your name cannot be over " . User::MAX_NAME_LENGTH . " characters.";
				else if(preg_match('/[^A-zÀ-ÿ\s\-]/', $data[$key]))
					$errorMsg = "Your name can only contain letters and spaces.";
			}

			# New password validation
			else if($type == "password"){
				if(Functions::isEmpty($data[$key], false))
					$errorMsg = "Your password cannot be empty.";
				else if(strlen($data[$key]) < User::MIN_PASSWORD_LENGTH || strlen($data[$key]) > User::MAX_PASSWORD_LENGTH)
					$errorMsg = "Your password must be between " . User::MIN_PASSWORD_LENGTH . " and " . User::MAX_PASSWORD_LENGTH . " characters.";
			}
			
			# New password validation
			else if($type == "new-password"){
				if(Functions::isEmpty($data[$key], false))
					$errorMsg = "Your new password cannot be empty.";
				else if(strlen($data[$key]) < User::MIN_PASSWORD_LENGTH || strlen($data[$key]) > User::MAX_PASSWORD_LENGTH)
					$errorMsg = "Your new password must be between " . User::MIN_PASSWORD_LENGTH . " and " . User::MAX_PASSWORD_LENGTH . " characters.";
				else if(md5($data[$key]) == $_SESSION['user']['password'])
					$errorMsg = "Your new password cannot be different from your current password.";
			}
			
			# Unknown validation type
			else{ $errorMsg = "Unknown type: " . $type; }
			
			return $errorMsg == "-1";
		}
	}
?>