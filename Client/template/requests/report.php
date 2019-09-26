<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['clientid']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $clientid = $_POST['clientid'];
	 
     $query = DB::getInstance()->get("report", "*", ["AND"=> ["messageid" => $id, "by_userid" => $clientid]]);
	 if($query->count() === 1) {
	 	echo '
                 <section class="comment-list block">
                  <article class="comment-item">
                   <section class="comment-body panel panel-default bg-white">
                    <header class="panel-heading">
                    </header>
                    <div class="panel-body">
                      Already Reported
                    </div>
                   </section>
                  </article><!-- .message-end -->
                 </section>	 	
	 	 ';
	 } else {
   
	   $Insert = DB::getInstance()->insert('report', array(
		   'messageid' => $id,
		   'by_userid' => $clientid,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		 if ($Insert) {
		 	echo '
	                 <section class="comment-list block">
	                  <article class="comment-item">
	                   <section class="comment-body panel panel-default bg-white">
	                    <header class="panel-heading">
	                    </header>
	                    <div class="panel-body">
	                      This Message has been Reported
	                    </div>
	                   </section>
	                  </article><!-- .message-end -->
	                 </section>	 	
		 	 ';		 	
		 } else {
		 	echo '
	                 <section class="comment-list block">
	                  <article class="comment-item">
	                   <section class="comment-body panel panel-default bg-white">
	                    <header class="panel-heading">
	                    </header>
	                    <div class="panel-body">
	                      Something Went Wrong, refresh and report again
	                    </div>
	                   </section>
	                  </article><!-- .message-end -->
	                 </section>	 	
		 	 ';			 	
		 }
	 		 
	 }			 
	
 }
 
?>