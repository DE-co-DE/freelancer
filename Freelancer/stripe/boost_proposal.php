<?php

//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
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
$proposalid = Input::get('id');
 
$amount = getMoneyAsCents($bids_cost);

$q2 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancer->data()->freelancerid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
  $freelancer_email = $r2->email;
 }
}						
						

if (isset($_POST['stripeToken'])) {
	
	$token = $_POST['stripeToken'];
	
	try {
		
	 Stripe_Charge::create([
	  "amount" => $amount,
	  "currency" => $currency_code,
	  "card" => $token,
	  "description" => $freelancer_email
	  ]);	
	 
	   //Insert
	   $Insert = DB::getInstance()->insert('transactions', array(
		   'membershipid' => $proposalid,
		   'freelancerid' => $freelancer->data()->freelancerid,
		   'paymentid' => 4,
		   'hash' => 4,
		   'payment' => $bids_cost,
		   'complete' => 1,
		   'transaction_type' => 2,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	 
 	  
	  
		//Update Membership
		$Update = DB::getInstance()->update('proposal',[
		   'featured' => 1,
		   'featured_date' => date('Y-m-d H:i:s')
		],[
		    'id' => $proposalid
		  ]);	
		
	} catch(Stripe_CardError $e) {
	 //Do something with the error here	
		
	}
	
	Redirect::to('../proposallist.php');
	exit();
	
}

?>