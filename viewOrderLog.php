<?php
  /* Displays user information and some useful messages */
  session_start();
  // Check if user is logged in using the session variable
  if (!$_SESSION['logged_in']) {
      $_SESSION['message'] = "You must log in before viewing your profile page!";
      header("location: error.php");    
  }

  else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name  = $_SESSION['last_name'];
    $statusID   = (int)$_SESSION['statusID'];
    $companyID  = (int)$_SESSION['companyID'];
  }
?>

<!doctype html>
<html lang="en" dir="ltr">
	<head>
		<title>jQuery SCRUD system</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=1000, initial-scale=1">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
		<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
		<script charset="utf-8" src="./js/webappOrderLog.js"></script>
		<link rel="stylesheet" href="layout.css">
	</head>
	
	<body>
		<div class="col-sm-12  col-lg-12   main">
			<div class="row">
				<ol class="breadcrumb">
					<li><a href="#">
						<em class="fa fa-home"></em>
						</a></li>
					<li class="active">Order Log</li>
				</ol>
			</div><!--/.row-->
			
			<div class="row">
				<h1 class="page-header">Order Log</h1>
			</div><!--/.row-->
			
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">	<!-- BEGIN LEFT COL --->
					<div class="panel panel-default articles"> 	<!-- BEGIN RECEIPTS --->
						<div class="panel-heading">	
							View all Orders
						</div>
						
						<div class="panel-body articles-container table-responsive ">
							<table class="datatable bootstrap-table table-bordered table-hover" id="table_products">
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Date / Time</th>
										<th>Payment Method</th>
										<th>Dine-In / Take-Away</th>
										<th>Total Value</th>
										<th>Functions</th>
									</tr>
								</thead>
								
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div> <!---END LEFT COL--->
			</div> <!--END ROW -->
			
			<div class="lightbox_container" >
				<div class="lightbox_close"></div>
				<div id="showData" class="lightbox_content">
				</div>
			</div>
			
			<noscript id="noscript_container">
				<div id="noscript" class="error">
					<p>JavaScript support is needed to use this page.</p>
				</div>
			</noscript>
			
			<div id="message_container">
				<div id="message" class="success">
					<p>This is a success message.</p>
				</div>
			</div>
			
			<div id="loading_container">
				<div id="loading_container2">
					<div id="loading_container3">
						<div id="loading_container4">
							Loading, please wait...
						</div>
					</div>
				</div>
			</div>
		</div> <!-- END MAIN DIV -->
		<?php
		print str_pad('',4096)."\n";
		ob_flush();
		flush();
		set_time_limit(45);
		?>
	</body>
</html>