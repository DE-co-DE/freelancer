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

//Get Proposal Data
$proposalid = Input::get('id');
$query = DB::getInstance()->get("proposal", "*", ["proposalid" => $proposalid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$jobid = $row->jobid;
 	$freelancerid = $row->freelancerid;
 	$proposal_budget = $row->budget;
 	$proposal_description = $row->description;
 }			
}else {
  Redirect::to('proposallist.php');
}

$q1 = DB::getInstance()->get("job", "*", ["jobid" => $jobid, "LIMIT" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
 	$catid = $r1->catid;
 	$job_title = $r1->title;
 	$job_budget = $r1->budget;
 }			
}	

$q2 = DB::getInstance()->get("category", "*", ["catid" => $catid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$category_name = $r2->name;
 }			
}

$q3 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancerid, "LIMIT" => 1]);
if ($q3->count()) {
 foreach($q3->results() as $r3) {
 	$freelancer_name = $r3->name;
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
          <h1><?php echo $lang['proposal']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['view']; ?> <?php echo $lang['proposal']; ?></li>
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
	          <h3 class="box-title"><?php echo $lang['job']; ?> <?php echo $lang['title']; ?> :- <strong><?php echo escape($job_title); ?></strong></h3>
	        </div><!-- /.box-header -->
	          <div class="box-body">
            	<h4><b><u><?php echo $lang['job']; ?> <?php echo $lang['budget']; ?></u></b></h4>
            	<p><?php echo $currency_symbol; ?> <?php echo escape($job_budget); ?></p>
            	<h4><b><u><?php echo $lang['category']; ?></u></b></h4>
            	<p><?php echo escape($category_name); ?></p>
	          </div><!-- /.box-body -->
	      </div><!-- /.box -->	 
          </div>
          
		 <div class="col-lg-8">
		 <!-- Input addon -->
          <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-text-width"></i>
              <h3 class="box-title"><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?> :- <strong><?php echo escape($freelancer_name); ?></strong></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
            	<h4><b><u><?php echo $lang['budget']; ?></u></b></h4>
            	<p><?php echo $currency_symbol; ?> <?php echo escape($proposal_budget); ?></p>
            	<h4><b><u><?php echo $lang['description']; ?></u></b></h4>
                <?php echo $proposal_description; ?>
              <div class="box-footer">
              <div class="tools">
              <a href="proposallist.php" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $lang['back']; ?>"><span class="fa fa-caret-up"></span></a>
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
