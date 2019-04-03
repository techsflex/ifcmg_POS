<?php
  /* Displays user information and some useful messages */
  session_start();
  // Check if user is logged in using the session variable
  if (!$_SESSION['logged_in']) {
      $_SESSION['message'] = "You must log in before viewing your profile page!";
      header("location: error.php");    
  }

  else {
  	include "config.php";
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name  = $_SESSION['last_name'];
    $statusID   = (int)$_SESSION['statusID'];
    $companyID  = (int)$_SESSION['companyID'];

    $query = "SELECT posversion_posID FROM company WHERE companyID='$companyID'";	

    if ($result = $conn->query($query)) {
    	$row = $result->fetch_array();
    	$posversion = (int)$row[0];
    }
    else {
    	$posversion = 1;
    }

    $query = "SELECT poscaption FROM posversion WHERE posID='$posversion'";
    $result = $conn->query($query);
    $row = $result->fetch_array();
    $pos = $row[0];
    $conn->close();
  }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>IFCMG - Dashboard</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	
    <script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	
	<script src="js/custom.js"></script>
	<script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<script>
		$(document).ready(function(){
			$("#tillOperation").on('click', function(e){
				e.preventDefault();
				//alert(typeof this.value);
				var message = null;
				var amount = null;
				
				if (this.value === "5") {
					message = "Please enter cash in till at Day Opening";
					amount = parseInt(prompt(message));
				}
				
				else if (this.value === "6") {
					message = "Please enter cash in till at Day Closing";
					amount = parseInt(prompt(message));
				}
				
				else {
					alert("Invalid value encountered for Day Opening/Day Closing Button!");
				}
				
				//alert(amount);
				
				if (amount == null || amount == "" || isNaN(amount)) {
					alert("Invalid value entered or request was cancelled");
				}
				
				else {
					//alert("Initializing AJAX Query");
					$.ajax({
						url: "tillAmount.php",
						type: "POST",
						data: {
								tillProcess: this.value,
								tillBalance: amount,
							  }//,
						//success: function(queryMessage){
							//alert(queryMessage);
						//}
					}).done(function(queryMessage){
						alert(queryMessage);
						location.reload(true);
					}).fail(function(queryMessage){
						alert("Call to server failed!");
					});
					//alert("AJAX Query Complete");
				}
				//location.reload(true);
			});
		});
	</script>
</head>

<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>POS</span>APP</a>
				<a class="navbar-right" href="#"><?php echo $pos; ?>&nbsp;&nbsp;</a>
			</div>
		</div><!-- /.container-fluid -->
	</nav>

	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name"><?php echo $first_name . ' ' . $last_name ?></div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>

		<ul class="nav menu">
			<li class="active" id="dashboard"><a href="home.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
			<li onClick="loadPage(this)"  id="new_order" value="2" ><a ><em class="fa fa-plus-square">&nbsp;</em> New Order</a></li>
			<li onClick="loadPage(this)"  id="add_products" value="1"  ><a ><em class="fa fa-bars">&nbsp;</em> All Products</a></li>
			<li onClick="loadPage(this)"  id="order_log" value="3" ><a ><em class="fa fa-file">&nbsp;</em> Order Log</a></li>
			<li onClick="loadPage(this)"  id="hold_order_log" value="4" ><a ><em class="fa fa-clock-o">&nbsp;</em> Hold Order Log</a></li>
			<li onClick="loadPage(this)"  id="kitchen_status" value="7" ><a ><em class="fa fa-birthday-cake">&nbsp;</em> Kitchen Status</a></li>
			<?php
				if ($statusID === 4) {
					echo '<li onClick="loadPage(this)"  id="company_setup" value="8" ><a ><em class="fa fa-cog">&nbsp;</em> Company Settings</a></li>';
				}
			?>
			
			<br><br>
			
			<?php
				include "config.php";
				$query = "SELECT COUNT(*) FROM `tillamount` WHERE company_companyID='$companyID'"; #checks count of rows in table
				$result = $conn->query($query);
				$row = $result->fetch_array();
				if ((int)$row[0] === 0) {
					echo '<li><button type="button" class="btn btn-block btn-success" id="tillOperation" value="5">Day Open</button></li>';
				}
					
				else {
					$query = "SELECT * FROM `tillamount` WHERE company_companyID='$companyID' ORDER BY `tillID` DESC LIMIT 1";
					$result = $conn->query($query);
					$row = $result->fetch_array();
					if (is_null($row['tillclosecash'])) {
						echo '<li><button type="button" class="btn btn-block btn-warning" id="tillOperation" value="6">Day Close</button></li>';
					}
					else {
						echo '<li><button type="button" class="btn btn-block btn-success" id="tillOperation" value="5">Day Open</button></li>';
					}
				}
				$conn->close();
			?>
			
			<br>
			<br>
			<!--<li>
				<a class="text-center signout" style="background: rgba(255,0,0, 0.6);" href="logout.php">Logout</a>
			</li>-->
		</ul>
		<div style="text-align: center;">
			<a class="btn btn-danger signout" href="logout.php">Logout</a>
		</div>
	</div><!--/.sidebar-->
		
		
	
			<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main" >
		<div class="col-lg-12" id="main_content">
				<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Line Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Bar Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="bar-chart" height="200" width="600"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->		
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						Pie Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="chart" id="pie-chart" ></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						Doughnut Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="chart" id="doughnut-chart" ></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						Radar Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="chart" id="radar-chart" ></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						Polar Area Chart
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
					</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="chart" id="polar-area-chart" ></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<p class="back-link">Designed and developed by <a href="https://www.techsflex.net">Techsflex</a></p>
			</div>
		</div><!--/.row-->
				</div>
			 
		</div>		

