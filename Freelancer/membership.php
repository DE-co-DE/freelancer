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
          <h1><?php echo $lang['membership']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['membership']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	

	 <?php if (!Input::get('id')) : ?>	
		 	
	 <section class="w">
	  <div class="container">

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
	  	
	   <div class="row">
	     <h3 class="text-center"><?php echo $lang['freelancers']; ?></h3>
	     <hr class="mint">
	   </div>	
       <br></br>
       
		  <?php
          $query = DB::getInstance()->get("membership_freelancer", "*", ["ORDER" => "item_order ASC"]);
		 if($query->count()) {
		 	
          /*
            Start with variables to help with row creation;
          */
            $startRow = true;
            $postCounter = 0;
		    $x = 1;
			foreach($query->results() as $row) {
		    $List = '';
            $rollover = (escape($row->rollover) === '1') ? Yes : No;
            $buy = (escape($row->buy) === '1') ? Yes : No;
            $see = (escape($row->see) === '1') ? Yes : No;
            $team = (escape($row->team) === '1') ? Yes : No;
			/*
              Check whether we need to add the start of a new row.
              If true, echo a div with the "row" class and set the startRow variable to false 
              If false, do nothing. 
            */
            if ($startRow) {
              echo '<!-- START OF INTERNAL ROW --><div class="row pricing">';
              $startRow = false;
            } 
            /* Add one to the counter because a new post is being added to your page.  */ 
              $postCounter += 1; 
		              
			if ($freelancer->data()->membershipid == $row->membershipid) {
				$sub .='<a class="btn btn-primary btn-block">'.$lang['selected'].'</a>';
			}else {
				$sub .='<a class="btn btn-success btn-block" href="membership.php?id='. escape($row->membershipid) .'">'.$lang['select'].' '.$lang['membership'].'</a>';
			}
				  
		    echo $List .= '
		            <div class="col-lg-6">
		             <div class="price-full">	
		              <h6>'. escape($row->name) .'</h6>
		              <div class="price">
		                <sup>'. escape($currency_symbol) .'</sup>'. escape($row->price) .'
                        <span>per month</span>
		              </div>
		              <hr>
		              <p>'.$lang['number'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. escape($row->bids) .'</strong></p>
		              <p>'.$lang['rollover'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. $rollover .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['buy'].' '.$lang['additional'].' '.$lang['bids'].' :- <strong>'. $buy .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['see'].' '.$lang['other'].' '.$lang['bids'].' :- <strong>'. $see .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['add'].' '.$lang['team'].' '.$lang['members'].' :- <strong>'. $team .'</strong></p>
		              <br>
		               '. $sub .'
		             </div> 
		            </div>
					 ';
				
             unset($List);	 
             unset($sub);	
			 $x++;		
			
            /*
            Check whether the counter has hit 3 posts.  
            If true, close the "row" div.  Also reset the $startRow variable so that before the next post, a new "row" div is being created. Finally, reset the counter to track the next set of three posts.
            If false, do nothing. 
            */
            if ( 2 === $postCounter ) {
                echo '</div><br/><!-- END OF INTERNAL ROW -->';
                $startRow = true;
                $postCounter = 0;
            }  
		   }
		}else {
		 echo $List = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>        
 
	   <div class="row">
	     <h3 class="text-center"><?php echo $lang['agencies']; ?></h3>
	     <hr class="mint">
	   </div>	
       <br></br>
       
		  <?php
          $query = DB::getInstance()->get("membership_agency", "*", ["ORDER" => "item_order ASC"]);
		 if($query->count()) {
		 	
          /*
            Start with variables to help with row creation;
          */
            $startRow = true;
            $postCounter = 0;
		    $x = 1;
			foreach($query->results() as $row) {
		    $List = '';
            $rollover = (escape($row->rollover) === '1') ? Yes : No;
            $buy = (escape($row->buy) === '1') ? Yes : No;
            $see = (escape($row->see) === '1') ? Yes : No;
            $team = (escape($row->team) === '1') ? Yes : No;
			/*
              Check whether we need to add the start of a new row.
              If true, echo a div with the "row" class and set the startRow variable to false 
              If false, do nothing. 
            */
            if ($startRow) {
              echo '<!-- START OF INTERNAL ROW --><div class="row pricing">';
              $startRow = false;
            } 
            /* Add one to the counter because a new post is being added to your page.  */ 
              $postCounter += 1; 
		          
			if ($freelancer->data()->membershipid == $row->membershipid) {
				$sub .='<a class="btn btn-primary btn-block">'.$lang['selected'].'</a>';
			}else {
				$sub .='<a class="btn btn-success btn-block" href="membership.php?id='. escape($row->membershipid) .'">'.$lang['select'].' '.$lang['membership'].'</a>';
			}
				  
		    echo $List .= '
		            <div class="col-lg-6">
		             <div class="price-full">	
		              <h6>'. escape($row->name) .'</h6>
		              <div class="price">
		                <sup>'. escape($currency_symbol) .'</sup>'. escape($row->price) .'
                        <span>per month</span>
		              </div>
		              <hr>
		              <p>'.$lang['number'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. escape($row->bids) .'</strong></p>
		              <p>'.$lang['rollover'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. $rollover .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['buy'].' '.$lang['additional'].' '.$lang['bids'].' :- <strong>'. $buy .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['see'].' '.$lang['other'].' '.$lang['bids'].' :- <strong>'. $see .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['add'].' '.$lang['team'].' '.$lang['members'].' :- <strong>'. $team .'</strong></p>
		              <br>
		               '. $sub .'
		             </div> 
		            </div>
					 ';
				
             unset($List);	 
             unset($sub);	
			 $x++;		
			
            /*
            Check whether the counter has hit 3 posts.  
            If true, close the "row" div.  Also reset the $startRow variable so that before the next post, a new "row" div is being created. Finally, reset the counter to track the next set of three posts.
            If false, do nothing. 
            */
            if ( 2 === $postCounter ) {
                echo '</div><br/><!-- END OF INTERNAL ROW -->';
                $startRow = true;
                $postCounter = 0;
            }  
		   }
		}else {
		 echo $List = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>       
	  </div> <!-- /.container -->
     </section><!-- End section-->
  
	 <?php elseif (Input::get('id')) : ?>

	 <section class="w">
	  <div class="container">
	  	<div class="row pricing">
		  <?php
          $membershipid = Input::get('id');
            $query = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => Input::get('id')]);
			if ($query->count() === 1) {
              $q1 = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => Input::get('id')]);
			} else {
              $q1 = DB::getInstance()->get("membership_agency", "*", ["membershipid" => Input::get('id')]);
			}
			
		  
		 if($q1->count()) {
		 	
            $postCounter = 0;
		    $x = 1;
			foreach($query->results() as $row) {
		    $List = '';
			$price = $row->price;	
            $rollover = (escape($row->rollover) === '1') ? Yes : No;
            $buy = (escape($row->buy) === '1') ? Yes : No;
            $see = (escape($row->see) === '1') ? Yes : No;
            $team = (escape($row->team) === '1') ? Yes : No;

		    echo $List .= '
		            <div class="col-lg-6">
		             <div class="price-full">	
		              <h6>'. escape($row->name) .'</h6>
		              <div class="price">
		                <sup>'. escape($currency_symbol) .'</sup>'. escape($row->price) .'
                        <span>per month</span>
		              </div>
		              <hr>
		              <p>'.$lang['number'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. escape($row->bids) .'</strong></p>
		              <p>'.$lang['rollover'].' '.$lang['of'].' '.$lang['bids'].'  :- <strong>'. $rollover .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['buy'].' '.$lang['additional'].' '.$lang['bids'].' :- <strong>'. $buy .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['see'].' '.$lang['other'].' '.$lang['bids'].' :- <strong>'. $see .'</strong></p>
		              <p>'.$lang['can'].' '.$lang['be'].' '.$lang['able'].' '.$lang['to'].' '.$lang['add'].' '.$lang['team'].' '.$lang['members'].' :- <strong>'. $team .'</strong></p>
		              <br>
		             </div> 
		            </div>
					 ';
				
             unset($List);	 
			 $x++;		
			
		   }
		}else {
		 echo $List = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>     
		 <div class="col-lg-6"> 
			<form action="stripe/membership_charge.php?id=<?php echo $membershipid; ?>" method="POST">
			  <script
			    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			    data-key="<?php echo $stripe[publishable]; ?>"
			    data-name="<?php echo $lang['membership']; ?> <?php echo $lang['payments']; ?>"
			    data-description="<?php echo $lang['payments']; ?>"
			    data-currency="<?php echo $currency_code; ?>"
			    data-email="<?php echo $freelancer->data()->email; ?>"
			    data-amount="<?php echo getMoneyAsCents($price);?>"
			    data-locale="auto">
			  </script>
			</form>		
			<br/>
		  <a href="paypal/addpayment.php?id=<?php echo $membershipid; ?>" class="btn btn-success btn-block"><?php echo $lang['pay']; ?> <?php echo $lang['with']; ?> Paypal</a> 	
		 </div>	
       </div><!-- /.row -->
         
	  </div> <!-- /.container -->
     </section><!-- End section-->	    		 				 
		 <?php endif; ?>     		 	
		 	 
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
