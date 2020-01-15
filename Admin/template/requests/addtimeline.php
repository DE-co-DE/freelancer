<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['icon']) && !empty($_POST['title']) && !empty($_POST['description']))
 {
	 // get values
	 $icon = $_POST['icon'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 
	   $Insert = DB::getInstance()->insert('timeline', array(
		   'icon' => $icon,
		   'title' => $title,
		   'description' => $description,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		  if (count($Insert) > 0) {
		    Session::put("noError", $title);
		  } else {
	        Session::put("hasError", $title);
		  }
	
 } else {
	 $title = $_POST['title'];
	  Session::put("hasError", $title);
     
 }
 
 
?>