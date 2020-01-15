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


//Get Site Settings Data
$query = DB::getInstance()->get("settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$sid = $row->id;
 	$jobs_header_img = $row->jobs_header_img;
 }			
}	



/*Edit Background Image Data*/
if(isset($_POST['picture'])){
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
  	
	$path = "../assets/img/jobs/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(1920*1280))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $path_new = "assets/img/jobs/";
		 $newname=$path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	       	$new_bgimage = '../'.$jobs_header_img;
			unlink($new_bgimage);
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'jobs_header_img' => $newname
			    ],[
			    'id' => $sid
			    ]);
				
				if (count($siteUpdate) > 0) {
					$noError = true;
				} else {
					$hasError = true;
				}	
  		  
					
	      }else{
		   $imageError = true;	
     	  }
       }else{
       	  $formatError = true;				
	   }
      }else{
      	  $selectError = true;
      }	
  	
  }
 }	
}


?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">
	
    <!-- Include header.php. Contains header content. -->
    <?php include ('template/header.php'); ?> 
    <!-- Fontawesome Icon Picker CSS -->
    <link href="../assets/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" />
    
	<!-- ==============================================
	 Scripts
	 =============================================== -->
	 
    <!-- jQuery 2.1.4 -->
    <script src="../assets/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.6 JS -->
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- Jquery UI 1.10.3 -->
	<script src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script> 

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
          <h1><?php echo $lang['settings']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['settings']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  	
		 <div class="row">	
		 	
		 <div class="col-lg-12">
		 <?php if(isset($selectError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_select']; ?>
		   </div>
	      <?php } ?>	      
	      
		 <?php if(isset($formatError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_format']; ?>
		   </div>
	      <?php } ?>
	      
		 <?php if(isset($imageError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_upload']; ?>
		   </div>
	      <?php } ?>
	      
	      <?php if(isset($hasError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
	      <?php } ?>
	
		  <?php if(isset($noError) && $noError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?></strong>
		   </div>
		  <?php } ?>
		  
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
          </div>	
           
          <div class="col-lg-4">
          	<?php $headerimg = (Input::get('a') == 'headerimg') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="jobs.php?a=headerimg" class="list-group-item<?php echo $headerimg; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['jobs']; ?> <?php echo $lang['header']; ?> <?php echo $lang['image']; ?> <?php echo $lang['settings']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
              
 		 <?php if (Input::get('a') == 'headerimg') : ?>
		  
		  		 <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bg_image']; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="box-body">
                    <div class="form-group">
					 <div class="image text-center">
					  <img src="../<?php 
					  if (isset($_POST['picture'])) {
						 echo escape($newname); 
					  } else {
					  echo escape($jobs_header_img); 
					  } ?>" class="img-thumbnail" width="515" height="415"/>
					 </div>
                    </div>
                   <div style="position:relative;">
	                <a class='btn btn-primary' href='javascript:;'>
		            Choose File...
		            <input type="file" name="photoimg" id="photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
	                <input type="hidden" name="image_name" id="image_name"/>
	                </a>
	                &nbsp;
	                <span class='label label-info' id="upload-file-info"></span>
                  </div>
				  
                  </div><!-- /.box-body -->
                  
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="picture" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button><br></br>
                  </div>

                </form>
              </div><!-- /.box -->     
              
                                       
		 <?php endif; ?>
		 
		</div><!-- /.col -->
		
        
			 
	    </div><!-- /.row -->		  		  
	   </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	 	
      <!-- Include footer.php. Contains footer content. -->	
	  <?php include 'template/footer.php'; ?>	
	 	
     </div><!-- /.wrapper -->   
 
    <!-- Summernote WYSIWYG-->
    <script src="../assets/js/summernote.min.js" type="text/javascript"></script>    
    <script>
    $(document).ready(function() {
     $('#summernote').summernote({
		  height: 300,                 // set editor height
		
		  minHeight: null,             // set minimum height of editor
		  maxHeight: null,             // set maximum height of editor
		
		  focus: false,                 // set focus to editable area after initializing summernote
		});    
    });
    </script>
    <!-- FontAwesome Icon Picker -->
    <script src="../assets/js/fontawesome-iconpicker.min.js" type="text/javascript"></script>
	<script type="text/javascript">
    $('.icp-auto').iconpicker();	
	</script>    
    <script type="text/javascript">
	$(function() {
	
	
	$(".btn-team").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_team']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deleteteam.php",
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
	
	
	$(".btn-timeline").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_timeline']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletetimeline.php",
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
	// Add File
	function addteam() {	

		var file_data = $('#photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#name").val());             
		form_data.append('title', $("#title").val());             
		form_data.append('description', $("#description").val());   
		form_data.append('facebook', $("#facebook").val());             
		form_data.append('twitter', $("#twitter").val());             
		form_data.append('linkedin', $("#linkedin").val());  
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/addteam.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#addteam").modal("hide");
				}
		 });		
		
	}  
	function getTeamDetails(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#update_teamid").val(id);
	    $.post("template/requests/readteamdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var team = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(team.name);
	            $("#update_title").val(team.title);
	            $("#update_description").val(team.description);
	            $("#update_facebook").val(team.facebook);
	            $("#update_twitter").val(team.twitter);
	            $("#update_linkedin").val(team.linkedin);
	            $("#update_photoimg").html(team.imagename);
                $('#update_upload-file-info').html(team.imagename); 
  	            
	        }
	    );
	    // Open modal popup
	    $("#editteam").modal("show");
	}	
	function updateteam() {	

		var file_data = $('#update_photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#update_name").val());             
		form_data.append('title', $("#update_title").val());   
		form_data.append('description', $("#update_description").val());  
		form_data.append('facebook', $("#update_facebook").val());             
		form_data.append('twitter', $("#update_twitter").val());   
		form_data.append('linkedin', $("#update_linkedin").val());           
		form_data.append('teamid', $("#update_teamid").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/editteam.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#editteam").modal("hide");
				}
		 });		
		
	} 	
	// Add Timeline
	function addtimeline() {	
	    // get values
	    var icon = $("#icon").val();
	    var title = $("#title").val();
	    var description = $("#description").val();
	    
		//Built a url to send    
		var info = "icon="+icon+"&title="+title+"&description="+description;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addtimeline.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addtimeline").modal("hide");
            }
        });
	}   
	function getTimelineDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_timelineid").val(id);
	    $.post("template/requests/readtimelinedetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var timeline = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_title").val(timeline.title);
	            $("#update_description").val(timeline.description);
	            $("#update_icon").val(timeline.icon);
	        }
	    );
	    // Open modal popup
	    $("#edittimeline").modal("show");
	}  
	function updatetimeline() {
	    // get values
	    var timelineid = $("#update_timelineid").val();
	    var title = $("#update_title").val();
	    var description = $("#update_description").val();
	    var icon = $("#update_icon").val();
	    
		//Built a url to send       
		var info = "timelineid="+timelineid+"&title="+title+"&description="+description+"&icon="+icon;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatetimeline.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#edittimeline").modal("hide");
            }
        });
	}			
	</script>
    
</body>
</html>
