<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../index.php');	
}
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['jobid']) && !empty($_POST['freelancerid']) && !empty($_POST['star']) && !empty($_POST['star_type']))
 {
	 // get values
	 $jobid = $_POST['jobid'];
	 $freelancerid = $_POST['freelancerid'];
	 $star = $_POST['star'];
	 $star_type = $_POST['star_type'];


	$query = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, "star_type" => $star_type], "LIMIT" => 1]);
	if ($query->count() === 0) {
	 
	   $Insert = DB::getInstance()->insert('ratings_client', array(
		   'jobid' => $jobid,
		   'freelancerid' => $freelancer->data()->freelancerid,
		   'clientid' => $freelancerid,
		   'star' => $star,
		   'star_type' => $star_type,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		  if (count($Insert) > 0) {
		    Session::put("noError", $jobid);
		  } else {
	        Session::put("hasError", $jobid);
		  }	

	}else{
		
	//Update
	$Update = DB::getInstance()->update('ratings_client',[
	   'star' => $star
	],[
	    "AND"=>["star_type" => $star_type, "jobid" => $jobid]
	  ]);
	  
	   if (count($Update) > 0) {
	        Session::put("updatedError", $jobid);
		} else {
	        Session::put("hasError", $jobid);
		}
	}	 
		  
		// Display the result
		echo "<b class='g'>Thanks! You rated this Client {$star} Stars.</b>";		  	
	
 } else {
	 $jobid = $_POST['jobid'];
	  Session::put("hasError", $jobid);
     
 }
 
?>