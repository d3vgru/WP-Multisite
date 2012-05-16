<?php
$oldURL = dirname(__FILE__);
$newURL = str_replace(DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'font-uploader', '', $oldURL);
include($newURL . DIRECTORY_SEPARATOR . 'wp-blog-header.php');
//define a maxim size for the uploaded files
define ("MAX_SIZE","20000000"); 
//This function reads the extension of the file. It is used to determine if the file  is an font by checking the extension.
function getExtension($str)
{
   $parts = explode('.', $str);
   return end($parts);
}

//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
//and it will be changed to 1 if an errro occures.  
//If the error occures the file will not be uploaded.
 $errors=0;
//checks if the form has been submitted
 if(isset($_POST['Submit'])) 
 {
 	//reads the name of the file the user submitted for uploading
 	$font=$_FILES['font']['name'];
 	//if it is not empty
 	if ($font) 
 	{
	 	//get the original name of the file from the clients machine
	 		$filename = stripslashes($_FILES['font']['name']);
	 	//get the extension of the file in a lower case format
	  		$extension = getExtension($filename);
	 		$extension = strtolower($extension);
	 	//if it is not a known extension, we will suppose it is an error and will not upload the file,
	 	//we will only allow .ttf and .otf file extensions  
		//otherwise we will do more tests
		if (($extension != "ttf") && ($extension != "otf") && ($extension != "eot")) 
	 	{
			//print error message
	 		echo '<h1>Unknown extension!</h1>';
	 		$errors=1;
	 	}
	 	else
	 	{ 		
			//check the mimetypes against an allowed list
			/*
			$mime = array ("application/x-font-ttf", "application/vnd.oasis.opendocument.formula-template", "application/octet-stream", "application/x-vnd.oasis.opendocument.formula-template", "application/vnd.ms-fontobject");
 																	
			if (!in_array($_FILES['font']['type'],$mime))
			{
		 		echo '<h1>Unknown mimetype!</h1>';
				$errors=1;
			}*/
			//get the size of the file in bytes
			//$_FILES['font']['tmp_name'] is the temporary filename of the file
			//in which the uploaded file was stored on the server
			$size=filesize($_FILES['font']['tmp_name']);
			//compare the size with the maxim size we defined and print error if bigger
			if ($size > MAX_SIZE)
			{
				echo '<h1>You have exceeded the size limit!</h1>';
				$errors=1;
			}
			//keep the original file name
			$font_name=$filename;
 
			if (!$errors)
			{
				//the new name will be containing the full path where fonts will be stored (fonts folder)
				$newname = "fonts/".$font_name;
				//we verify if the image has been uploaded, and print error instead
				$copied = copy($_FILES['font']['tmp_name'], $newname);
				if (!$copied) 
				{
					echo '<h1>Copy unsuccessfull!</h1>';
					$errors=1;
				}
			}
		}
	}	
	//If no errors registred, redirect back to the theme options panel
	if(isset($_POST['Submit']) && !$errors) 
	{
	$url = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=font-uploader.php';
 
	header ("Location: $url");
	}
 }
 ?>