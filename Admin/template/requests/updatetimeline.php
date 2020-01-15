<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['timelineid']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['icon']))
 {
	 // get id value
	 $timelineid = $_POST['timelineid'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 $icon = $_POST['icon'];

	//Update
	$Update = DB::getInstance()->update('timeline',[
	   'title' => $title,
	   'description' => $description,
	   'icon' => $icon
	],[
	    'id' => $timelineid
	  ]);
	  
	   if (count($Update) > 0) {
	        Session::put("updatedError", $title);
		} else {
	        Session::put("hasError", $title);
		}
	  
 }else {
	 $title = $_POST['title'];
	  Session::put("hasError", $title);
 }
 
	  
?>