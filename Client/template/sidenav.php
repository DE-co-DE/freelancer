<?
$client = new Client();

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
              <img src="<?php echo escape($client->data()->imagelocation); ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo escape($client->data()->name); ?></p>
              <!-- Status -->
              <a href="profile.php?a=profile"><i class="fa fa-circle text-success"></i> <?php echo $lang['online']; ?></a>
            </div>
          </div>


          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header"><?php echo $lang['sidenav_header_2']; ?></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="<?php echo $active = ($basename == 'index') ? ' active' : ''; ?>">
            	<a href="index.php"><i class='fa fa-dashboard'></i> <span><?php echo $lang['dashboard']; ?></span></a>
            </li>
            <li class="treeview<?php echo $active = ($basename == 'joblist') ? ' active' : ''; echo $active = ($basename == 'addjob') ? ' active' : ''; echo $active = ($editname == 'editjob.php?id='. Input::get('id').'') ? ' active' : ''; ?>">
              <a href="#"><i class='fa fa-align-left'></i> <span><?php echo $lang['jobs']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="joblist.php"><?php echo $lang['job']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addjob.php"><?php echo $lang['post']; ?> <?php echo $lang['a']; ?> <?php echo $lang['job']; ?></a></li>
              </ul>
            </li> 
            <li class="treeview<?php echo $active = ($basename == 'jobinvite') ? ' active' : ''; echo $active = ($basename == 'addinvite') ? ' active' : ''; echo $active = ($editname == 'editinvite.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'invite.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'viewinvite.php?id='. Input::get('id').'') ? ' active' : '';?>">
              <a href="#"><i class='fa fa-filter'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['invites']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="jobinvite.php"><?php echo $lang['job']; ?> <?php echo $lang['invites']; ?> <?php echo $lang['list']; ?></a></li>
                <li><a href="addinvite.php"><?php echo $lang['post']; ?> <?php echo $lang['a']; ?> <?php echo $lang['job']; ?> <?php echo $lang['invite']; ?></a></li>
              </ul>
            </li> 
            <li class="treeview<?php 
               echo $active = ($basename == 'proposallist') ? ' active' : ''; echo $active = ($editname == 'proposallist.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'editproposal.php?id='. Input::get('id').'') ? ' active' : ''; echo $active = ($editname == 'viewproposal.php?id='. Input::get('id').'') ? ' active' : ''; ?>">
             <a href="proposallist.php"><i class='fa fa-files-o'></i> <span><?php echo $lang['proposals']; ?></span>
                <span class="label label-info pull-right">
                    <?php 	
                     $q1 = DB::getInstance()->get("proposal", ["[>]job" => ["proposal.jobid" => "jobid"]], "*", ["AND" => ["job.clientid" => $client->data()->clientid, "proposal.opened" => 0, "proposal.delete_remove" => 0]]);
					 echo $q1->count();
                     ?></span></a>
            </li>    
            <li class="<?php echo $active = ($basename == 'jobassigned') ? ' active' : ''; echo $active = ($editname == 'jobboard.php?a='. Input::get('a').'&id='. Input::get('id').'') ? ' active' : '';?>">
            	<a href="jobassigned.php"><i class='fa fa-address-card'></i> <span><?php echo $lang['jobs']; ?> <?php echo $lang['assigned']; ?></span></a>
            </li>       
            <li class="header"><?php echo $lang['payments']; ?></li> 
            <li class="<?php echo $active = ($basename == 'paymentlist') ? ' active' : '';?>">
            	<a href="paymentlist.php"><i class='fa fa-list'></i> <span><?php echo $lang['payments']; ?> <?php echo $lang['list']; ?></span></a>
            </li>  
            <li class="header"><?php echo $lang['sidenav_header_3']; ?></li>          
            <li class="treeview<?php 
               echo $active = ($basename == 'overview') ? ' active' : '';   ?>">
             <a href="overview.php?a=profile"><i class='fa fa-info-circle'></i> <span><?php echo $lang['overview']; ?></span></a>
            </li> 
            <li class="header"><?php echo $lang['sidenav_header_4']; ?></li>    
            <li class="treeview<?php echo $active = ($basename == 'inbox') ? ' active' : ''; echo $active = ($basename == 'compose') ? ' active' : ''; echo $active = ($basename == 'sent') ? ' active' : ''; echo $active = ($basename == 'favorite') ? ' active' : ''; echo $active = ($basename == 'trash') ? ' active' : ''; echo $active = ($editname == 'message.php?id='. Input::get('id').'') ? ' active' : '';?>">
              <a href="#">
                <i class="fa fa-envelope"></i> <span><?php echo $lang['mailbox']; ?></span>
                <span class="label label-info pull-right" style="margin-right: 20px;">
                    <?php 	
                     $q1 = DB::getInstance()->get("message", "*", ["AND" => ["user_to" => $client->data()->clientid, "opened" => 0, "delete_remove" => 0, "disc" => 0]]);
					 echo $q1->count();
                     ?></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="active"><a href="inbox.php"><?php echo $lang['inbox']; ?> 
                <span class="label label-info pull-right">
                    <?php 	
                     $q1 = DB::getInstance()->get("message", "*", ["AND" => ["user_to" => $client->data()->clientid, "opened" => 0, "delete_remove" => 0, "disc" => 0]]);
					 echo $q1->count();
                     ?></span></a></li>
                <li><a href="compose.php"><?php echo $lang['compose']; ?></a></li>
              </ul>
            </li>
          
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>