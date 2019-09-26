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

//Get Service Data
$serviceid = Input::get('id');
$query = DB::getInstance()->get("service", "*", ["serviceid" => $serviceid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$nid = $row->id;
 	$service_title = $row->title;
 	$rate = $row->rate;
 	$service_description = $row->description;
 }			
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
          <h1><?php echo $lang['service']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['service']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>   	
		 <div class="row">	

		 <div class="col-lg-12">
		 <!-- Input addon -->
          <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title"><?php echo escape($service_title); ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
            	<h4><b><u><?php echo $lang['rate']; ?></u></b></h4>
            	<p><?php echo $currency_symbol; ?> <?php echo escape($rate); ?></p>
            	<h4><b><u><?php echo $lang['description']; ?></u></b></h4>
                <?php echo $service_description; ?>
              <div class="box-footer">
              <div class="tools">
              <a href="servicelist.php" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $lang['back']; ?>"><span class="fa fa-caret-up"></span></a>
              </div>
          </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

		 
		</div><!-- /.col -->
		
        
			 
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
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    
</body>
</html>
