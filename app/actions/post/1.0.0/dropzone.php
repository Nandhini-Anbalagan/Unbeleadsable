<?php 
	switch($_POST['case']){
		case "add":
			$ext = pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);
			$fileName = Tokenizer::generateString(rand(10, 20), true, true, false, false) . "_" . time() . "_o.$ext";
			move_uploaded_file($_FILES['file']['tmp_name'], "temp/$fileName");
			exit($fileName);
		case "delete":
			if(isset($_POST['name']) && $_POST['name'] != NULL && file_exists("temp/" . $_POST['name']))
				unlink("temp/" . $_POST['name']);
			$resultObj['no-message'] = true;
			break;
	}
?>