<?php

    /******************************************************
    *
    *   Author          Unbeleadsable <support@unbeleasable.com>
    *   Version         2.1.1
    *   Last modified   February 4th, 2016
    *   Web             http://
    *
    ******************************************************/
    class Tokenizer{
        # List of options for get function
        const GET_TOKEN_NAME = 54;
        const GET_TOKEN = 55;
        const GET_TOKEN_VALUE = 56;
        
        # Properties of Tokenizer
        private $tokens;
        private $CREATION_TIME;
        private $REFRESH_RATE;
        
        /*
        *   Constructor of the class.
        *   @settings: settings of Tokenizer.
        *   @return: void
        */
        public function __construct(Array $settings = array()){
            # Set the refresh rate if the user didn't specify any, or specified an invalid value
            if(!isset($settings['refresh-rate']) || !is_int($settings['refresh-rate']))
                $settings['refresh-rate'] = 10 * 60; 
                
            $this->REFRESH_RATE = $settings['refresh-rate'];
            $this->CREATION_TIME = time();
        }
        
        /*
        *   Function to add a token.
        *   @name: name of the token.
        *   @length: length of the token. (Default: 20)
        *   @value: value of the token hidden. Set null to use name as value.
        *   @refresh: interval of refresh rate. Set to 0 for never refresh. (Default: refresh rate of the class)
        *   @return: array
        */
        public static function add($name, $length = 20, $value = null, $refresh = null){
            Tokenizer::start();
            return $_SESSION['tokenizer']->generateToken($name, $length, $value, $refresh);
        }
        
        /*
        *   Function to destroy a token by it's name.
        *   @name: single name or a list of names of the token(s) to delete
        *   @return void
        */
        public static function delete($name){
            Tokenizer::start();
            return $_SESSION['tokenizer']->destroyToken($name);
        }
        
        /*
        *   Function to delete all tokens that the name start with a specified prefix.
        *   @prefix: the prefix to delete
        */
        public static function deleteTokensWithPrefix($prefix){
            Tokenizer::start();
            $total = 0;
            
            if($prefix != NULL && strpos($prefix, " ") === false){
                if($tokenList != NULL){
                    foreach($tokenList as $token){
                        if(substr($token['name'], 0, strlen($prefix)) == $prefix)
                            $token += $_SESSION['tokenizer']->destroyToken($token['name']);
                    }
                }
            }
            
            return $total;
        }
        
        /*
        *   Function to clear all tokens from Tokenizer.
        */
        public static function destroy(){
            Tokenizer::start();
            $_SESSION['tokenizer'] = new Tokenizer();
        }
        
        /*
        *   Function to destroy a token by it's name.
        *   @name: single name or a list of names of the token(s) to delete
        *   @return the number of token destroyed
        */
        public function destroyToken($name){
            $total = 1;
            if(is_array($name) && count($name) > 0){
                foreach($name as $n){
                    if(isset($this->tokens[$n])){
                        unset($this->tokens[$n]);
                        $total++;
                    }
                }
            }else if(isset($this->tokens[$name]))
                unset($this->tokens[$name]);
                
            return $total;
        }
        
        /*
        *   Function to generate a random string.
        *   @length: length of the string. (Default: 20)
        *   @hasNumbers: true if the generated string can contain a number. (Default: true)
        *   @intBegin: true if the generated string can begin with a digit. (Default: false)
        *   @hasSymbols: true if the generated string can contain symbols. (Default: false)
        *   @symbolBegin: true if the generated string can begin with a symbol. (Default: false)
        *   @return: string
        */
        public static function generateString($length = 20, $hasNumbers = true, $intBegin = false, $hasSymbols = false, $symbolBegin = false){
            # Check if the user entered a positive number for the string length
            if(!is_int($length) || $length < 1)
                $length = 20;
                    
            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $digits = "0123456789";
            $symbols = "!@#$%^&*()-_=+";
            $invalidStart = "";
            $result = "";
                
            # Set the default hasNumbers to false
            if($hasNumbers != "false")
                $hasNumbers = true;
                
            if($hasNumbers){
                if($intBegin != "true")
                    $intBegin = false;
                
                $characters .= $digits;
            }else{ $intBegin = false; }
            
            # Set the default hasSymbols to false
            if($hasSymbols != "true")
                $hasSymbols = false;
                
            if($hasSymbols){
                if($symbolBegin != "true")
                    $symbolBegin = false;
                
                $characters .= $symbols;
            }else{ $symbolBegin = false; }
            
            # Generate the invalid start
            if(!$intBegin) $invalidStart .= $digits;
            if(!$symbolBegin) $invalidStart .= $symbols;
            
            do{
                $result .= $characters[rand(0, strlen($characters) - 1)];
                
                if(strlen($result) == 1 && strpos($invalidStart, $result) !== false)
                    $result = Tokenizer::generateString($length, $hasNumbers, $intBegin, $hasSymbols, $symbolBegin);
            }while(strlen($result) < $length);
            
            return $result;
        }
        
        /*
        *   Functions to generate a random token.
        *   @name: name of the token.
        *   @length: length of the token. (Default: 20)
        *   @value: value of the token hidden. Set null to use name as value.
        *   @refresh: interval of refresh rate. Set to 0 for never refresh. (Default: refresh rate of the class)
        *   @return: array
        */
        public function generateToken($name, $length = 20, $value = null, $refresh = null){
            # Name of the variable cannot begin with a digit
            if(is_numeric($name[0]))
                exit('Name of token cannot begin with a numerical value.');
                
            # Set the length to 20 by default if none specified by the user
            # Minimum length of 5 and maximum length of 50
            if(!is_int($length))
                $length = 20;
            else if($length < 5)
                $length = 5;
            else if($length > 50)
                $length = 50;
                
            # Set the refresh rate to the class's refresh rate
            if($refresh == null && $refresh !== 0)
                $refresh = $this->REFRESH_RATE;
            
            $token = NULL;
            if((!isset($this->tokens[$name]) || (time() - $this->tokens[$name]['time'] >= $this->tokens[$name]['refresh-rate'] && $this->tokens[$name]['refresh-rate'] != 0)
                || (strlen($this->tokens[$name]['token']) != $length) && $this->tokens[$name]['refresh-rate'] != 0)){
                do{
                    $token = Tokenizer::generateString($length);
                }while($this->getTokenNameByValue($token) != NULL);
                $this->tokens[$name] = array('name' => $name, 'token' => $token, 'value' => $value, 'refresh-rate' => $refresh, 'time' => time());
            }
            
            return $this->tokens[$name]['token'];
        }
        
        /*
        *   Function to get a token.
        *   @data: value we need to retrieve our token.
        *   @type: type of get we want. (Default: GET_TOKEN_VALUE)
        *   @return string
        */
        public static function get($data, $type = Tokenizer::GET_TOKEN){
            Tokenizer::start();
            switch($type){
                case Tokenizer::GET_TOKEN:
                    return $_SESSION['tokenizer']->getTokenByName($data);
                case Tokenizer::GET_TOKEN_NAME:
                    return $_SESSION['tokenizer']->getTokenNameByValue($data);
                case Tokenizer::GET_TOKEN_VALUE:
                    return $_SESSION['tokenizer']->getTokenStrictValue($data);
                default:
                    throw new Exception('Unknown type specified: ' . $type . '.');
            }
        }
        
        /**
        *   Function to get the creation time of the object.
        *   @forHuman:   True to print a human readable time. (Default: false)
        *   @return: time in miliseconds or human formatted
        */
        public function getCreationTime($forHuman = false){
            return $forHuman ? date("Y-m-d H:i:s", $this->CREATION_TIME) : $this->CREATION_TIME;
        }
        
        /*
        *   Function to get a token's encryption by it's name.
        *   @name: name of the token.
        *   @return string
        */
        public function getTokenByName($name){
            if(!isset($this->tokens[$name]))
                return null;
                
            return $this->tokens[$name]['token'];            
        }
        
        /*
        *   Function to get a token's non-encrypted value by the token.
        *   @token: value of the encryption.
        *   @return: string
        */
        public function getTokenNameByValue($token){
            if(!isset($this->tokens) || count($this->tokens) < 1 || strlen($token) < 1)
                return null;
                
            foreach($this->tokens as $t){
                if($t['token'] == $token)
                    return $t['name'];
            }    
                
            return null;
        }
        
        /*
        *   Function to get all tokens.
        *   @return array
        */
        public function getTokens(){
            if(!isset($this->tokens) || count($this->tokens) < 1)
                return null;
            return $this->tokens;
        }
        
        /*
        *   Function to get a token's non-encrypted user-set strict-value by the token. Return token's name if value not set.
        *   @token: value of the encryption.
        *   @return: string
        */
        public function getTokenStrictValue($token){
            if(!isset($this->tokens) || count($this->tokens) < 1 || strlen($token) < 1)
                return null;
                
            foreach($this->tokens as $t){
                if($t['token'] == $token)
                    return empty($t['value']) ? $t['name'] : $t['value'];
            }    
                
            return null;
        }
        
        /*
        *   Function to start Tokenizer.
        *   @settings: settings of Tokenizer.
        *   @return void
        */
        public static function start($settings = array()){
            if(session_status() == PHP_SESSION_NONE)
                session_start();
                
            if(!isset($_SESSION['tokenizer']))
                $_SESSION['tokenizer'] = new Tokenizer($settings);
        }
    }
?>