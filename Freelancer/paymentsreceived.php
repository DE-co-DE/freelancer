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
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['payment']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['payment']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['client']; ?></th>
					   <th><?php echo $lang['transaction']; ?> <?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
						$q3 = DB::getInstance()->get("job", "*", ["AND" =>["freelancerid" => $freelancer->data()->freelancerid, "invite" => "0", "delete_remove" => 0, "accepted" => 1]]);
						if ($q3->count()) {
						 foreach($q3->results() as $r3) {
							 
							$q4 = DB::getInstance()->get("milestone", "*", ["AND" =>["jobid" => $r3->jobid]]);
							if ($q4->count()) {
							 foreach($q4->results() as $r4) {
							 	$milestoneid = $r4->id;			
							 	$clientid = $r4->clientid;		
                              $query = DB::getInstance()->get("transactions", "*", ["AND" => ["membershipid" => $milestoneid, "freelancerid" => $clientid, "transaction_type" => 4], "ORDER" => "date_added DESC"]);
					    
		                     if($query->count()) {
								foreach($query->results() as $row) {
									
								
							    $q1 = DB::getInstance()->get("client", "*", ["clientid" => $clientid, "LIMIT" => 1]);
								if ($q1->count() === 1) {
								 foreach($q1->results() as $r1) {
								  $reporter .='
								             <img src="../Client/'. escape($r1->imagelocation) .'" class="img-responsive img-thumbnail" width="50" height="40"/>
								             <a href="../client.php?a=overview&id='. $clientid .'" target="_blank">'. escape($r1->name) .'</a>
								            ';	
								 }
								}else {
									$reporter .='';
								}	
										
														
							    echo '<tr>';
					            echo '<td>'. $reporter .'</td>';
								if(escape($row->transaction_type) == 1):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['membership'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 2):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['boosting'] . ' ' . $lang['of'] . ' ' . $lang['bids'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 3):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['boosting'] . ' ' . $lang['of'] . ' ' . $lang['job'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 4):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['job'] . ' ' . $lang['work'] . ' </span> </td>';
								endif;	
							    echo '<td>'. escape($currency_symbol) .' '. escape($row->payment) .'</td>';
								
							    if (escape($row->complete) == 1) {
							    echo '<td><span class="label label-success"> ' . $lang['complete'] . ' </span> </td>';
								} else {
							    echo '<td><span class="label label-success"> ' . $lang['in_complete'] . ' </span> </td>';
								}
								
							    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
							    echo '</tr>';
								unset($view);
						        unset($reporter);
							   }
							}
												  
							  
							 }
							}
						 }
						} 	 
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['client']; ?></th>
					   <th><?php echo $lang['transaction']; ?> <?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  	
			  
	         
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
	$(function() {
	
	
	$(".btn-danger").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletejob.php",
				 data: info,
				 success: function()
					   {
						parent.fadeOut('slow', function() {$(this).remove();});
					   }
				});
			 
	
			  }
		   return false;
	
		});
	
	});
	</script>

	<script type="text/javascript">
	$(function() {
	
	$(".btn-info").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['activate_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/activatejob.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});	
	
	});
	</script>
	
	<script type="text/javascript">
	$(function() {
	
	$(".btn-default").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['deactivate_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/deactivatejob.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});		
	
	});
	</script>	
	<script type="text/javascript">
	$(function() {
	
	$(".btn-kafe").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['make_public']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/makepublic.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});	
	
	});
	</script>
	
	<script type="text/javascript">
	$(function() {
	
	$(".btn-warning").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['hide_public']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/hidepublic.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});		
	
	});
	</script>		
    
</body>
</html>
