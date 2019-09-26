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
$id = Input::get('id');
$query = DB::getInstance()->get("milestone", "*", ["id" => $id, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $jobid = $row->jobid;
  $budget = $row->budget;
 }
} 

$amount = getMoneyAsCents($budget);

$q1 = DB::getInstance()->get("client", "*", ["clientid" => $row->clientid, "LIMIT" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
  $client_email = $r1->email;
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
		   'membershipid' => $id,
		   'freelancerid' => $client->data()->clientid,
		   'paymentid' => 4,
		   'hash' => 4,
		   'payment' => $budget,
		   'complete' => 1,
		   'transaction_type' => 4,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
	  
		//Update Milestone
		$Update = DB::getInstance()->update('milestone',[
		   'funded' => 1
		],[
		    'id' => $id
		  ]);	
		
	} catch(Stripe_CardError $e) {
	 //Do something with the error here	
		
	}
	
	Redirect::to('../jobboard.php?a=milestones&id='.$jobid.'');
	exit();
	
}

?>