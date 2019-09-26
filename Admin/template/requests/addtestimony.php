<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 //Start new Admin Object
$admin = new Admin();

//Check if Admin is logged in
if (!$admin->isLoggedIn()) {
  Redirect::to('index.php');	
}
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['name']) && !empty($_POST['company']) && !empty($_POST['message']))
 {
	 // get values
	$tname = $_POST['name'];
	$company = $_POST['company'];
	$message = $_POST['message'];

	$path = "../../uploads/testimony/";
	$new_path = "uploads/testimony/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];	
	
    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(700*400))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $newname=$new_path.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      { 		  
	 
		   $Insert = DB::getInstance()->insert('team', array(
			   'userid' => $admin->data()->adminid,
			   'name' => $tname,
			   'title' => $company,
			   'description' => $message,
			   'imagelocation' => $newname,
			   'imagename' => $image_name,
			   'testimony' => 1,
			   'active' => 1,
			   'delete_remove' => 0,
			   'date_added' => date('Y-m-d H:i:s')
		    ));	
			
		  if (count($Insert) > 0) {
		    Session::put("noError", $tname);
		  } else {
	        Session::put("hasError", $tname);
		  }
	  
	      }else{
	       $tname = $_POST['name'];
	       Session::put("imageError", $tname);
     	  }
	   }else {
         $tname = $_POST['name'];
         Session::put("formatError", $tname);
	   }  
		  
      }else{
         $tname = $_POST['name'];
         Session::put("selectError", $tname);
      }		
	 
	 
	
 } else {
	 $tname = $_POST['name'];
	  Session::put("hasError", $tname);
     
 }
 
 
?>