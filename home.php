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
			$("#tillOperation").click(function(){
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
								tillBalance: amount
							  },
						success: function(queryMessage){
							alert(queryMessage);
						}
					});
					//alert("AJAX Query Complete");
				}
				location.reload(true);
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
				<a class="navbar-right" href="#">Full Version&nbsp;&nbsp;</a>
			</div>
		</div><!-- /.container-fluid -->
	</nav>

	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name">M. Ghazi</div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>
		<ul class="nav menu">
			<li class="active" id="dashboard"><a href="index.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
			<li onClick="loadPage(this)"  id="new_order" value="2" ><a ><em class="fa fa-plus-square">&nbsp;</em> New Order</a></li>
			<li onClick="loadPage(this)"  id="add_products" value="1"  ><a ><em class="fa fa-bars">&nbsp;</em> All Products</a></li>

			<li onClick="loadPage(this)"  id="order_log" value="3" ><a ><em class="fa fa-file">&nbsp;</em> Order Log</a></li>
			<li onClick="loadPage(this)"  id="hold_order_log" value="4" ><a ><em class="fa fa-clock-o">&nbsp;</em> Hold Order Log</a></li>
			<br><br>
			
			<?php
				include "config.php";
				$query = "SELECT COUNT(*) FROM `tillamount`"; #checks count of rows in table
				$result = $conn->query($query);
				$row = $result->fetch_array();
				if ((int)$row[0] === 0) {
					echo '<li><button type="button" class="btn btn-block btn-success" id="tillOperation" value="5">Day Open</button></li>';
				}
					
				else {
					$query = "SELECT * FROM `tillamount` ORDER BY `tillID` DESC LIMIT 1";
					$result = $conn->query($query);
					$row = $result->fetch_array();
					if (is_null($row['till_close_cash'])) {
						echo '<li><button type="button" class="btn btn-block btn-warning" id="tillOperation" value="6">Day Close</button></li>';
					}
					else {
						echo '<li><button type="button" class="btn btn-block btn-success" id="tillOperation" value="5">Day Open</button></li>';
					}
				}
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
		
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Dashboard</li>
			</ol>
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
				<p class="back-link">A Product of <a href="https://www.techsflex.net">Techsflex</a></p>
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