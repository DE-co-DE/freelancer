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
          <h1><?php echo $lang['job']; ?> <?php echo $lang['invite']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['job']; ?> <?php echo $lang['invite']; ?> <?php echo $lang['list']; ?></li>
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
                  <h3 class="box-title"><?php echo $lang['job']; ?> <?php echo $lang['invite']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['client']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['country']; ?></th>
					   <th><?php echo $lang['public']; ?></th>
					   <th><?php echo $lang['acceptance']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["freelancerid" => $freelancer->data()->freelancerid, "invite" => 1, "delete_remove" => 0], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    $q1 = DB::getInstance()->get("category", "*", ["catid" => $row->catid]);
						if ($q1->count()) {
							 foreach ($q1->results() as $r1) {
							  $name1 = $r1->name;	
						     }
						}
						
					    $q2 = DB::getInstance()->get("client", "*", ["clientid" => $row->clientid]);
						if ($q2->count()) {
							 foreach ($q2->results() as $r2) {
							  $name2 = $r2->name;	
						     }
						}
						
						 $q = DB::getInstance()->get("job", "*", ["AND"=> ["jobid" => $row->jobid, "accepted" => 1]]);	
						 if($q->count() === 1) {
						  $link .='<a href="jobboard.php?a=overview&&id='. escape($row->jobid) .'">'. escape($row->title) .'</a>'; 
						 	
						 }else {
						  $link .='<a href="../jobpost.php?title='. escape($row->slug) .'" target="_blank">'. escape($row->title) .'</a>';  
						  	
						 }						
						

                        if($row->accepted === "1"):
					    $mark = '<a id="' . escape($row->id) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $lang['decline'] . '"><span class="fa fa-close"></span></a>';
                        elseif($row->accepted === "2"):
					    $mark = '
					    <a id="' . escape($row->id) . '" class="btn btn-info btn-xs" data-toggle="tooltip" title="' . $lang['accept'] . '"><span class="fa fa-check-square"></span></a>';
                        elseif($row->accepted === "0"):
					    $mark = '
					    <a id="' . escape($row->id) . '" class="btn btn-info btn-xs" data-toggle="tooltip" title="' . $lang['accept'] . '"><span class="fa fa-check-square"></span></a>
					    <a id="' . escape($row->id) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $lang['decline'] . '"><span class="fa fa-close"></span></a>';
						endif;	
									
						
						if($row->opened == 0):						
					    echo '<tr class="success">';
						else:				
					    echo '<tr>';
						endif;
					    echo '<td><a href="../client.php?a=overview&&id='. escape($row->clientid) .'">'. escape($name2) .'</a></td>';
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
					    echo '<td><span class="label label-success"> ' . $lang['accepted'] . ' </span> </td>';
                        elseif($row->accepted === "0"):
					    echo '<td><span class="label label-success"> ' . $lang['waiting'] . ' ' . $lang['acceptance'] . ' </span> </td>';
						endif;
						
					    echo '<td>
					      '.$mark.'
					      <a href="invitenotification.php?id=' . escape($row->jobid) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . '"><span class="fa fa-eye"></span></a>
					      </td>';
					    echo '</tr>';
						unset($link);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['country']; ?></th>
					   <th><?php echo $lang['public']; ?></th>
					   <th><?php echo $lang['acceptance']; ?></th>
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
	 if(confirm("<?php echo $lang['accept_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/acceptjob.php",
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
	 if(confirm("<?php echo $lang['decline_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/declinejob.php",
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
