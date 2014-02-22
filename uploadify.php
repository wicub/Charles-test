<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
include './func/img_resize.php';
$targetFolder = 'uploads/';

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

//if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	$targetFile = uniqid().'.'.$fileParts['extension'];
	
	if (in_array(strtolower($fileParts['extension']),$fileTypes)) {
		
		if(move_uploaded_file($tempFile,$targetFolder.$targetFile)){
			resize(200,255,'uploads/'.$targetFile);
			echo $targetFile;
		}else{
			echo '上传失败';
		}
	} else {
		echo '扩展名无效';
	}
//}

//sleep(10);
