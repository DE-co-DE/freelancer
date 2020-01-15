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
 if (!empty($_POST['milestoneid']) && !empty($_POST['jobid']) && !empty($_POST['name']) && !empty($_POST['progress']) && !empty($_POST['description']) && !empty($_POST['start_date'])
 && !empty($_POST['end_date']))
 {
	 // get values
	 $jobid = $_POST['jobid'];
	 $milestoneid = $_POST['milestoneid'];
	 $name = $_POST['name'];
	 $progress = $_POST['progress'];
	 $description = $_POST['description'];
	 $start_date = $_POST['start_date'];
	 $end_date = $_POST['end_date'];
	 
	 
	   $Insert = DB::getInstance()->insert('task', array(
		   'description' => $description,
		   'jobid' => $jobid,
		   'milestoneid' => $milestoneid,
		   'freelancerid' => $freelancer->data()->freelancerid,
		   'name' => $name,
		   'progress' => $progress,
		   'start_date' => $start_date,
		   'end_date' => $end_date,
		   'active' => 1,
		   'delete_remove' => 0,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
	  Session::put("addTask", $name);
	
 } else {
	 $name = $_POST['name'];
	  Session::put("notTask", $name);
     
 }
 
 
?>