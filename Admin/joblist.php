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
          <h1><?php echo $lang['job']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['job']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>   
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		       <div class="row">
		       	<div class="col-lg-12">
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
		       </div>		 		
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['job']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['country']; ?></th>
					   <th><?php echo $lang['public']; ?></th>
					   <th><?php echo $lang['acceptance']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['featured']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => 0, "delete_remove" => 0], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    $q1 = DB::getInstance()->get("category", "*", ["catid" => $row->catid]);
						if ($q1->count()) {
							 foreach ($q1->results() as $r1) {
							  $name1 = $r1->name;	
						     }
						}


						 $q = DB::getInstance()->get("job", "*", ["AND"=> ["jobid" => $row->jobid, "accepted" => 1]]);	
						 if($q->count() === 1) {
						  $link .='<a href="jobboard.php?a=overview&&id='. escape($row->jobid) .'">'. escape($row->title) .'</a>'; 
						  $view .='
					      <a href="jobboard.php?a=overview&&id='. escape($row->jobid) .'" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . ' ' . $lang['job'] . '"><span class="fa fa-eye"></span></a>'; 
						  $delete .='';
						  $pay .='';
						 	
						 }else {
						  $link .='<a href="../jobpost.php?title='. escape($row->slug) .'" target="_blank">'. escape($row->title) .'</a>';
						  $view .='
					      <a href="../jobpost.php?title='. escape($row->slug) .'" target="_blank" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . ' ' . $lang['job'] . '"><span class="fa fa-eye"></span></a>'; 
						  $delete .='
					      <a href="editjob.php?id=' . escape($row->jobid) . '" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>';  
						  			
	                        if(!$row->public == 1):
						    $public = '
						    <a id="' . escape($row->id) . '" class="btn btn-kafe btn-xs" data-toggle="tooltip" title="' . $lang['make'] . ' ' . $lang['public'] . '"><span class="fa fa-globe"></span></a>';
							else:
						    $public = '<a id="' . escape($row->id) . '" class="btn btn-warning btn-xs" data-toggle="tooltip" title="' . $lang['hide'] . ' ' . $lang['from'] . ' ' . $lang['public'] . '"><span class="fa fa-user-secret"></span></a>';
							endif;	
								
	                        if(!$row->active == 1):
						    $mark = '
						    <a id="' . escape($row->id) . '" class="btn btn-info btn-xs" data-toggle="tooltip" title="' . $lang['activate'] . '"><span class="fa fa-check-square"></span></a>';
							else:
						    $mark = '<a id="' . escape($row->id) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $lang['deactivate'] . '"><span class="fa fa-close"></span></a>';
							endif;
							
							
							
						 }						
												
					    echo '<tr>';
					    echo '<td>'.$link.'</td>';
					    echo '<td>'. escape($name1) .'</td>';
					    echo '<td>'. $currency_symbol .' '. escape($row->budget) .'</td>';
					    echo '<td>'. escape($row->start_date) .'</td>';
					    echo '<td>'. escape($row->end_date) .'</td>';
					    echo '<td>'. escape($row->country) .'</td>';
						
					    if (escape($row->public) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['public'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['not_public'] . ' </span> </td>';
						}
						
					    if ($row->accepted === "2") :
					    echo '<td><span class="label label-success"> ' . $lang['declined'] . ' </span> </td>';
                        elseif($row->accepted === "1"):
					    echo '<td><span class="label label-success"> ' . $lang['assigned'] . ' </span> </td>';
                        elseif($row->accepted === "0"):
					    echo '<td><span class="label label-success"> ' . $lang['waiting'] . ' ' . $lang['to'] . ' ' . $lang['be'] . ' ' . $lang['assigned'] . ' </span> </td>';
						endif;
						
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
						
					    echo '<td>
					      <a href="proposallist.php?id=' . escape($row->jobid) . '" class="btn btn-m btn-xs" data-toggle="tooltip" title="' . $lang['view'] . ' ' . $lang['proposals'] . '"><span class="fa fa-eye"></span></a>
					      '.$delete.'
					      '.$public.'
					      '.$view.'
                           </td>';
					    echo '</tr>';
						unset($delete);
						unset($public);
						unset($mark);
						unset($link);
						unset($view);
						unset($pay);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['country']; ?></th>
					   <th><?php echo $lang['public']; ?></th>
					   <th><?php echo $lang['acceptance']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['featured']; ?></th>
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
