     <?php echo $google_analytics; ?>
     <!-- ==============================================
	 Footer Section
	 =============================================== -->
     <div class="footer">
	  <div class="container">
	   <div class="row">
	  
	    <div class="col-md-4 col-sm-6 text-left">
	     <h4 class="heading no-margin"><?php echo $lang['about']; ?> <?php echo $lang['us']; ?></h4>
		 <hr class="mint">
		 <p><?php echo $footer_about; ?></p>
	    </div><!-- /.col-md-4 -->
	   
	    <div class="col-md-2 col-sm-6 text-left">
	     <h4 class="heading no-margin"><?php echo $lang['company']; ?></h4>
		 <hr class="mint">
		 <div class ="no-padding">
		  <a href="index.php"><?php echo $lang['home']; ?></a>
		  <a href="jobs.php"><?php echo $lang['jobs']; ?></a>
		  <a href="services.php"><?php echo $lang['services']; ?></a>
		  <a href="about.php"><?php echo $lang['about']; ?></a>
		  <a href="how.php"><?php echo $lang['how']; ?> <?php echo $lang['it']; ?> <?php echo $lang['works']; ?></a>
		 </div>
	    </div><!-- /.col-md-2 -->	
		
		<div class="col-md-3 col-sm-6 text-left">
	     <h4 class="heading no-margin"><?php echo $lang['other']; ?> <?php echo $lang['services']; ?></h4>
		 <hr class="mint">
		 <div class="no-padding">
		  <a href="login.php"><?php echo $lang['login']; ?></a>
		  <a href="register.php"><?php echo $lang['register']; ?></a>		 
		  <a href="faq.php"><?php echo $lang['faq']; ?></a>	
		  <a href="contact.php"><?php echo $lang['contact']; ?></a>		 	 
		 </div>
	    </div><!-- /.col-md-3 -->	
		
	    <div class="col-md-3 col-sm-6 text-left">
	    <h4 class="heading no-margin"><?php echo $lang['contact']; ?> <?php echo $lang['us']; ?></h4>
		<hr class="mint">
		 <div class="no-padding">
		   <a><?php echo $contact_location; ?></a>
		   <a><?php echo $contact_phone; ?></a>
		   <a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>	  
		  </div>
		 </div><!-- /.col-md-3 -->
		 
	    </div><!-- /.row -->
	   <div class="clearfix"></div>
	  </div><!-- /.container-->
     </div><!-- /.footer -->			
	 
	 <!-- ==============================================
	 Bottom Footer Section
	 =============================================== -->	
     <footer id="main-footer" class="main-footer">
	  <div class="container">
	   <div class="row">
	   
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		 <ul class="social-links">
		  <li><a href="<?php echo $facebook; ?>"><i class="fa fa-facebook fa-fw"></i></a></li>
		  <li><a href="<?php echo $twitter; ?>"><i class="fa fa-twitter fa-fw"></i></a></li>
		  <li><a href="<?php echo $google; ?>"><i class="fa fa-google-plus fa-fw"></i></a></li>
		  <li><a href="<?php echo $instagram; ?>"><i class="fa fa-instagram fa-fw"></i></a></li>
		  <li><a href="<?php echo $linkedin; ?>"><i class="fa fa-linkedin fa-fw"></i></a></li>
		 </ul>
		</div>
	    <!-- /.col-sm-4 -->
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 revealOnScroll" data-animation="bounceIn" data-timeout="200">
		 <div class="img-responsive text-center">
       	<?php if($use_icon === '1'): ?>
       		<i class="fa <?php echo $site_icon; ?> logo"></i>
       	<?php endif; ?> 		 </div><!-- End image-->
		</div>
		<!-- /.col-sm-4 -->
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-right revealOnScroll" data-animation="slideInRight" data-timeout="200">
		 <p><?php auto_copyright('','All Rights Reserved'); ?> </p>
		</div>
		<!-- /.col-sm-4 -->
				
	   </div><!-- /.row -->
	  </div><!-- /.container -->
	 </footer><!-- /.footer -->  
	 
     <a id="scrollup">Scroll</a>