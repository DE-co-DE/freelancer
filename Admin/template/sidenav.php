<?
$admin = new Admin();

$basename = basename($_SERVER["REQUEST_URI"], ".php");
$editname = basename($_SERVER["REQUEST_URI"]);
?>
<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo escape($admin->data()->imagelocation); ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo escape($admin->data()->name); ?></p>
              <!-- Status -->
              <a href="profile.php?a=profile"><i class="fa fa-circle text-success"></i> <?php echo $lang['online']; ?></a>
            </div>
          </div>


          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header"><?php echo $lang['sidenav_header_1']; ?></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="<?php echo $active = ($basename == 'dashboard') ? ' active' : ''; ?>">
            	<a href="dashboard.php"><i class='fa fa-dashboard'></i> <span><?php echo $lang['dashboard']; ?></span></a>
            </li>
            <li class="treeview<?php echo $active = ($basename == 'freelancerlist') ? ' active' : ''; echo $active = ($basename == 'addfreelancer') ? ' active' : ''; echo $active = ($editname == 'editfreelancer.php?id='. Input::get('id').'') ? ' active' : ''; ?>">
              <a href="#"><i class='fa fa-users'></i> <span><?php echo $lang['freelancers']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="freelancerlist.php"><?php echo $lang['freelancer']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addfreelancer.php"><?php echo $lang['add']; ?> <?php echo $lang['freelancer']; ?></a></li>
              </ul>
            </li>            
            <li class="treeview<?php echo $active = ($basename == 'clientlist') ? ' active' : ''; echo $active = ($basename == 'addclient') ? ' active' : ''; echo $active = ($editname == 'editclient.php?a='. Input::get('a').'&id='. Input::get('id').'') ? ' active' : ''; ?>">
              <a href="#"><i class='fa fa-user-md'></i> <span><?php echo $lang['clients']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="clientlist.php"><?php echo $lang['client']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addclient.php"><?php echo $lang['add']; ?> <?php echo $lang['client']; ?></a></li>
              </ul>
            </li>        
            <li class="header"><?php echo $lang['sidenav_header_2']; ?></li>       
            <li class="<?php echo $active = ($basename == 'joblist') ? ' active' : ''; echo $active = ($editname == 'editjob.php?id='. Input::get('id').'') ? ' active' : ''; ?>">
            	<a href="joblist.php"><i class='fa fa-align-left'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['list']; ?></span></a>
            </li>         
            <li class="treeview<?php echo $active = ($basename == 'jobinvite') ? ' active' : ''; echo $active = ($basename == 'addinvite') ? ' active' : ''; echo $active = ($editname == 'editinvite.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'invite.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'viewinvite.php?id='. Input::get('id').'') ? ' active' : '';?>">
            	<a href="jobinvite.php"><i class='fa fa-filter'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['invites']; ?></span></a>
            </li>  
            <li class="treeview<?php 
               echo $active = ($basename == 'proposallist') ? ' active' : ''; echo $active = ($editname == 'proposallist.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'viewproposal.php?id='. Input::get('id').'') ? ' active' : ''; ?>">
            	<a href="proposallist.php"><i class='fa fa-files-o'></i> <span><?php echo $lang['proposals']; ?></span></a>
            </li>  
            <li class="<?php echo $active = ($basename == 'jobassigned') ? ' active' : ''; echo $active = ($editname == 'jobboard.php?a='. Input::get('a').'&id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'jobboard.php?a='. Input::get('a').'&bug='. Input::get('bug').'&id='. Input::get('id').'') ? ' active' : '';?>">
            	<a href="jobassigned.php"><i class='fa fa-address-card'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['assigned']; ?></span></a>
            </li>           
            <li class="treeview<?php echo $active = ($basename == 'categorylist') ? ' active' : ''; echo $active = ($basename == 'addcategory') ? ' active' : ''; echo $active = ($editname == 'editcategory.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($basename == 'changecategory') ? ' active' : ''; ?>">
              <a href="#"><i class='fa fa-align-left'></i> <span><?php echo $lang['categories']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="categorylist.php"><?php echo $lang['category']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addcategory.php"><?php echo $lang['add']; ?> <?php echo $lang['category']; ?></a></li>
                <li><a href="changecategory.php"><?php echo $lang['change']; ?> <?php echo $lang['category']; ?> <?php echo $lang['position']; ?></a></li>
              </ul>
            </li>            
            <li class="treeview<?php echo $active = ($basename == 'skilllist') ? ' active' : ''; echo $active = ($basename == 'addskill') ? ' active' : ''; echo $active = ($editname == 'editskill.php?a='. Input::get('a').'&id='. Input::get('id').'') ? ' active' : ''; ?>">
              <a href="#"><i class='fa fa-cogs'></i> <span><?php echo $lang['skills']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="skilllist.php"><?php echo $lang['skill']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addskill.php"><?php echo $lang['add']; ?> <?php echo $lang['a']; ?> <?php echo $lang['skill']; ?></a></li>
              </ul>
            </li>      
            <li class="<?php echo $active = ($basename == 'reportlist') ? ' active' : ''; ?>">
            	<a href="reportlist.php"><i class='fa fa-align-left'></i> <span><?php echo $lang['message']; ?> <?php echo $lang['reports']; ?></span></a>
            </li>      
            <li class="header"><?php echo $lang['payments']; ?></li> 
            <li class="<?php echo $active = ($basename == 'paymentlist') ? ' active' : '';?>">
            	<a href="paymentlist.php"><i class='fa fa-list'></i> <span><?php echo $lang['payments']; ?> <?php echo $lang['list']; ?></span></a>
            </li>
            <li class="<?php echo $active = ($basename == 'pay') ? ' active' : ''; echo $active = ($editname == 'pay.php?id='. Input::get('id').'') ? ' active' : '';?>">
            	<a href="pay.php"><i class='fa fa-list'></i> <span><?php echo $lang['pay']; ?> <?php echo $lang['freelancers']; ?></span></a>
            </li>
            <li class="header"><?php echo $lang['settings']; ?></li>          
            <li class="treeview<?php 
               echo $active = ($basename == 'home') ? ' active' : '';  echo $active = ($editname == 'settings.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="settings.php?a=site"><i class='fa fa-cogs'></i> <span><?php echo $lang['site']; ?> <?php echo $lang['settings']; ?></span></a>
            </li>      
            <li class="header"><?php echo $lang['frontend']; ?> <?php echo $lang['settings']; ?></li>          
            <li class="treeview<?php 
               echo $active = ($basename == 'home') ? ' active' : '';  echo $active = ($editname == 'home.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="home.php?a=details"><i class='fa fa-info-circle'></i> <span><?php echo $lang['home']; ?> <?php echo $lang['page']; ?></span></a>
            </li>   
            <li class="treeview<?php 
               echo $active = ($basename == 'jobs') ? ' active' : '';  echo $active = ($editname == 'jobs.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="jobs.php?a=headerimg"><i class='fa fa-clipboard'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['page']; ?></span></a>
            </li>      
            <li class="treeview<?php 
               echo $active = ($basename == 'services') ? ' active' : '';  echo $active = ($editname == 'services.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="services.php?a=headerimg"><i class='fa fa-clipboard'></i> <span><?php echo $lang['services']; ?> <?php echo $lang['page']; ?></span></a>
            </li>         
            <li class="treeview<?php 
               echo $active = ($basename == 'about') ? ' active' : '';  echo $active = ($editname == 'about.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="about.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['about']; ?> <?php echo $lang['page']; ?></span></a>
            </li>         
            <li class="treeview<?php 
               echo $active = ($basename == 'how') ? ' active' : '';  echo $active = ($editname == 'how.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="how.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['how']; ?> <?php echo $lang['it']; ?> <?php echo $lang['works']; ?> <?php echo $lang['page']; ?></span></a>
            </li>    
            <li class="treeview<?php 
               echo $active = ($basename == 'pricing') ? ' active' : '';  echo $active = ($editname == 'pricing.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="pricing.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['pricing']; ?> <?php echo $lang['page']; ?></span></a>
            </li>          
            <li class="treeview<?php 
               echo $active = ($basename == 'faq') ? ' active' : '';  echo $active = ($editname == 'faq.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="faq.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['faq']; ?> <?php echo $lang['page']; ?></span></a>
            </li>    
            <li class="treeview<?php 
               echo $active = ($basename == 'contact') ? ' active' : '';  echo $active = ($editname == 'contact.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="contact.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['contact']; ?> <?php echo $lang['page']; ?></span></a>
            </li> 
            <li class="treeview<?php 
               echo $active = ($basename == 'footer') ? ' active' : '';  echo $active = ($editname == 'footer.php?a='. Input::get('a').'') ? ' active' : '';   ?>">
             <a href="footer.php?a=details"><i class='fa fa-clipboard'></i> <span><?php echo $lang['footer']; ?> <?php echo $lang['page']; ?></span></a>
            </li> 
          
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>