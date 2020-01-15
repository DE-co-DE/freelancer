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
$membershipid = Input::get('id');
$query = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
if ($query->count() === 1) {
  $q1 = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
} else {
  $q1 = DB::getInstance()->get("membership_agency", "*", ["membershipid" => $membershipid]);
}
if ($q1->count() === 1) {
 foreach($q1->results() as $r1) {
  $price = $r1->price;
  $bids = $r1->bids;
 }
} 

$amount = getMoneyAsCents($price);

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
		   'membershipid' => $membershipid,
		   'freelancerid' => $freelancer->data()->freelancerid,
		   'paymentid' => 4,
		   'hash' => 4,
		   'payment' => $price,
		   'complete' => 1,
		   'transaction_type' => 1,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	 
 	  
	  
		//Update Membership
		$Update = DB::getInstance()->update('freelancer',[
		   'membershipid' => $membershipid,
		   'membership_bids' => $bids,
		   'membership_date' => date('Y-m-d H:i:s')
		],[
		    'freelancerid' => $freelancer->data()->freelancerid
		  ]);	
		
	} catch(Stripe_CardError $e) {
	 //Do something with the error here	
		
	}
	
	Redirect::to('../membership.php');
	exit();
	
}

?>