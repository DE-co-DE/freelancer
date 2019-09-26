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
          <h1><?php echo $lang['job']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['job']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
		 <div class="col-md-12">
		  <?php if(Session::exists(Posted) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['posted_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(Posted); ?>
		  </div>
		  
		 	<div class="col-md-12">
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['job']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
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
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['proposal']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => 0, "active" => 1, "delete_remove" => 0], "ORDER" => "date_added DESC"]);
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
						  $link .='<a href="jobboard.php?a=overview&id='. escape($row->jobid) .'">'. escape($row->title) .'</a>'; 
						  $delete .='';
						 	
						 }else {
						  $link .='<a href="../jobpost.php?title='. escape($row->slug) .'" target="_blank">'. escape($row->title) .'</a>';
						  $delete .='
					      <a href="addproposal.php?id=' . escape($row->jobid) . '" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['add'] . ' ' . $lang['proposal'] . '"><span class="fa fa-edit"></span></a>';  
						  	
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
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
						
					    echo '<td>
					         '.$delete.'
					         </td>';
					    echo '</tr>';
						unset($delete);
						unset($link);
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
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['proposal']; ?></th>
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

</body>
</html>