<script>

    function loadPage(el){
		//alert(el.value);
		
	   $(el).siblings('li').removeClass('active');
       $(el).addClass('active');
    
		if(el.value==1){
			//Add Products
			
			 $("#main_content").load("addProducts.php");
		}
		else if(el.value==2){
			 $("#main_content").load("newOrder.php");
			
			
		}
		else if(el.value==3){
			 $("#main_content").load("viewOrderLog.php");
			
		}
		
		else if(el.value==4){
			 $("#main_content").load("viewHoldOrderLog.php");
			
		}
		
		else if(el.value==7){
			$("#main_content").load("kitchenstatus.php");
		}
		
		else if(el.value==8){
			$("#main_content").load("companysetup.php");
		}
		
	}
	
	window.addEventListener('load', function() {
	//$("#main_content").load("dashboard.html");
	var chart1 = document.getElementById("line-chart").getContext("2d");
	window.myLine = new Chart(chart1).Line(lineChartData, {
	responsive: true,
	scaleLineColor: "rgba(0,0,0,.2)",
	scaleGridLineColor: "rgba(0,0,0,.05)",
	scaleFontColor: "#c5c7cc"
	});
	var chart2 = document.getElementById("bar-chart").getContext("2d");
	window.myBar = new Chart(chart2).Bar(barChartData, {
	responsive: true,
	scaleLineColor: "rgba(0,0,0,.2)",
	scaleGridLineColor: "rgba(0,0,0,.05)",
	scaleFontColor: "#c5c7cc"
	});
	var chart3 = document.getElementById("doughnut-chart").getContext("2d");
	window.myDoughnut = new Chart(chart3).Doughnut(doughnutData, {
	responsive: true,
	segmentShowStroke: false
	});
	var chart4 = document.getElementById("pie-chart").getContext("2d");
	window.myPie = new Chart(chart4).Pie(pieData, {
	responsive: true,
	segmentShowStroke: false
	});
	var chart5 = document.getElementById("radar-chart").getContext("2d");
	window.myRadarChart = new Chart(chart5).Radar(radarData, {
	responsive: true,
	scaleLineColor: "rgba(0,0,0,.05)",
	angleLineColor: "rgba(0,0,0,.2)"
	});
	var chart6 = document.getElementById("polar-area-chart").getContext("2d");
	window.myPolarAreaChart = new Chart(chart6).PolarArea(polarData, {
	responsive: true,
	scaleLineColor: "rgba(0,0,0,.2)",
	segmentShowStroke: false
	});
		
	})
</script>
	
		
</body>
</html>