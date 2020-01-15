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
 if (!empty($_POST['name']) && !empty($_POST['title']) && !empty($_POST['description'])
 && !empty($_POST['facebook']) && !empty($_POST['twitter']) && !empty($_POST['linkedin']))
 {
	 // get values
	$tname = $_POST['name'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$facebook = $_POST['facebook'];
	$twitter = $_POST['twitter'];
	$linkedin = $_POST['linkedin'];
	$teamid = $_POST['teamid'];
	

   if($_FILES['photoimg']['size'] == 0) {


			//Update
			$Update = DB::getInstance()->update('team',[
			   'name' => $tname,
			   'title' => $title,
			   'description' => $description,
			   'facebook' => $facebook,
			   'twitter' => $twitter,
			   'linkedin' => $linkedin
			],[
			    'id' => $teamid
			  ]);
			  
		   if (count($Update) > 0) {
		        Session::put("updatedError", $tname);
			} else {
	            Session::put("hasError", $tname);
			}
	 
	        
   } else {
   	

	 //Get Data
     $query = DB::getInstance()->get("team", "*", ["id" => $teamid, "LIMIT" => 1]);
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

	$path = "../../uploads/team/";
	$new_path = "uploads/team/";
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
	      	
			//Update
			$Update = DB::getInstance()->update('team',[
			   'name' => $tname,
			   'title' => $title,
			   'description' => $description,
			   'facebook' => $facebook,
			   'twitter' => $twitter,
			   'linkedin' => $linkedin,
			   'imagelocation' => $newname,
			   'imagename' => $image_name
			],[
			    'id' => $teamid
			  ]);
			  
		   if (count($Update) > 0) {
		        Session::put("updatedError", $tname);
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
       
   }	
	
 } else {
	 $tname = $_POST['name'];
	  Session::put("hasError", $tname);
     
 }
 
 
?>