<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['milestoneid']) && !empty($_POST['name']) && !empty($_POST['progress']) && !empty($_POST['description']) && !empty($_POST['start_date'])
 && !empty($_POST['end_date']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $milestoneid = $_POST['milestoneid'];
	 $name = $_POST['name'];
	 $description = $_POST['description'];
	 $progress = $_POST['progress'];
	 $start_date = $_POST['start_date'];
	 $end_date = $_POST['end_date'];

	//Update
	$Update = DB::getInstance()->update('task',[
	   'milestoneid' => $milestoneid,
	   'name' => $name,
	   'description' => $description,
	   'progress' => $progress,
	   'start_date' => $start_date,
	   'end_date' => $end_date
	],[
	    'id' => $id
	  ]);
	  
	  Session::put("updateTask", $name);
 }else {
	 $name = $_POST['name'];
	  Session::put("notTask", $name);
 }
 
	  
?>