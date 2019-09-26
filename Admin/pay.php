<?php 
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Admin Object
$admin = new Admin();

//Check if Admin is logged in
if (!$admin->isLoggedIn()) {
  Redirect::to('index.php');	
}
?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">

    <!-- Include header.php. Contains header content. -->
    <?php include ('template/header.php'); ?>
  
<body class="skin-green sidebar-mini">
     
     <!-- ==============================================
     Wrapper Section
     =============================================== -->
	 <div class="wrapper">
	 	
        <!-- Include navigation.php. Contains navigation content. -->
	 	<?php include ('template/navigation.php'); ?> 
        <!-- Include sidenav.php. Contains sidebar content. -->
	 	<?php include ('template/sidenav.php'); ?> 
	 	
	  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['payment']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['payment']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		   <?php if(!Input::get('id')): ?>		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['freelancers']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?></th>
					   <th><?php echo $lang['withdrawal']; ?> <?php echo $lang['method']; ?></th>
					   <th><?php echo $lang['email']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                    $dbc = mysqli_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password'), Config::get('mysql/db')) OR die('Could not connect because:' .mysqli_connect_error());     
	
				    $qc = DB::getInstance()->get("freelancer", "*", []);
					if ($qc->count()) {
					 foreach($qc->results() as $rc) {
					
					    $qt = DB::getInstance()->get("withdraw", "*", ["freelancerid" => $rc->freelancerid, "LIMIT" => 1]);
						if ($qt->count() === 1) {
						 foreach($qt->results() as $rt) {
						 	$email = $rt->email;
						 	$type = $rt->type;
						 }
						} 		
						 							
					
					    $q1 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $rc->freelancerid, "LIMIT" => 1]);
						if ($q1->count() === 1) {
						 foreach($q1->results() as $r1) {
						  $reporter .='
						             <img src="../Freelancer/'. escape($r1->imagelocation) .'" class="img-responsive img-thumbnail" width="50" height="40"/>
						             <a href="pay.php?id='. escape($r1->freelancerid) .'" target="_blank">'. escape($r1->name) .'</a>
						            ';	
						 }
						}else {
							$reporter .='';
						}
						  
						  
						echo '<tr>';
						echo '<td>'. $reporter .'</td>';
						
						if(escape($type) == 1):
					      echo '<td><span class="label label-success"> Paypal </span> </td>';
						elseif(escape($type) == 2):
					      echo '<td><span class="label label-success"> Stripe </span> </td>';
						elseif(escape($type) == 3):
					      echo '<td><span class="label label-success"> Skrill </span> </td>';
						else:
					      echo '<td><span class="label label-success"> ' . $lang['not'] . ' ' . $lang['set'] . ' </span> </td>';
					    endif;
						
						echo '<td>'. $email .'</td>';
						echo '</tr>';
				unset($email);
				unset($type);
				unset($sum_v);
				unset($reporter);	
						
				 }
	            }
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?></th>
					   <th><?php echo $lang['withdrawal']; ?> <?php echo $lang['method']; ?></th>
					   <th><?php echo $lang['email']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box --> 		 		
		 		
		 <?php elseif(Input::get('id')): ?>	

		 	<div class="col-lg-12">

			  <?php if(Session::exists(noError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(noError); ?>
			  
			  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		        <?php echo Session::get(hasError); ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(hasError); ?>
			  	
			  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(updatedError); ?>			 		
		 	</div>
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['pay']; ?> <?php echo $lang['freelancer']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['month']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
				    
					$qj = DB::getInstance()->get("payments_settings", "*", ["AND" =>["id" => "1"]]);
					if ($qj->count()) {
					 foreach($qj->results() as $rj) {
					   $jobs_percentage = $rj->jobs_percentage;
					 }
					}
            
                    $dbc = mysqli_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password'), Config::get('mysql/db')) OR die('Could not connect because:' .mysqli_connect_error());     
	
				    $qc = DB::getInstance()->get("freelancer", "*", ["AND" => ["freelancerid" => Input::get('id')]]);
					if ($qc->count()) {
					 foreach($qc->results() as $rc) {
							 								
					 	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
						foreach ($months as $month) {
									
						$q3 = DB::getInstance()->get("job", "*", ["AND" =>["freelancerid" => $rc->freelancerid, "invite" => "0", "delete_remove" => 0, "accepted" => 1]]);
						if ($q3->count()) {
						 foreach($q3->results() as $r3) {
							 
							$q4 = DB::getInstance()->get("milestone", "*", ["AND" =>["jobid" => $r3->jobid]]);
							if ($q4->count()) {
							 foreach($q4->results() as $r4) {
							 	$milestoneid = $r4->id;			
							 	$clientid = $r4->clientid;					 	
						 	
							    $q5 = "SELECT SUM(payment) AS value_sum FROM transactions WHERE DATE_FORMAT(date_added,'%M') = '$month' AND freelancerid = '$clientid' AND transaction_type = 4 AND membershipid = '$milestoneid' AND complete = 1 ";
								$r5 = mysqli_query($dbc, $q5);
								while ($row5 = mysqli_fetch_assoc($r5)) {
								
							      $sum_v[] = $row5['value_sum'];
								  
								if (empty($sum_v)) {
								 $sum = 0;
								}else {
								  $summ = array_sum($sum_v);
								  $jab = $jobs_percentage/100 * $summ;
			                      $jab = round($jab, 1);
								  $sum = $summ - $jab;
								}
								  
								}
							}
						   }	
						 
						 }
						}
						
						
								
							
							    $q1 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $rc->freelancerid, "LIMIT" => 1]);
								if ($q1->count() === 1) {
								 foreach($q1->results() as $r1) {
								  $reporter .='
								             <img src="../Freelancer/'. escape($r1->imagelocation) .'" class="img-responsive img-thumbnail" width="50" height="40"/>
								             <a href="../freelancer.php?a=overview&id='. escape($r1->freelancerid) .'" target="_blank">'. escape($r1->name) .'</a>
								            ';	
								 }
								}else {
									$reporter .='';
								}
								
								$freelancerid = $rc->freelancerid;
								$qp = DB::getInstance()->get("pay_freelancer", "*", ["AND" => ["freelancerid" => $rc->freelancerid, "month_time" => $month], "LIMIT" => 1]);
								if ($qp->count() === 0) {
								    $mark = '
								    <a class="btn btn-info btn-xs" 
								    href="payfreelancer.php?id='.$freelancerid.'&sum='.$sum.'&month='.$month.'">
								    <i class="fa fa-check-square"></i> &nbsp; ' . $lang['pay'] . ' ' . $lang['freelancer'] . '</a>';
								} else {
								 $mark .='';
								}							
								
								if($qp->count() == 1) {
								  $com .='<span class="label label-success"> ' . $lang['paid'] . ' </span>';
								}else {
								  $com .='<span class="label label-success"> ' . $lang['not'] . ' ' . $lang['paid'] . ' </span>';	
								}

                       if($sum == 0):
						   $table .='';
					   else:
								echo $table .='
								       <tr>
								       <td>'. $reporter .'</td>
								       <td>'. escape($currency_symbol) .' '. escape($sum) .'</td>
								       <td>'. $com .' </td>
								       <td>'. escape($month) .'</td>
								       <td>'. $mark .'</td>
								       </tr>
								';
					   endif;	   	   
					unset($table);	
								  
								  
						unset($sum_v);
						unset($mark);
						unset($com);
						unset($reporter);	
						
				 }	
					unset($month);	
	            }
	           }					
					unset($table);	

			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['month']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  	
			 <?php endif; ?> 
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->		  		  
	   </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	 	
      <!-- Include footer.php. Contains footer content. -->	
	  <?php include 'template/footer.php'; ?>	
	 	
     </div><!-- /.wrapper -->   

	
	 <!-- ==============================================
	 Scripts
	 =============================================== -->
	 
    <!-- jQuery 2.1.4 -->
    <script src="../assets/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.6 JS -->
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>  
    <!-- DATA TABES SCRIPT -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
      });
    </script> 

	<script type="text/javascript">
	// Pay
	function payde(id, sum, month) {	
	    // get values
		//Built a url to send    
		//var info = "id="+id+"&studentid="+studentid;
	
	    // Add record
	    alert("Nice");
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/pay.php',
			data: "id="+id+"&sum="+sum+"&month="+month
		    success: function()
				   {
					window.location.reload();
				   }
        });
	}   		
	</script> 
    
</body>
</html>
