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
 if (!empty($_POST['jobid']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['url']))
 {
	 // get values
	 $jobid = $_POST['jobid'];
	 $title = $_POST['title'];
	 $description = $_POST['description'];
	 $url = $_POST['url'];
	 
	   $Insert = DB::getInstance()->insert('link', array(
		   'jobid' => $jobid,
		   'freelancerid' => $freelancer->data()->freelancerid,
		   'title' => $title,
		   'description' => $description,
		   'url' => $url,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
	  Session::put("addLink", $title);
	
 } else {
	 $title = $_POST['title'];
	  Session::put("notLink", $name);
     
 }
 
 
?>