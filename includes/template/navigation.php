 <? 
$basename = basename($_SERVER["REQUEST_URI"], ".php");
$editname = basename($_SERVER["REQUEST_URI"]);
$test = $_SERVER["REQUEST_URI"];
?>
     <!-- ==============================================
     Navigation Section
     =============================================== -->
	<header id="header" headroom="" role="banner" tolerance="5" offset="700" class="navbar navbar-fixed-top navbar--white ng-isolate-scope headroom headroom--top">
	  <nav role="navigation">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle header-nav__button" data-toggle="collapse" data-target=".navbar-main">
	        <span class="icon-bar header-nav__button-line"></span>
	        <span class="icon-bar header-nav__button-line"></span>
	        <span class="icon-bar header-nav__button-line"></span>
	      </button>
	      <div class="header-nav__logo">
	        <a class="header-nav__logo-link navbar-brand" href="index.php">
	       	<?php if($use_icon === '1'): ?>
	       		<i class="fa <?php echo $site_icon; ?>"></i>
	       	<?php endif; ?> 
	       	<?php echo escape($title); ?></a>
			
		 
	      </div>
		  
		
		  
		  
	    </div>
		
		<br>
		
		  <div class="collapse navbar-collapse navbar-main navbar-right">
		  <form action="searchpage.php" method="get">
		  <div class="d-inline-block">
		  <input type="text" class="searchinput" name="searchterm" placeholder="<?php echo $searchterm; ?>" value=""/> 
		 <button  class="btn btn-success">Search</button>
