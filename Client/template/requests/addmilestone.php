<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 //Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('index.php');	
}
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['budget']) && !empty($_POST['start_date'])
 && !empty($_POST['end_date']))
 {
	 // get values
	 $name = $_POST['name'];
	 $description = $_POST['description'];
	 $budget = $_POST['budget'];
	 $start_date = $_POST['start_date'];
	 $end_date = $_POST['end_date'];
	 $jobid = $_POST['jobid'];
	 $freelancerid = $_POST['freelancerid'];
	 
	 
	   $Insert = DB::getInstance()->insert('milestone', array(
		   'description' => $description,
		   'jobid' => $jobid,
		   'clientid' => $client->data()->clientid,
		   'freelancerid' => $freelancerid,
		   'name' => $name,
		   'budget' => $budget,
		   'start_date' => $start_date,
		   'end_date' => $end_date,
		   'funded' => 0,
		   'active' => 1,
		   'delete_remove' => 0,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		  if (count($Insert) > 0) {
		    Session::put("noError", $name);
		  } else {
	        Session::put("hasError", $name);
		  }		
	
 } else {
	 $name = $_POST['name'];
	  Session::put("hasError", $name);
     
 }
 
?>