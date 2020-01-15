<?php 

include "clientdb.php";

$id = $_GET["id"];
$jobid = $_GET["pub"];

if($jobid == 1){
	
	$sql = "update job set public=0 where id=$id";
}
else {
	
	$sql = "update job set public=1 where id=$id";
	
}

$result =mysqli_query($connection,$sql);

header("Location: joblist.php");

?> 