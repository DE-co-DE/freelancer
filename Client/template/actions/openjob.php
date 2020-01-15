<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (Input::get('id') && is_numeric(Input::get('id')))
 {
	 // get id value
	 $id = Input::get('id');
	 
	 // update the entry
	$query = DB::getInstance()->update('job',[
	    'completed' => 0
	],[
	    'jobid' => $id
	  ]);	 
	
	 // redirect back to the view page
	 header("Location: ../../jobboard.php?a=overview&id='.$id.'");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../jobboard.php?a=overview&id='.$id.'");
 }
 
?>