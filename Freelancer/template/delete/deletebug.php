<?php
/* 
 DELETE.PHP
 Deletes a specific entry.
*/

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (Input::get('id') && is_numeric(Input::get('id')))
 {
	 // get id value
	 $id = Input::get('id');
	 
	$query = DB::getInstance()->get("bugs", "*", ["id" => $id, "LIMIT" => 1]);
	if ($query->count() === 1) {
	 foreach($query->results() as $row) {
	  $jobid = $row->jobid;
	 }
	}	 
	 
	 // delete comments
	$query = DB::getInstance()->delete('message', ["bugid" => $id]);
	 // delete the entry
	$query = DB::getInstance()->delete('bugs', ["id" => $id]);
	
	 // redirect back to the view page
	 header("Location: ../../jobboard.php?a=bugs&id='.$jobid.'");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../jobboard.php?a=bugs&id='.$jobid.'");
 }
 
?>