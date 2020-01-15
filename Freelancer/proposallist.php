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

require_once 'stripe/config.php';
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
          <h1><?php echo $lang['proposal']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['proposal']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	<div class="col-md-12">
		 		
		       <div class="row">
				  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
			       <div class="alert alert-danger fade in">
			        <a href="#" class="close" data-dismiss="alert">&times;</a>
			        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
				   </div>
				  <?php } ?>
				  <?php Session::delete(hasError); ?>  
				  
				  <?php if(Session::exists(Cancelled) == true) { //If email is sent ?>
			       <div class="alert alert-danger fade in">
			        <a href="#" class="close" data-dismiss="alert">&times;</a>
			        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['cancelled_Payment']; ?>
				   </div>
				  <?php } ?>
				  <?php Session::delete(Cancelled); ?>			       	
		       </div>		 		
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['proposal']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['your']; ?> <?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['accepted']; ?></th>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['status']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['featured']; ?></th>
					   <th><?php echo $lang['sponsor']; ?> <?php echo $lang['proposal']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("proposal", "*", ["AND" => ["freelancerid" => $freelancer->data()->freelancerid, "delete_remove" => 0], "ORDER" => "date_added DESC",]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    $q1 = DB::getInstance()->get("job", "*", ["jobid" => $row->jobid]);
						if ($q1->count()) {
							 foreach ($q1->results() as $r1) {
							  $title_job = $r1->title;	
							  $slug = $r1->slug;	
							  $catid = $r1->catid;	
							  $budget = $r1->budget;	
						     }
						}	
						
					    $q2 = DB::getInstance()->get("category", "*", ["catid" => $catid]);
						if ($q2->count()) {
							 foreach ($q2->results() as $r2) {
							  $name1 = $r2->name;	
						     }
						}
						
						 $q = DB::getInstance()->get("job", "*", ["AND"=> ["jobid" => $row->jobid, "accepted" => 1]]);	
						 if($q->count() === 1) {
						  $assigned .='<span class="label label-success"> ' . $lang['assigned'] . ' ' . $lang['to'] . ' ' . $lang['someone'] . ' ' . $lang['else'] . '</span>'; 
						 	
						 }else {
						  $assigned .='<span class="label label-success"> ' . $lang['still'] . ' ' . $lang['waiting'] . ' ' . $lang['to'] . ' ' . $lang['be'] . ' ' . $lang['assigned'] . ' </span>';  
						  	
						 }						
						
                        if(!$row->active == 1):
					    $mark = '
					    <a id="' . escape($row->id) . '" class="btn btn-info btn-xs" data-toggle="tooltip" title="' . $lang['activate'] . '"><span class="fa fa-check-square"></span></a>';
						else:
					    $mark = '<a id="' . escape($row->id) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $lang['deactivate'] . '"><span class="fa fa-close"></span></a>';
						endif;	
						
						if(!$row->featured == "1"):
							$pay .='
							<form action="stripe/boost_proposal.php?id=' . escape($row->id) . '" method="POST">
							  <script
							    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							    data-key="'. $stripe[publishable] .'"
							    data-name="' . $lang['proposal'] . ' ' . $lang['payments'] . '"
							    data-description="' . $lang['boost'] . ' ' . $lang['payments'] . '"
							    data-currency="'.$currency_code.'"
							    data-email="'. $freelancer->data()->email .'"
							    data-amount="'. getMoneyAsCents($bids_cost) .'"
							    data-locale="auto">
							  </script>
							</form>		
							<br/>
						    <a href="paypal/boost_proposal.php?id=' . escape($row->id) . '" class="btn btn-success btn-xs">' . $lang['pay'] . ' ' . $lang['with'] . ' ' . $lang['paypal'] . '</a>					
							';
						   $delete .='
					         <a id="' . escape($row->id) . '" class="btn btn-danger btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>';	
							
                        else:
						   $pay .='
						    <a href="paymentspaid.php" class="btn btn-info btn-xs">' . $lang['view'] . ' ' . $lang['payment'] . '</a>';
						   $delete .='';	
							
						endif;								
												
					    echo '<tr>';
					    echo '<td><a href="../jobpost.php?title='. escape($slug) .'" target="_blank">'. escape($title_job) .'</a></td>';
					    echo '<td><span class="label label-success"> '. escape($name1) .' </span></td>';
					    echo '<td>'. $currency_symbol .' '. escape($budget) .'</td>';
					    echo '<td>'. $currency_symbol .' '. escape($row->budget) .'</td>';
						
					    if ($row->accepted === "2") :
					    echo '<td><span class="label label-success"> ' . $lang['declined'] . ' </span> </td>';
                        elseif($row->accepted === "1"):
					    echo '<td><span class="label label-success"> ' . $lang['assigned'] . ' </span> </td>';
                        elseif($row->accepted === "0"):
					    echo '<td><span class="label label-success"> ' . $lang['waiting'] . ' ' . $lang['to'] . ' ' . $lang['be'] . ' ' . $lang['assigned'] . ' </span> </td>';
						endif;	
						
					    echo '<td>'.$assigned.'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
						
					    if (escape($row->active) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['active'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['in_active'] . ' </span> </td>';
						}	
						
					    if (escape($row->featured) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['featured'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['not'] . ' ' . $lang['featured'] . '</span> </td>';
						}																
						
					    echo '<td>'.$pay.'</td>';
						
					    echo '<td>
					      <a href="viewproposal.php?id=' . escape($row->proposalid) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . ' ' . $lang['proposal'] . '"><span class="fa fa-eye"></span></a>
					      '.$mark.'
					      <a href="editproposal.php?id=' . escape($row->proposalid) . '" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      '.$delete.'
					      </td>';
					    echo '</tr>';
						unset($assigned);
						unset($mark);
						unset($pay);
						unset($delete);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['your']; ?> <?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['accepted']; ?></th>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['status']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['featured']; ?></th>
					   <th><?php echo $lang['sponsor']; ?> <?php echo $lang['proposal']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
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
        $("#example1").dataTable();
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
	 if(confirm("<?php echo $lang['delete_proposal']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deleteproposal.php",
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
	 if(confirm("<?php echo $lang['activate_proposal']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/activateproposal.php",
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
	 if(confirm("<?php echo $lang['deactivate_proposal']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/deactivateproposal.php",
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