</div>
		 </form>
		  </div>
		  <style>
		  
		  .searchinput {
			 
			  min-width: 450px;
			  height: 32px;
		  }
		  @media screen and (max-width: 600px) {
			.searchinput {
			 
			 min-width: 350px;
			 height: 32px;
		 }
}
		  </style>
		  
		 
		
		<br><br>
	    <div class="collapse navbar-collapse navbar-main navbar-right">
	      <ul class="nav navbar-nav header-nav__navigation">
	        <li class="header-nav__navigation-item
	         <?php echo $active = ($basename == 'index') ? ' active' : ''; ?>">
	          <a href="index.php" class="header-nav__navigation-link">
	            <?php echo $lang['home']; ?>
	          </a>
	        </li>
	        <li class="header-nav__navigation-item <?php echo $active = ($basename == 'jobs') ? ' active' : ''; echo $active = ($editname == 'jobpost.php?title='. Input::get('title').'') ? ' active' : '';?>">
	          <a href="jobs.php" class="header-nav__navigation-link ">
	            <?php echo $lang['jobs']; ?>
	          </a>
	        </li>
	        <li class="header-nav__navigation-item <?php echo $active = ($basename == 'services') ? ' active' : ''; echo $active = ($editname == 'freelancer.php?a='. Input::get('a').'&id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'searchpage.php?searchterm='. Input::get('searchterm').'') ? ' active' : ''; ?>">
	          <a href="services.php" class="header-nav__navigation-link ">
	            <?php echo $lang['services']; ?>
	          </a>
	        </li>
	        <li class="header-nav__navigation-item <?php echo $active = ($basename == 'about') ? ' active' : ''; ?>">
	          <a href="about.php" class="header-nav__navigation-link ">
	            <?php echo $lang['about']; ?>
	          </a>
	        </li>
	        <li class="header-nav__navigation-item <?php echo $active = ($basename == 'how') ? ' active' : ''; ?>">
	          <a href="how.php" class="header-nav__navigation-link ">
	            <?php echo $lang['how']; ?> <?php echo $lang['it']; ?> <?php echo $lang['works']; ?>
	          </a>
	        </li>
	        <li class="header-nav__navigation-item <?php echo $active = ($basename == 'pricing') ? ' active' : ''; ?>">
	          <a href="pricing.php" class="header-nav__navigation-link ">
	            <?php echo $lang['pricing']; ?>
	          </a>
	        </li>
	        
		 <?php
		 //Start new Admin object
		 $admin = new Admin();
		 //Start new Client object
		 $client = new Client();
		 //Start new Freelancer object
		 $freelancer = new Freelancer(); 
		 
		 if ($admin->isLoggedIn()) { ?>
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
            	<?php // echo $profileimg; ?>
                  <img src="Admin/<?php echo escape($admin->data()->imagelocation); ?>" class="user-image" alt="User Image"/>
                
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
                  	<?php echo escape($admin->data()->name); ?>
                  </span>
                </a>
                <ul class="dropdown-menu user_drop_down">
						<li class="m_2"><a href="Admin/dashboard.php"><i class="fa fa-dashboard"></i><?php echo $lang['dashboard']; ?></a></li>
						<li class="m_2"><a href="Admin/profile.php?a=profile"><i class="fa fa-user"></i><?php echo $lang['view']; ?> <?php echo $lang['profile']; ?></a></li>
						<li class="m_2"><a href="Admin/logout.php"><i class="fa fa-lock"></i> <?php echo $lang['logout']; ?></a></li>	
        		</ul>
              </li>
		<?php } elseif($client->isLoggedIn()) { ?>
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
            	<?php // echo $profileimg; ?>
                  <img src="Client/<?php echo escape($client->data()->imagelocation); ?>" class="user-image" alt="User Image"/>
                
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
                  	<?php echo escape($client->data()->name); ?>
                  </span>
                </a>
                <ul class="dropdown-menu user_drop_down">
						<li class="m_2"><a href="Client/"><i class="fa fa-dashboard"></i><?php echo $lang['dashboard']; ?></a></li>
						<li class="m_2"><a href="Client/profile.php?a=profile"><i class="fa fa-user"></i><?php echo $lang['view']; ?> <?php echo $lang['profile']; ?></a></li>
						<li class="m_2"><a href="Client/logout.php"><i class="fa fa-lock"></i> <?php echo $lang['logout']; ?></a></li>	
        		</ul>
              </li>
		<?php } elseif($freelancer->isLoggedIn()) { ?>
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
            	<?php // echo $profileimg; ?>
                  <img src="Freelancer/<?php echo escape($freelancer->data()->imagelocation); ?>" class="user-image" alt="User Image"/>
                
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
                  	<?php echo escape($freelancer->data()->name); ?>
                  </span>
                </a>
                <ul class="dropdown-menu user_drop_down">
						<li class="m_2"><a href="Freelancer/index.php"><i class="fa fa-dashboard"></i><?php echo $lang['dashboard']; ?></a></li>
						<li class="m_2"><a href="Freelancer/profile.php?a=profile"><i class="fa fa-user"></i><?php echo $lang['view']; ?> <?php echo $lang['profile']; ?></a></li>
						<li class="m_2"><a href="Freelancer/logout.php"><i class="fa fa-lock"></i> <?php echo $lang['logout']; ?></a></li>	
        		</ul>
              </li>
		<?php } else { ?>		 		        
	        <li class="header-nav__navigation-item">
	          <a class="header-nav__navigation-link" href="login.php"><?php echo $lang['login']; ?></a>
	        </li>
	        <li class="header-nav__navigation-item">
	          <a class="header-nav__navigation-link header-nav__navigation-link--outline" href="register.php"><?php echo $lang['signup']; ?> <?php echo $lang['for']; ?> <?php echo $lang['free']; ?></a>
	        </li>
		 <?php } ?>              		 	

              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  	<?php echo $lang['languages']; ?>
                </a>
                <ul class="dropdown-menu">
					<li class="m_2"><a href="<?php echo $test; ?>?lang=english">English</a></li>
					<li class="m_2"><a href="<?php echo $test; ?>?lang=french">French</a></li>
					<li class="m_2"><a href="<?php echo $test; ?>?lang=german">German</a></li>	
					<li class="m_2"><a href="<?php echo $test; ?>?lang=portuguese">Portuguese</a></li>
					<li class="m_2"><a href="<?php echo $test; ?>?lang=spanish">Spanish</a></li>
					<li class="m_2"><a href="<?php echo $test; ?>?lang=russian">Russian</a></li>	
					<li class="m_2"><a href="<?php echo $test; ?>?lang=chinese">Chinese</a></li>	
        		</ul>
              </li>


              	        
	      </ul>
	    </div>
	  </nav>
	</header>     
	<script>
	$('.dropdown-toggle').click(function(){
		$('.user_drop_down').toggle()
	})
	$('.header-nav__button').click(function(){
		$('.navbar-main').toggle()
	})
	</script>