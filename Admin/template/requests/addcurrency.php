<?php

 // connect to the database
 require_once '../../../core/backinit.php';

 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['currency_code']) && !empty($_POST['currency_name']) && !empty($_POST['currency_symbol']))
 {
	 // get values
	 $currency_code = $_POST['currency_code'];
	 $currency_name = $_POST['currency_name'];
	 $currency_symbol = $_POST['currency_symbol'];
	 
	   $Insert = DB::getInstance()->insert('currency', array(
		   'currency_code' => $currency_code,
		   'currency_name' => $currency_name,
		   'currency_symbol' => $currency_symbol,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	

		  if (count($Insert) > 0) {
		    Session::put("noError", $currency_code);
		  } else {
	        Session::put("hasError", $currency_code);
		  }
	
 } else {
	 $currency_code = $_POST['currency_code'];
	  Session::put("hasError", $currency_code);
 }
 
 
?>