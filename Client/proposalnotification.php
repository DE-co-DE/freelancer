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
$proposalid = Input::get('id');


	//Update
	$notUpdate = DB::getInstance()->update('proposal',[
	    'opened' => 1
	],["AND" => ["proposalid" => $proposalid]]);	

// redirect back to the view page
header("Location: viewproposal.php?id=$proposalid");

 
?>