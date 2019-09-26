<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../../index.php');	
}
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['fileid']))
 {
	 // get values
	$title = $_POST['title'];
	$description = $_POST['description'];
	$fileid = $_POST['fileid'];


   if($_FILES['photoimg']['size'] == 0) {

			$Update = DB::getInstance()->update('file',[
			   'title' => $title,
			   'description' => $description,
			],[
			    'id' => $fileid
			  ]);
			  
		  Session::put("updateFile", $title);
	        
   } else {	

	 //Get Data
     $query = DB::getInstance()->get("file", "*", ["id" => $fileid, "LIMIT" => 1]);
	  if ($query->count()) {
	   foreach($query->results() as $row) {
	    $fileupload = $row->fileupload;
	   }			
	  }
		  $back = "../../";
	      $location = $back.$fileupload;

			if(file_exists($location)){
		       unlink($location);
		 }	

	$path = "../../uploads/files/";
	$new_path = "uploads/files/";
    //$valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];	
	$type = $_FILES['photoimg']['type']; 
	// new file size in KB
	$new_size = $size/1024;  
	
    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $newname=$new_path.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	      	
			//Update
			$Update = DB::getInstance()->update('file',[
			   'title' => $title,
			   'description' => $description,
			   'file_name' => $image_name,
			   'fileupload' => $newname,
			   'type' => $type,
			   'extension' => $ext,
			   'size' => $new_size
			],[
			    'id' => $fileid
			  ]);
			
		
		  Session::put("updateFile", $title);
	  
	      }else{
	 $title = $_POST['title'];
	  Session::put("updateFile", $title);
		   $imageError = true;	
     	  }
      }else{
	 $title = $_POST['title'];
	  Session::put("updateFile", $name);
      	  //$selectError = true;
      }		
	 
   } 
	
 } else {
	 $title = $_POST['title'];
	  Session::put("updateFile", $title);
     
 }
 
 
?>