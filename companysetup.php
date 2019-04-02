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
	  
	  $query = "SELECT `taxrate`, `numtables` FROM `company` WHERE companyID='$companyID'";
	  $query = $conn->query($query);
	  while ($row = $query->fetch_assoc()){
		  $gstValue = $row["taxrate"];
		  $numTable = $row["numtables"];
	  }
	 
	  $conn->close();
  }
?>

<!DOCTYPE html>
<html>
	<head>
		<script charset="utf-8" src="js/companySetup.js"></script>
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Company Settings</h1>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					General Setup
				</div>
				<div class="panel-body">
					<!--SETUP GST-->
					<div class="row">
						<div class="col-sm-3">		
							<label for="taxrate">GST Rate (%): </label>
						</div>
						<div class="col-sm-3">
							<input type="number" step="0.01" id="taxrate" name="taxrate" min="0" max="50" value="<?php echo $gstValue;?>">
						</div>

						<div class="col-sm-6">		
							<button type="button" id="gst" class="btn btn-success pull-right">Update GST</button>
						</div>
					</div>
					<!--END SETUP GST-->
					<br/>

					<!--SETUP NEW WAITER-->
					<div class="row">
						<div class="col-sm-3">		
							<label for="newserver">New Waiter: </label>
						</div>
						<div class="col-sm-3">		
							<input type="text" id="newserver" name="newserver" minlength="3" maxlength="45" placeholder="Please enter server name">
						</div>

						<div class="col-sm-6">
							<button type="button" id="newwaiter" class="btn btn-default pull-right">Create Waiter</button>
						</div>
					</div>
					<!--SETUP NEW WAITER-->
					<br/>

					<!--SETUP REMOVE WAITER-->
					<div class="row">
						<div class="col-sm-3">
							<label for="removeserver">Remove Waiter: </label>
						</div>
						<div class="col-sm-3">
							<select id="removeserver" name="removeserver">
								<?php
									include "config.php";
									$query = "SELECT * FROM server WHERE company_companyID='$companyID'";
									$query = $conn->query($query);
									if ($query->num_rows === 0) {
										echo "<option value='0'>--No Waiters--</option>";
									}
									else {
										echo "<option value='0'>--Select Waiter to Delete--</option>";
										while ($row = $query->fetch_assoc()) {
											$serID = $row["serverID"];
											$sername = $row["servername"];
											echo "<option value='$serID'>$sername</option>";
										}
									}
									$conn->close();
								?>
							</select>
						</div>

						<div class="col-sm-6">
							<button type="button" id="delwaiter" class="btn btn-danger pull-right">Delete Waiter</button>
						</div>
					</div>
					<!--SETUP REMOVE WAITER-->
					<br/>

					<!--SETUP TABLES-->
					<div class="row">
						<div class="col-sm-3">		
							<label for="numtable"># of Tables: </label>
						</div>
						<div class="col-sm-3">
							<input type="number" id="numtable" name="numtable" min="0" max="50" value="<?php echo $numTable;?>">
						</div>

						<div class="col-sm-6">		
							<button type="button" id="updateNumTable" class="btn btn-success pull-right">Update Count</button>
						</div>
					</div><!--END SETUP TABLES-->
				</div><!--END GENERAL SETTINGS PANEL BODY-->
			</div><!--END GENERAL SETTINGS PANEL-->
			
			<div class="panel panel-default">
				<div class="panel-heading">
					Customer Settings
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<form id="newCustomer" action="dataCompanySetup.php" method="post">
								<div class="row">
									<div class="col-sm-4">
										<label for="customerName">New Customer Name: </label>
									</div>
									<div class="col-sm-8">
										<input type="text" id="customerName" name="customerName" minlength="5" maxlength="50" required />
									</div>
								</div>
								<br/>
								<div class="row">
									<div class="col-sm-4">
										<label for="customerAddress">New Customer Address: </label>
									</div>
									<div class="col-sm-8">
										<input type="text" id="customerAddress" name="customerAddress" minlength="5" maxlength="100" required />
									</div>
								</div>
								<br/>
								<div class="row">
									<div class="col-sm-4">
										<label for="customerPhone">New Customer Contact #: </label>
									</div>
									<div class="col-sm-8">
										<input type="text" id="customerPhone" name="customerPhone" minlength="11" maxlength="16" placeholder="+92-xx-xxxx-xxxx" required />
									</div>
								</div>
								<br/>
								<div class="row">
									<div class="col-sm-12">
										<input type="submit" id="submitCustomer" name="submitCustomer" value="Create Customer">
									</div>
								</div>
							</form>
						</div>
						
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-4">
									<label for="deletecustomer">Select Customer: </label>
								</div>
								<div class="col-sm-8">
									<select id="deletecustomer" name="deletecustomer">
									<?php
										include "config.php";
										$query = "SELECT * FROM customer WHERE company_companyID='$companyID'";
										$query = $conn->query($query);
										if ($query->num_rows === 0) {
											echo "<option value='0'>--No Customers--</option>";
										}
										else {
											echo "<option value='0'>--Select Customer--</option>";
											while ($row = $query->fetch_assoc()) {
												$custID = $row["custID"];
												$custname = $row["custname"];
												echo "<option value='$custID'>$custname</option>";
											}
										}
										$conn->close();
									?>
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="pull-right">
										<button class="btn btn-info" id="viewCustomer">View Record</button>
										<button class="btn btn-danger" id="delCustomer">Delete Record</button>
									</div>
								</div>
							</div>
							<br/>
							
							<div class="row">
								<div class="col-sm-4">
									<p><strong>Name: </strong></p>
								</div>
								<div class="col-sm-8">
									<p id="displayCustName">No selection</p>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-4">
									<p><strong>Address: </strong></p>
								</div>
								<div class="col-sm-8">
									<p id="displayCustAddress">No selection</p>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-4">
									<p><strong>Contact: </strong></p>
								</div>
								<div class="col-sm-8">
									<p id="displayCustContact">No selection</p>
								</div>
							</div>
						</div>
					</div><!--END CUSTOMER SETTINGS ROW-->
				</div><!--END CUSTOMER SETTINGS PANEL BODY-->
			</div><!--END CUSTOMER SETTINGS PANEL-->
		</div>
	</body>
</html>