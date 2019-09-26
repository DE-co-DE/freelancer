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

//Get Porfolio Data
$portfolioid = Input::get('id');
$query = DB::getInstance()->get("portfolio", "*", ["portfolioid" => $portfolioid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$nid = $row->id;
 	$portfolio_title = $row->title;
 	$date = $row->date;
 	$client = $row->client;
 	$website = $row->website;
 	$portfolio_description = $row->description;
 	$imagelocation = $row->imagelocation;
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
          <h1><?php echo $lang['portfolio']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['portfolio']; ?></li>
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
	          <h3 class="box-title"><?php echo $lang['portfolio']; ?> <?php echo $lang['image']; ?></h3>
	        </div><!-- /.box-header -->
	          <div class="box-body">
	            <div class="form-group">
				 <div class="image text-center">
				  <img src="<?php echo escape($imagelocation); ?>" class="img-thumbnail" width="215" height="215"/>
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
              <h3 class="box-title"><?php echo escape($portfolio_title); ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
            	<h4><b><u><?php echo $lang['date']; ?></u></b></h4>
            	<p><?php echo escape($date); ?></p>
            	<h4><b><u><?php echo $lang['client']; ?></u></b></h4>
            	<p><?php echo escape($client); ?></p>
            	<h4><b><u><?php echo $lang['website']; ?></u></b></h4>
            	<p><?php echo escape($website); ?></p>
            	<h4><b><u><?php echo $lang['description']; ?></u></b></h4>
                <?php echo $portfolio_description; ?>
              <div class="box-footer">
              <div class="tools">
              <a href="portfoliolist.php" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $lang['back']; ?>"><span class="fa fa-caret-up"></span></a>
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
