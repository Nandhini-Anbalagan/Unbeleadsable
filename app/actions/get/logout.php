<?php
    # Loop through each cookie
    foreach($_COOKIE as $name => $value){
        # Make sure it's not the PHP Session Id
        if($name != "PHPSESSID"){
            switch($name){
                case "remember_me":
                    $key = $db->getUserKey($value, 'AUTO_LOGIN');
                    if($key)
                        $db->deleteUserKey($key['user_key_id']);
                    break;
            }
        }
    }
    session_destroy();
    Functions::redirect("login");
?>