<?php


//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../index.php');	
}

$freelancerid = $freelancer->data()->freelancerid;
$clientid = Input::get('id');


	//Update
	$notUpdate = DB::getInstance()->update('message',[
	    'opened' => 1
	],["AND" => ["user_from" => $clientid, "user_to" => $freelancerid]]);	

// redirect back to the view page
header("Location: message.php?id=$clientid");

 
?>