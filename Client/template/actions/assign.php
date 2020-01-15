<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (Input::get('id') && is_numeric(Input::get('id')))
 {
	 // get id value
	 $id = Input::get('id');
     $q1 = DB::getInstance()->get("proposal", "*", ["AND"=> ["id" => $id]]);
	 if($q1->count() === 1) {
	  foreach($q1->results() as $r1) {
		  $jobid = $r1->jobid;
		  $freelancerid = $r1->freelancerid;
	  }	 	
	 }	 
	 
	//Update Job
	$jobUpdate = DB::getInstance()->update('job',[
	   'freelancerid' => $freelancerid,
	   'accepted' => 1,
	   'public' => 0
	],[
	    'jobid' => $jobid
	  ]);
	//Update Proposal
	$Update = DB::getInstance()->update('proposal',[
	   'accepted' => 1
	],[
	    'id' => $id
	  ]);
		  	 
	 // redirect back to the view page
	 header("Location: ../../proposallist.php");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../proposallist.php");
 }
 
?>