<?php

//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('../index.php');	
}

require_once 'config.php';

//Get Payments Settings Data
$q2 = DB::getInstance()->get("currency", "*", ["id" => $currency]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$currency_code = $r2->currency_code;
 }			
}

//Getting Payement Id from Database
$jobid = Input::get('id');
 
$amount = getMoneyAsCents($jobs_cost);

$q2 = DB::getInstance()->get("client", "*", ["clientid" => $client->data()->clientid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
  $client_email = $r2->email;
 }
}						
						

if (isset($_POST['stripeToken'])) {
	
	$token = $_POST['stripeToken'];
	
	try {
		
	 Stripe_Charge::create([
	  "amount" => $amount,
	  "currency" => $currency_code,
	  "card" => $token,
	  "description" => $client_email
	  ]);	
	 
	   //Insert
	   $Insert = DB::getInstance()->insert('transactions', array(
		   'membershipid' => $jobid,
		   'freelancerid' => $client->data()->clientid,
		   'paymentid' => 4,
		   'hash' => 4,
		   'payment' => $jobs_cost,
		   'complete' => 1,
		   'transaction_type' => 3,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	 
 	  
	  
		//Update Job
		$Update = DB::getInstance()->update('job',[
		   'featured' => 1,
		   'featured_date' => date('Y-m-d H:i:s')
		],[
		    'jobid' => $jobid
		  ]);	
		
	} catch(Stripe_CardError $e) {
	 //Do something with the error here	
		
	}
	
	Redirect::to('../joblist.php');
	exit();
	
}

?>