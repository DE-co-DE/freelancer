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


?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">
    
    <!-- Include header.php. Contains header content. -->
    <?php include ('template/header.php'); ?> 
    <!-- Panel CSS -->
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
          <h1><?php echo $lang['dashboard']; ?><small><?php echo $lang['control_panel']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['dashboard']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?> 
       	
          <!-- Info boxes -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-align-left"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['jobs']; ?> <?php echo $lang['posted']; ?></span>
                  <span class="info-box-number small">
                    <?php	
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => 0]]);
                     echo $query->count();
                    ?>	
			     </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-mint"><i class="fa fa-filter"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['jobs']; ?> <?php echo $lang['invites']; ?></span>
                  <span class="info-box-number">
                    <?php	
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => 1]]);
                     echo $query->count();
                    ?>	
                    </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-olive"><i class="fa fa-align-right"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['jobs']; ?> <?php echo $lang['completed']; ?></span>
                  <span class="info-box-number">
                    <?php	
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["completed" => 1]]);
                     echo $query->count();
                    ?>	
                    </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-mint"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['job']; ?> <?php echo $lang['payments']; ?></span>
                  <span class="info-box-number">
                        <?php	
                        echo $currency_symbol.'&nbsp;'; 
                        $query = DB::getInstance()->sum("transactions", "payment", ["transaction_type" => 4]);
						foreach($query->results()[0] as $row) {
							echo $row;
						}	
                        ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
          </div><!-- /.row -->  
          
          <!-- Info boxes -->
          <div class="row">
          	
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-mint"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['membership']; ?><br /> <?php echo $lang['payments']; ?></span>
                  <span class="info-box-number">
                        <?php	
                        echo $currency_symbol.'&nbsp;'; 
                        $query = DB::getInstance()->sum("transactions", "payment", ["AND" => ["transaction_type" => 1, "complete" => 1]]);
						foreach($query->results()[0] as $row) {
							echo $row;
						}	
                        ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-align-left"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['featured']; ?> 
                  	                          <?php echo $lang['payments']; ?></span>
                  <span class="info-box-number small">
                    <?php	
                        echo $currency_symbol.'&nbsp;'; 
                        $query = DB::getInstance()->sum("transactions", "payment", [
																				   "OR" => [
																						"AND #first" => [
																							"transaction_type" => 2,
																							"complete" => 1
																						],
																						"AND #second" => [
																							"transaction_type" => 3,
																							"complete" => 1
																						]
																					]]);
						foreach($query->results()[0] as $row) {
							echo $row;
						}	
                    ?>	
			     </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-mint"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['clients']; ?></span>
                  <span class="info-box-number">
                    <?php	
                     $query = DB::getInstance()->get("client", "*", []);
                     echo $query->count();
                    ?>	
                    </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-olive"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?php echo $lang['freelancers']; ?></span>
                  <span class="info-box-number">
                    <?php	
                     $query = DB::getInstance()->get("freelancer", "*", []);
                     echo $query->count();
                    ?>	
                    </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

          </div><!-- /.row --> 
          
         <div class="row">
          <div class="col-lg-8">
          	<section class="panel panel-default">
             <header class="panel-heading font-bold"><?php echo $lang['jobs']; ?> <?php echo $lang['awarded']; ?> </header>
             <div class="panel-body">


                 <div class="table-responsive">
                  <table  class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => "0", "delete_remove" => 0, "accepted" => 1], "LIMIT" => 4, "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {	
						
					    echo '<tr>';
					    echo '<td><a href="jobboard.php?a=overview&id='. escape($row->jobid) .'">'. escape($row->title) .'</a></td>';
					    if($row->completed === '1'):
						echo '<td><span class="label label-success"> ' . $lang['job'] . ' ' . $lang['completed'] . ' </span></td>';
						else:
						echo '<td><span class="label label-success"> ' . $lang['on'] . ' ' . $lang['progress'] . ' </span></td>';
						endif;		
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '</tr>';
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->             	
             	
             </div> 
             <footer class="panel-footer bg-white no-padder">
              <div class="row text-center no-gutter">
               <div class="col-xs-3 b-r b-light">
				<span class="h4 font-bold m-t block">
                <?php	
                 $query = DB::getInstance()->get("job", "*", ["AND" => ["completed" => 1]]);
                 echo $query->count();
                ?>	
				</span> 
				<small class="text-muted m-b block"><?php echo $lang['completed']; ?> <?php echo $lang['jobs']; ?></small>
               </div>
               <div class="col-xs-3 b-r b-light">
				<span class="h4 font-bold m-t block">
                <?php 	
                 $query = DB::getInstance()->get("job", "*", ["AND" => ["invite" => 0]]);
                 echo $query->count();
                 ?>
                </span> <small class="text-muted m-b block"><?php echo $lang['jobs']; ?> <?php echo $lang['posted']; ?></small>
               </div>
               <div class="col-xs-3 b-r b-light"> 
               	<span class="h4 font-bold m-t block">
                <?php 	
                 $q1 = DB::getInstance()->get("proposal", "*", ["AND" => ["delete_remove" => 0]]);
				 echo $q1->count();
                 ?>
				</span> <small class="text-muted m-b block"><?php echo $lang['proposals']; ?></small>
               </div>
               <div class="col-xs-3">
			    <span class="h4 font-bold m-t block">
                <?php	
                 $query = DB::getInstance()->get("transactions", "*", ["AND" => ["transaction_type" => 4, "complete" => 1]]);
                 echo $query->count();
                ?>	
				</span> <small class="text-muted m-b block"><?php echo $lang['jobs']; ?> <?php echo $lang['payments']; ?> </small>
               </div>
              </div> 
             </footer>
            </section>
          </div><!-- /.col-lg-8 -->	
          
          <div class="col-lg-4"> 
           <section class="panel panel-default">
            <header class="panel-heading"><?php echo $lang['company']; ?> 
            	                          <?php echo $lang['earnings']; ?></header>
            <div class="panel-body text-center"> 
            <?php
			$query = DB::getInstance()->get("payments_settings", "*", ["AND" =>["id" => "1"]]);
			if ($query->count()) {
			 foreach($query->results() as $row) {
			   $jobs_percentage = $row->jobs_percentage;
			 }
			}
            
            $query = DB::getInstance()->sum("transactions", "payment", ["transaction_type" => 4]);
			foreach($query->results()[0] as $pay) {
				 $pay;
			}	
			
            $query = DB::getInstance()->sum("transactions", "payment", ["transaction_type" => 1]);
			foreach($query->results()[0] as $mem) {
				$mem;
			}
			
            $query = DB::getInstance()->sum("transactions", "payment", [
																	   "OR" => [
																			"AND #first" => [
																				"transaction_type" => 2,
																				"complete" => 1
																			],
																			"AND #second" => [
																				"transaction_type" => 3,
																				"complete" => 1
																			]
																		]]);
			foreach($query->results()[0] as $fea) {
				$fea;
			}
			
			$earnings = $jobs_percentage/100 * $pay;
			$earnings = round($earnings, 1);
			
			$total_pa = $fea + $mem + $earnings + $pay;
			$total = $fea + $mem + $earnings;
			
			$percentage = $total/$total_pa * 100;
			$percentage = round($percentage, 1);
            ?>
             <h4><small><?php echo $lang['job']; ?>  <?php echo $lang['payments']; ?>: </small><?php echo $currency_symbol; ?> <?php echo $pay; ?></h4>
             <h4><small><?php echo $lang['company']; ?>  
             	        <?php echo $lang['earnings']; ?>
             	        <?php echo $lang['on']; ?>
             	        <?php echo $lang['job']; ?>  
             	        <?php echo $lang['payments']; ?>: </small><?php echo $currency_symbol; ?> <?php echo $earnings; ?></h4>
             <h4><small><?php echo $lang['membership']; ?>  
             	        <?php echo $lang['payments']; ?>: </small><?php echo $currency_symbol; ?> <?php echo $mem; ?></h4>
             <h4><small><?php echo $lang['featured']; ?>  
             	        <?php echo $lang['payments']; ?>: </small><?php echo $currency_symbol; ?> <?php echo $fea; ?></h4>
             <div class="inline">
              <input class="knob knob-front" data-angleOffset="90" data-linecap="round" value="<?php echo $percentage; ?>" style=""/>

             </div>
            </div>
            <div class="panel-footer">
             <small><?php echo $lang['total']; ?> <?php echo $lang['company']; ?> <?php echo $lang['earnings']; ?>:
              <strong><?php echo $currency_symbol; ?> <?php echo $total; ?></strong>
             </small>
            </div> 
            </section>
           </div>
                          
         </div><!-- /.row -->   
         
         <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $lang['monthly']; ?> <?php echo $lang['job']; ?> <?php echo $lang['payments']; ?></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <p class="text-center">
                        <strong><?php echo $lang['finance']; ?> : January, <?php year_now(); ?> - December, <?php year_now(); ?></strong>
                      </p>
                      <div class="chart">
                      	
					 <?php
                        $dbc = mysqli_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password'), Config::get('mysql/db')) OR die('Could not connect because:' .mysqli_connect_error());
						
					 	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
						foreach ($months as $month) {		
					    $q = "SELECT SUM(payment) AS value_sum FROM transactions WHERE transaction_type = 4 AND complete = 1 AND DATE_FORMAT(date_added,'%M') = '$month'";
						$r = mysqli_query($dbc, $q);
					    $row = mysqli_fetch_assoc($r); 
					    $sum_v = $row['value_sum'];
					
						
						if ($sum_v != 0) {
						 $monthvalues[] = $sum_v;
						}else {
						 $monthvalues[] = 0;
						}
						
						}
						
					 
					 ?>                      	
                        <!-- Sales Chart Canvas -->
                        <canvas id="salesChart" height="400"></canvas>
                      </div><!-- /.chart-responsive -->
                    </div><!-- /.col -->

                  </div><!-- /.row -->
                </div><!-- ./box-body -->
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6 col-xs-6">
                      <div class="description-block border-right">
                        <h5 class="description-header"> <?php echo $currency_symbol; ?> <?php echo $pay; ?></h5>
                        <span class="description-text"><?php echo $lang['total']; ?> <?php echo $lang['paid']; ?> <?php echo $lang['amount']; ?></span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-6 col-xs-6">
                      <div class="description-block">
                        <h5 class="description-header">
                        	<?php echo $currency_symbol; ?> <?php echo $total; ?>
                        </h5>
                        <span class="description-text"><?php echo $lang['total']; ?>
                        	                           <?php echo $lang['company']; ?>
                        	                           <?php echo $lang['earnings']; ?></span>
                      </div><!-- /.description-block -->
                    </div>
                  </div><!-- /.row -->
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
         	
         </div>               	           

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	 	
	  <?php include 'template/footer.php'; ?>	
	 	
     </div>
     
     <!-- ==============================================
	 Scripts
	 =============================================== -->
	 
    <!-- jQuery 2.1.4 -->
    <script src="../assets/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.6 JS -->
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>

    <script src="../assets/js/jquery.knob.min.js"></script>
    <script>
        $(function($) {

            $(".knob").knob({
                change : function (value) {
                    //console.log("change : " + value);
                },
                release : function (value) {
                    //console.log(this.$.attr('value'));
                    console.log("release : " + value);
                },
                cancel : function () {
                    console.log("cancel : ", this);
                },
                format : function (value) {
                    return value + '%';
                },
                draw : function () {

                    // "tron" case
                    if(this.$.data('skin') == 'tron') {

                        this.cursorExt = 0.3;

                        var a = this.arc(this.cv)  // Arc
                            , pa                   // Previous arc
                            , r = 1;

                        this.g.lineWidth = this.lineWidth;

                        if (this.o.displayPrevious) {
                            pa = this.arc(this.v);
                            this.g.beginPath();
                            this.g.strokeStyle = this.pColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, pa.s, pa.e, pa.d);
                            this.g.stroke();
                        }

                        this.g.beginPath();
                        this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, a.s, a.e, a.d);
                        this.g.stroke();

                        this.g.lineWidth = 2;
                        this.g.beginPath();
                        this.g.strokeStyle = this.o.fgColor;
                        this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                        this.g.stroke();

                        return false;
                    }
                }
            });

            // Example of infinite knob, iPod click wheel
            var v, up=0,down=0,i=0
                ,$idir = $("div.idir")
                ,$ival = $("div.ival")
                ,incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); }
                ,decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
            $("input.infinite").knob(
                                {
                                min : 0
                                , max : 20
                                , stopper : false
                                , change : function () {
                                                if(v > this.cv){
                                                    if(up){
                                                        decr();
                                                        up=0;
                                                    }else{up=1;down=0;}
                                                } else {
                                                    if(v < this.cv){
                                                        if(down){
                                                            incr();
                                                            down=0;
                                                        }else{down=1;up=0;}
                                                    }
                                                }
                                                v = this.cv;
                                            }
                                });
        });
    </script>    

    <!-- ChartJS 1.0.1 -->
    <script src="../assets/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
    <script type="text/javascript">
	 $(function () {
  //-----------------------
  //- MONTHLY SALES CHART -
  //-----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
  // This will get the first returned node in the jQuery collection.
  var salesChart = new Chart(salesChartCanvas);

  var salesChartData = {
    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    datasets: [
      {
        label: "Electronics",
        fillColor: "rgb(255, 99, 132)",
		lineTension: 0.1,
        strokeColor: "rgb(255, 99, 132)",
        pointColor: "rgb(255, 99, 132)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgb(220,220,220)",
        data : <?=json_encode(array_values($monthvalues));?>
      },
      {
        label: "Digital Goods",
        fillColor: "rgba(60,141,188,0.9)",
        strokeColor: "rgba(60,141,188,0.8)",
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: <?=json_encode(array_values($monthvalue));?>
      }
    ]
  };

  var salesChartOptions = {
    //Boolean - If we should show the scale at all
    showScale: true,
    scaleLabel:
    function(label){return  ' $ ' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");},
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines: false,
    //String - Colour of the grid lines
    scaleGridLineColor: "rgba(0,0,0,.05)",
    //Number - Width of the grid lines
    scaleGridLineWidth: 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,
    //Boolean - Whether the line is curved between points
    bezierCurve: true,
    //Number - Tension of the bezier curve between points
    bezierCurveTension: 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot: true,
    //Number - Radius of each point dot in pixels
    pointDotRadius: 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth: 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius: 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke: true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth: 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill: true,
    //String - A legend template
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true
  };

  //Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);		
		
	 });

    </script>

</body>
</html>