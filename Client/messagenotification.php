<?php


//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('../index.php');	
}

$clientid = $client->data()->clientid;
$freelancerid = Input::get('id');


	//Update
	$notUpdate = DB::getInstance()->update('message',[
	    'opened' => 1
	],["AND" => ["user_from" => $freelancerid, "user_to" => $clientid]]);	

// redirect back to the view page
header("Location: message.php?id=$freelancerid");

 
?>