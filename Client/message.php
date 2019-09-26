<?php
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
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
          <h1><?php echo $lang['message']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['add']; ?> <?php echo $lang['message']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
            <div class="col-md-3">
              <a href="compose.php" class="btn btn-info btn-block margin-bottom">Compose</a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Folders</h3>
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="inbox.php"><i class="fa fa-inbox"></i> Inbox 
                    <span class="label label-primary pull-right">
                    <?php 	
                     $q1 = DB::getInstance()->get("message", "*", ["AND" => ["user_to" => $client->data()->clientid, "opened" => 0, "delete_remove" => 0]]);
					 echo $q1->count();
                     ?></span></a></li>
                    <li><a href="sent.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
                    <li><a href="important.php"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                    <li><a href="trash.php"><i class="fa fa-trash-o"></i> Trash</a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->		 	
		 	
		  <?php
         $freelancerid = Input::get('id');
         $query = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancerid, "LIMIT" => 1]);
		 if($query->count()) {
		 	
		    $x = 1;
			foreach($query->results() as $row) {
		    $messageList = '';
			  
		    echo $messageList .= '
          
		 <div class="col-md-9">
		 	
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">' . $lang['message'] . ' '. escape($row->name) .'</h3>
                </div>
                  <div class="box-footer bg-body-light">
                  
                  <div class="form-group message-reply-container">	
                    <textarea onclick="showMsgButton('. $row->id .')" id="summernote" placeholder="Post Message" class="form-control"></textarea>
                  </div>
                    <div id="message_btn_'. $row->id .'" class="comment-btn">
                     <a onclick="postMessage('. $row->id .', '.$client->data()->clientid.', '. $freelancerid .')" class="btn btn-primary">Post</a>
					</div>
                  </div>
                <div class="box-body">
                	
                  <div id="messages-list'.$row->id.'" class="post-comments" style="overflow-y: scroll; height: 600px; width: 100%;">
                  '.getMessage($freelancerid, $client->data()->clientid).'
                  </div>
                  
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 
		</div><!-- /.col -->
				    
					 ';
				
             unset($messageList);	 
			 $x++;		 
		   }
		}else {
		 echo $messageList = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>		 		
		
        
			 
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
    <!-- Summernote WYSIWYG-->
    <script src="../assets/js/summernote.min.js" type="text/javascript"></script>    
    <script>
    $(document).ready(function() {
     $('#summernote').summernote({
		  height: 200,                 // set editor height
		
		  minHeight: null,             // set minimum height of editor
		  maxHeight: null,             // set maximum height of editor
		
		  focus: false,                 // set focus to editable area after initializing summernote
		});    
    });
    </script>
    <!-- Functions -->
    <script src="../assets/js/functions.js" type="text/javascript"></script>

    <script type="text/javascript">
	 function showMsgButton(id) {
		$('#message_btn_'+id).fadeIn('slow');
	}   
	function postMessage(id, clientid, freelancerid) {
		var message = $('#summernote').val();
		
		$('#post_comment_'+id).html('<div class="preloader-retina-large preloader-center"></div>');
		
		// Remove the post button
		$('#message_btn_'+id).fadeOut('slow');
		
		$.ajax({
			type: "POST",
			url: "template/requests/post_message.php",
			data: "clientid="+clientid+"&freelancerid="+freelancerid+"&message="+encodeURIComponent(message), 
			cache: false,
			success: function(html) {
				// Remove the loader animation
				$('#post_comment_'+id).html('');
			
				// Append the new comment to the div id
				$('#messages-list'+id).prepend(html);
				
				// Fade In the style="display: none" class
				$('.message-reply-container').fadeIn(500);
				
				// Empty the text area
                $("#summernote").summernote("reset");			
			}
		});
	}
	function delete_the(id, clientid) {
		// id = unique id of the message/comment/chat
		// type = type of post: message/comment/chat
		$('#del_comment_'+id).html('<div class="preloader-retina"></div>');
		
		$.ajax({
			type: "POST",
			url: "template/requests/delete.php",
			data: "id="+id+"&clientid="+clientid, 
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
	function report_the(id, clientid) {
		// id = unique id of the message/comment
		// type = type of post: message/comment
		
		$('#comment'+id).html('<div class="message-reported"><div class="preloader-retina"></div></div>');

		$.ajax({
			type: "POST",
			url: "template/requests/report.php",
			data: "id="+id+"&clientid="+clientid, 
			cache: false,
			success: function(html) {
				$('#comment'+id).html('<div class="message-reported">'+html+'</div>');
			}
		});
	}	
	</script> 
</body>
</html>
