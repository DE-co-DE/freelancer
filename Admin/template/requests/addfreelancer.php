<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['title']) && !empty($_POST['description']))
 {
	 // get values
	$title = $_POST['title'];
	$description = $_POST['description'];

	$path = "../../uploads/how/";
	$new_path = "uploads/how/";
    $valid_formats = array("jpg", "png", "gif", "bmp", "svg", "jpeg");
   
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
	       $tname = "Freelancer Section";
		   $Insert = DB::getInstance()->insert('section_freelancer', array(
			   'title' => $title,
			   'description' => $description,
			   'imagelocation' => $newname,
			   'imagename' => $image_name,
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
	  $tname = "Freelancer Section";
	  Session::put("hasError", $tname);
     
 }
 
 
?>