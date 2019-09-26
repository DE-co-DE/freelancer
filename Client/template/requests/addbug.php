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
 if (!empty($_POST['jobid']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['priority'])
 && !empty($_POST['severity']) && !empty($_POST['reproducibility']))
 {
	 // get values
	 $jobid = $_POST['jobid'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 $priority = $_POST['priority'];
	 $severity = $_POST['severity'];
	 $reproducibility = $_POST['reproducibility'];
	 
	   $Insert = DB::getInstance()->insert('bugs', array(
		   'jobid' => $jobid,
		   'reporter' => $client->data()->clientid,
		   'title' => $title,
		   'description' => $description,
		   'priority' => $priority,
		   'severity' => $severity,
		   'reproducibility' => $reproducibility,
		   'fixed' => 0,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
	  Session::put("addBug", $title);
	
 } else {
	 $title = $_POST['title'];
	  Session::put("notBug", $name);
     
 }
 
 
?>