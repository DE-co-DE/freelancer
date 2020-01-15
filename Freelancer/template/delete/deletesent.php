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

	//Update Message
	$messageUpdate = DB::getInstance()->update('message',[
	    'active' => 0,
	    'opened' => 1,
	    'delete_remove' => 1
	],['id' => $id]);	
	
	 // redirect back to the view page
	 header("Location: ../../sent.php");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../sent.php");
 }
 
?>