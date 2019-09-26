<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['clientid']) && !empty($_POST['title']) && !empty($_POST['description']))
 {
	 // get values
	$title = $_POST['title'];
	$description = $_POST['description'];
	$clientid = $_POST['clientid'];
	

   if($_FILES['photoimg']['size'] == 0) {


			//Update
			$Update = DB::getInstance()->update('section_client',[
			   'title' => $title,
			   'description' => $description,
			],[
			    'id' => $clientid
			  ]);
			  
		   if (count($Update) > 0) {
		        Session::put("updatedError", $title);
			} else {
	            Session::put("hasError", $title);
			}
	
	 
	        
   } else {
   	

	 //Get Data
     $query = DB::getInstance()->get("section_client", "*", ["id" => $clientid, "LIMIT" => 1]);
	  if ($query->count()) {
	   foreach($query->results() as $row) {
	    $imagelocation = $row->imagelocation;
	   }			
	  }
		  $back = "../../";
	      $location = $back.$imagelocation;

			if(file_exists($location)){
		       unlink($location);
		 }	

	$path = "../../uploads/how/";
	$new_path = "uploads/how/";
    $valid_formats = array("jpg", "png", "gif", "bmp", "svg");
   
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
	      	
			//Update
			$Update = DB::getInstance()->update('section_client',[
			   'title' => $title,
			   'description' => $description,
			   'imagelocation' => $newname,
			   'imagename' => $image_name
			],[
			    'id' => $clientid
			  ]);
			  
		   if (count($Update) > 0) {
		        Session::put("updatedError", $title);
			} else {
	            Session::put("hasError", $title);
			}			  
			
	  

	      }else{
	       $tname = $_POST['title'];
	       Session::put("imageError", $tname);
     	  }
	   }else {
         $tname = $_POST['title'];
         Session::put("formatError", $tname);
	   }  
		  
      }else{
         $tname = $_POST['title'];
         Session::put("selectError", $tname);
      }			
       
   }	
	
 } else {
	 $tname = $_POST['title'];
	  Session::put("hasError", $tname);
     
 }
 
 
?>