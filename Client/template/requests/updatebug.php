<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['bugid']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['reproducibility'])
 && !empty($_POST['priority']) && !empty($_POST['severity']))
 {
	 // get id value
	 $bugid = $_POST['bugid'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 $priority = $_POST['priority'];
	 $severity = $_POST['severity'];
	 $reproducibility = $_POST['reproducibility'];

	//Update
	$Update = DB::getInstance()->update('bugs',[
	   'title' => $title,
	   'description' => $description,
	   'priority' => $priority,
	   'severity' => $severity,
	   'reproducibility' => $reproducibility
	],[
	    'id' => $bugid
	  ]);
	  
	  Session::put("updateBug", $title);
 }else {
	 $title = $_POST['title'];
	  Session::put("notBug", $title);
 }
 
	  
?>