<?php
 include "clientdb.php";
$jobid = $_GET["id"];

$sql = "delete from job where id=$jobid";
$result = mysqli_query($connection,$sql);

header("Location: joblist.php");


?>