<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
	 // get id value
	 
// get the list of items id separated by cama (,)
$list_order = $_POST['list_order'];
// convert the string list to an array
$list = explode(',' , $list_order);
$i = 1 ;
foreach($list as $id) {
	try {

		//Update
		$Update = DB::getInstance()->update('timeline',[
		   'item_order' => $i
		],[
		    'id' => $id
		  ]);
	  
	} catch (PDOException $e) {
		echo 'PDOException : '.  $e->getMessage();
	}
	$i++ ;
}
 
	  
?>