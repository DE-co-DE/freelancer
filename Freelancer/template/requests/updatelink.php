<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['linkid']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url']))
 {
	 // get id value
	 $linkid = $_POST['linkid'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 $url = $_POST['url'];

	//Update
	$Update = DB::getInstance()->update('link',[
	   'title' => $title,
	   'description' => $description,
	   'url' => $url
	],[
	    'id' => $linkid
	  ]);
	  
	  Session::put("updateLink", $title);
 }else {
	 $title = $_POST['title'];
	  Session::put("notLink", $title);
 }
 
	  
?>