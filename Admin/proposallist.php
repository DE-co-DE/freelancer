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
          <h1><?php echo $lang['proposal']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['proposal']; ?> <?php echo $lang['list']; ?></li>
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
                  <h3 class="box-title"><?php echo $lang['proposal']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['accepted']; ?></th>
					   <th><?php echo $lang['date_added']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
				    if(Input::get('id')):
				    $jobid = Input::get('id');
                     $query = DB::getInstance()->get("proposal", ["[>]job" => ["proposal.jobid" => "jobid"]], "proposal.*", ["AND" => ["job.jobid" => $jobid, "proposal.active" => 1, "proposal.delete_remove" => 0]]);
					else:	
                     $query = DB::getInstance()->get("proposal", ["[>]job" => ["proposal.jobid" => "jobid"]], "proposal.*", ["AND" => ["proposal.active" => 1, "proposal.delete_remove" => 0]]);
				    endif;
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
							
					    $q3 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $row->freelancerid]);
						if ($q3->count()) {
							 foreach ($q3->results() as $r3) {
							  $name2 = $r3->name;	
						     }
						}	
						
						 $q = DB::getInstance()->get("job", "*", ["AND"=> ["jobid" => $row->jobid, "accepted" => 1]]);	
						 if($q->count() === 1) {
							 $link .='<a href="jobboard.php?a=overview&id='. escape($row->jobid) .'">'. escape($title_job) .'</a>';  
						 	
						 }else {
						 
						$link .='<a href="../jobpost.php?title='. escape($slug) .'" target="_blank">'. escape($title_job) .'</a>';  
						 }		
								
					    echo '<tr>';
					    echo '<td><a href="../freelancer.php?a=overview&id='. escape($row->freelancerid) .'">'. $name2 .'</a></td>';
					    echo '<td> '.$link.' </td>';
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
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
						
					    if (escape($row->active) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['active'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['in_active'] . ' </span> </td>';
						}											
						
					    echo '<td>
					      <a href="viewproposal.php?id=' . escape($row->proposalid) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . ' ' . $lang['proposal'] . '"><span class="fa fa-eye"></span></a>
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
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['category']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['approved']; ?></th>
					   <th><?php echo $lang['date_added']; ?></th>
					   <th><?php echo $lang['active']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
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
	
	$(".btn-info").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['assign_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/assign.php",
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
	 if(confirm("<?php echo $lang['unassign_job']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/unassign.php",
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
