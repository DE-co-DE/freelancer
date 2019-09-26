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
	 
	$query = DB::getInstance()->get("task", "*", ["id" => $id, "LIMIT" => 1]);
	if ($query->count() === 1) {
	 foreach($query->results() as $row) {
	  $milestoneid = $row->milestoneid;
	 }
	}	 
	
	$q = DB::getInstance()->get("milestone", "*", ["id" => $milestoneid, "LIMIT" => 1]);
	if ($q->count() === 1) {
	 foreach($q->results() as $r) {
	  $jobid = $r->jobid;
	 }
	}	
	 
	 // delete the entry
	$query = DB::getInstance()->delete('task', ["id" => $id]);
	
	 // redirect back to the view page
	 header("Location: ../../jobboard.php?a=tasks&id='.$jobid.'");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../jobboard.php?a=milestones&id='.$jobid.'");
 }
 
?>