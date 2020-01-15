<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['freelancerid']) && !empty($_POST['sum']) && !empty($_POST['month']))
 {
	 // get values
	 $freelancerid = $_POST['freelancerid'];
	 $sum = $_POST['sum'];
	 $month = $_POST['month'];
	 
	   $Insert = DB::getInstance()->insert('payfreelancers', array(
		   'freelancerid' => $freelancerid,
		   'amount' => $sum,
		   'month' => $month,
		   'complete' => 1,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		  if (count($Insert) > 0) {
		    Session::put("noError", $freelancerid);
		  } else {
	        Session::put("hasError", $freelancerid);
		  }
	
 } else {
	 $freelancerid = $_POST['freelancerid'];
	  Session::put("hasError", $freelancerid);
     
 }
 
 
?>