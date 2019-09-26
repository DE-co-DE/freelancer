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
    <!-- Theme style -->
    <link href="../assets/css/AdminLTE/AdminLTE.min.css" rel="stylesheet" type="text/css" />

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
          <h1><?php echo $lang['mailbox']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['mailbox']; ?> <?php echo $lang['list']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
           
            <div class="col-md-3">
              <a href="compose.php" class="btn btn-info btn-block margin-bottom"> <?php echo $lang['compose']; ?></a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"> <?php echo $lang['folders']; ?></h3>
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                	<?php $basename = basename($_SERVER["REQUEST_URI"], ".php"); ?>
		          	<?php $inbox = ($basename == 'inbox') ? ' active' : ''; ?>
		          	<?php $sent = ($basename == 'sent') ? ' active' : ''; ?>
		          	<?php $favorite = ($basename == 'favorite') ? ' active' : ''; ?>
		          	<?php $trash = ($basename == 'trash') ? ' active' : ''; ?>
                  <ul class="nav nav-pills nav-stacked">
                    <li class="<?php echo $inbox; ?>"><a href="inbox.php"><i class="fa fa-inbox"></i> <?php echo $lang['inbox']; ?> 
                    <span class="label label-primary pull-right">
                    <?php 	
                     $q1 = DB::getInstance()->get("message", "*", ["AND" => ["user_to" => $freelancer->data()->freelancerid, "opened" => 0, "delete_remove" => 0, "disc" => 0 ]]);
					 echo $q1->count();
                     ?></span></a></li>
                    <li class="<?php echo $sent; ?>"><a href="sent.php"><i class="fa fa-envelope-o"></i> <?php echo $lang['sent']; ?></a></li>
                    <li class="<?php echo $favorite; ?>"><a href="favorite.php"><i class="fa fa-star-o text-red"></i> <?php echo $lang['favorites']; ?></a></li>
                    <li class="<?php echo $trash; ?>"><a href="trash.php"><i class="fa fa-trash-o"></i> <?php echo $lang['trash']; ?></a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
            
            <div class="col-md-9">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"> <?php echo $lang['sent']; ?> <?php echo $lang['mail']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                    <table id="example1" class="table table-bordered">
                    
                    <thead>
                      <tr>
					   <th><?php echo $lang['favorite']; ?></th>
					   <th><?php echo $lang['to']; ?></th>
					   <th><?php echo $lang['message']; ?></th>
					   <th><?php echo $lang['time']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>	
                      <tbody>
				    <?php
				     $freelancerid = $freelancer->data()->freelancerid;
                     $query = DB::getInstance()->get("message", "*", ["ORDER" => "date_added DESC", 'AND' => ["user_from" => $freelancer->data()->freelancerid, "active" => 1, "delete_remove" => 0]]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    $q1 = DB::getInstance()->get("client", "*", ["clientid" => $row->user_to]);
						if ($q1->count()) {
							 foreach ($q1->results() as $r1) {
							  $name1 = $r1->name;	
						     }
						}
						
						$blurb = truncateHtml($row->message, 75);
							
					    echo '<tr id="comment'.$row->id.'">';
					    echo '<td>
		                 <div id="message-action'.$row->id.'">
					      '.star($row->id, $freelancerid, null) .'
					     </div> 
					      </td>';
					    echo '<td class="mailbox-name"><a href="message.php?id=' . escape($row->user_to) . '">'. escape($name1) .'</a></td>';
					    echo '<td class="mailbox-subject">'. $blurb .'</td>';
					    echo '<td class="mailbox-date">'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
						
					    echo '<td>
					      <a href="message.php?id=' . escape($row->user_to) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . '"><span class="fa fa-eye"></span></a>
					      <a onclick="delete_the('.$row->id.', '.$freelancerid.')" class="btn btn-danger btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><i class="fa fa-trash-o"></i></a></td>';
						
					    echo '</tr>';
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>                    
                      </tbody>
                    
                    <thead>
                      <tr>
					   <th><?php echo $lang['favorite']; ?></th>
					   <th><?php echo $lang['to']; ?></th>
					   <th><?php echo $lang['message']; ?></th>
					   <th><?php echo $lang['time']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
              </div><!-- /. box -->
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
    <!-- DATA TABES SCRIPT -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable({
        "pagingType": "full_numbers",
        "order": []
        });
      });
    </script>
    <script type="text/javascript">
	function doStar(id, userid, state) {
		// id = unique id of the message
		// type = 1 do the like, 2 do the dislike
		$('#like_btn'+id).html('<div class="privacy_loader"></div>');
		$('#doStar'+id).removeAttr('onclick');
		$.ajax({
			type: "POST",
			url: "template/requests/star.php",
			data: "id="+id+"&userid="+userid+"&state="+state, 
			cache: false,
			success: function(html) {
				$('#message-action'+id).empty();
				$('#message-action'+id).html(html);
			}
		});
	}		
	function delete_the(id, freelancerid) {
		// id = unique id of the message/comment/chat
		// type = type of post: message/comment/chat
		$('#del_comment_'+id).html('<div class="preloader-retina"></div>');
		
		$.ajax({
			type: "POST",
			url: "template/requests/delete.php",
			data: "id="+id+"&freelancerid="+freelancerid, 
			cache: false,
			success: function(html) {
				if(html == '1') {
				   $('#comment'+id).fadeOut(500, function() { $('#comment'+id).remove(); });
				} else {
				   $('#comment'+id).html($('#del_comment_'+id).html('Sorry, the message could not be removed, please refresh the page and try again.'));
				}
			}
		});
	}	
	</script> 

</body>
</html>
