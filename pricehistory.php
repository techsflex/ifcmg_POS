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
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Product Price History</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/priceHistory.js"></script>
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Product Price History</h1>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					Product Search
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-3">		
							<label for="searchProdCat">Product Category: </label>
						</div>
						<div class="col-sm-9">
							<select id="searchProdCat" name="searchProdCat">
								<?php
									include "config.php";
									$query = "SELECT * FROM `cat` WHERE `company_companyID`='$companyID'";
									$query = $conn->query($query);
									if ($query->num_rows === 0) {
										echo "<option value='na'>--No Categories Defined--</option>";
									}
									else {
										echo "<option value='na'>--Select Category--</option>";
										while ($row = $query->fetch_assoc()) {
											$catID = $row["catID"];
											$catname = $row["catname"];
											echo "<option value='$catID'>$catname</option>";
										}
									}
									$conn->close();
								?>
							</select>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-3">		
							<label for="searchProdName">Product Name: </label>
						</div>
						<div class="col-sm-9">
							<select id="searchProdName" name="searchProdName" disabled="true">
								<option value='na'>--Select Product Name--</option>;
							</select>
						</div>
					</div>
					
					<div class="row">
						<button type="button" id="searchProdHist" class="btn btn-success pull-right">Search</button>
					</div>
				</div>
			</div><!--END DEFAULT PANEL FOR PRODUCT SEARCH-->
			
			<div class="panel panel-default">
				<div class="panel-heading">
					Product Search Results
				</div>
				<div class="panel-body">
					<div class="prodHist">
						<table class="table table-responsive table-striped">
							<thead>
								<tr>
									<th>Date Modified</th>
									<th>Modified By</th>
									<th>Previous Price</th>
								</tr>
							</thead>
							<tbody class="priceHistory text-center" id="priceHistory">
								<tr>
									<td colspan="3">No records found!</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div><!--END DEFAULT PANEL FOR PRODUCT SEARCH-->
			
			
		</div> <!--END CONTAINER FLUID-->
	</body>
</html>