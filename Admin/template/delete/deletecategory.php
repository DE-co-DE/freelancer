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
 
     // delete the entry
     $query = DB::getInstance()->delete('category', ["id" => $id]);
	
	 // redirect back to the view page
	 header("Location: ../../categorylist.php");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: ../../categorylist.php");
 }
 
?>