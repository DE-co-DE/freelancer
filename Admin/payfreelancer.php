<?php


//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Admin Object
$admin = new Admin();

//Check if Admin is logged in
if (!$admin->isLoggedIn()) {
  Redirect::to('index.php');	
}
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty(Input::get('id')) && !empty(Input::get('sum')) && !empty(Input::get('month')))
 {
	
$freelancerid = Input::get('id');
$sum = Input::get('sum');
$month = Input::get('month');

	 
	   $Insert = DB::getInstance()->insert('pay_freelancer', array(
		   'freelancerid' => $freelancerid,
		   'amount_sum' => $sum,
		   'month_time' => $month,
		   'complete' => 1,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		  if (count($Insert) > 0) {
		    Session::put("noError", $freelancerid);
		  } else {
	        Session::put("hasError", $freelancerid);
		  }
 }
// redirect back to the view page
header("Location: pay.php?id=$freelancerid");

 
?>