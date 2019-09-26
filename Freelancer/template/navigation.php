<?
$test = $_SERVER["REQUEST_URI"];
$freelancer = new Freelancer();
?>
<!-- ==============================================
     Main Header Section
     =============================================== -->
     <header class="main-header">
      <a href="../index.php" class="logo">
       <!-- mini logo for sidebar mini 50x50 pixels -->
       <span class="logo-mini"><b><i class="fa fa-user-md"></i></b></span>
       <!-- logo for regular state and mobile devices -->
       <span class="logo-lg"><b>
       	<?php if($use_icon === '1'): ?>
       		<i class="fa <?php echo $site_icon; ?>"></i>
       	<?php endif; ?> 
       	<?php echo escape($title); ?></b></span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
       <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
       </a>
      <!-- Navbar Right Menu -->
       <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
              <!-- User Account Menu -->              
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
				 <strong><?php echo $lang['languages']; ?></strong>	
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
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
            	<?php // echo $profileimg; ?>
                  <img src="<?php echo escape($freelancer->data()->imagelocation); ?>" class="user-image" alt="User Image"/>
                
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
                  	<?php echo escape($freelancer->data()->name); ?>
                  </span>
                </a>
                <ul class="dropdown-menu">
						<li class="dropdown-menu-header text-center">
							<strong><?php echo $lang['settings']; ?></strong>						
						</li>
						<li class="m_2"><a href="profile.php?a=profile"><i class="fa fa-user"></i> <?php echo $lang['profile']; ?></a></li>
						<li class="m_2"><a href="logout.php"><i class="fa fa-lock"></i> <?php echo $lang['logout']; ?></a></li>	
        		</ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>