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
$jobid = Input::get('id');
$query = DB::getInstance()->get("job", "*", ["jobid" => $jobid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
  $clientid = $row->clientid;
  $catid = $row->catid;
  $job_title = $row->title;
  $country = $row->country;
  $job_catid = $row->catid;
  $job_type = $row->job_type;
  $job_budget = $row->budget;
  $job_description = $row->description;
  $job_message = $row->message;
  $job_start_date = $row->start_date;
  $job_end_date = $row->end_date;
  $skills = $row->skills;
  $arr=explode(',',$skills);
 }			
}else {
  Redirect::to('jobinvite.php');
}

$q1 = DB::getInstance()->get("client", "*", ["clientid" => $clientid, "LIMIT" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
 	$client_name = $r1->name;
 	$client_imagelocation = $r1->imagelocation;
 }			
}
	
$q2 = DB::getInstance()->get("category", "*", ["catid" => $catid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$category_name = $r2->name;
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
           
          <div class="col-lg-4">

	  		 <div class="box box-info">
	        <div class="box-header">
	          <h3 class="box-title"><?php echo $lang['client']; ?> <?php echo $lang['name']; ?> <?php echo $lang['and']; ?> <?php echo $lang['image']; ?></h3>
	        </div><!-- /.box-header -->
	          <div class="box-body">
	          	<h4 class="text-center"><strong><?php echo $client_name; ?></strong></h4>
	            <div class="form-group">
				 <div class="image text-center">
				  <img src="../Client/<?php echo escape($client_imagelocation); ?>" class="img-thumbnail" width="215" height="215"/>
				 </div>
	            </div>
			  
	          </div><!-- /.box-body -->
	      </div><!-- /.box -->	 
          </div>

		 <div class="col-lg-8">
		 <!-- Input addon -->
          <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title"><?php echo $lang['message']; ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php echo $job_message; ?>

            </div><!-- /.box-body -->
          </div><!-- /.box -->
          
		 <!-- Input addon -->
          <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title"><?php echo escape($job_title); ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
            	<h4><b><u><?php echo $lang['category']; ?></u></b></h4>
            	<p><?php echo escape($category_name); ?></p>
            	<h4><b><u><?php echo $lang['country']; ?></u></b></h4>
            	<p><?php echo escape($country); ?></p>
            	<h4><b><u><?php echo $lang['budget']; ?></u></b></h4>
            	<p><?php echo $currency_symbol; ?> <?php echo escape($job_budget); ?></p>
            	<h4><b><u><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></u></b></h4>
            	<p><?php echo escape($job_start_date); ?></p>
            	<h4><b><u><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></u></b></h4>
            	<p><?php echo escape($job_end_date); ?></p>
            	<h4><b><u><?php echo $lang['description']; ?></u></b></h4>
                <?php echo $job_description; ?>
			    <h4><b><u><?php echo $lang['skills']; ?></u></b></h4>
	            <?php
	             foreach ($arr as $key => $value) {
	               echo '<label class="label label-success">'. $value .'</label> &nbsp;'; 
	             }
			    ?>
              <div class="box-footer">
              <div class="tools">
              <a href="jobinvite.php" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $lang['back']; ?>"><span class="fa fa-caret-up"></span></a>
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
