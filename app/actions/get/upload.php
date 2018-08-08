<?php 
/*
	if($_FILES['file']['name']){
		if(!$_FILES['file']['error']){
			$new_file_name = strtolower($_FILES['file']['tmp_name']); 
			if($_FILES['file']['size'] > (1024000)){
				$valid_file = false;
				$message = 'Oops!  Your file\'s size is to large.';
			}

			if($valid_file){
				move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/'.$new_file_name);
				$message = 'Congratulations!  Your file was accepted.';
			}
		}
		else{
			$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
		}

		$resultObj['success'] = $message;
	}*/

	exit(var_dump($_FILES));


?>