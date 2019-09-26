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
$jobid = Input::get('id');


	//Update
	$notUpdate = DB::getInstance()->update('job',[
	    'opened' => 1
	],["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid]]);	

// redirect back to the view page
header("Location: viewinvite.php?id=$jobid");

 
?>