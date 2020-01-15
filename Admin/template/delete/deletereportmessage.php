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
	 
	$query = DB::getInstance()->get("report", "*", ["id" => $id, "LIMIT" => 1]);
	if ($query->count() === 1) {
	 foreach($query->results() as $row) {
	  $messageid = $row->messageid;
	 }
	}	 
	 
	 // delete comments
	$query = DB::getInstance()->delete('message', ["id" => $messageid]);
	 // delete the entry
	$query = DB::getInstance()->delete('report', ["id" => $id]);
	
	 // redirect back to the view page
	 header("Location: ../../reportlist.php");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../reportlist.php");
 }
 
?>