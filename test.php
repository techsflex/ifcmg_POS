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
<html>
	<head>
		<meta charset="utf-8">
		<title>New Order</title>
		
		<link rel="stylesheet" href="./css/customStyle.css">
		
		<script src="js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<div class="container-fluid main">
			<row>
				<div class="col-lg-12">
					<h1 class="page-header">New Order</h1>
				</div>
			</row>
			
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							Receipt
						</div>
						<div class="panel-body table-responsive">
							<input type="checkbox" onClick"toggle(this)"> Select All
							
							<div class="orderType" id="orderType" style="float: right;">
								<?php
									if (isset($_GET['id'])) {
										include "config.php";
										$heldID = (int)$_GET['id'];
										$query = "SELECT `ordertype` FROM `held` WHERE heldID='$heldID' AND company_companyID='$companyID'";
										$query = $conn->query($query);
										while($row = $query->fetch_assoc()) {
											$orderType = $row['ordertype'];
										}

										if ($orderType == 'Dine-In') {
											echo "<input type='radio' name='orderType' id='dinein' value='Dine-In' checked='checked'> Dine-In&nbsp;&nbsp;";
											echo "<input type='radio' name='orderType' id='takeaway' value='Take-Away'> Take-Away";
										}
										else {
											echo "<input type='radio' name='orderType' id='dinein' value='Dine-In'> Dine-In&nbsp;&nbsp;";
											echo "<input type='radio' name='orderType' id='takeaway' value='Take-Away' checked='checked'> Take-Away";
										}
										$conn->close();
									}
									else {
										echo "<input type='radio' name='orderType' id='dinein' value='Dine-In' checked='checked'> Dine-In&nbsp;&nbsp;";
										echo "<input type='radio' name='orderType' id='takeaway' value='Take-Away'> Take-Away";
									}
								?>
							</div>
							<br />
							
							<table id="myTable" class="table table-bordered table-hover bootstrap-table">
								<thead>
									<tr style="background-color: teal; color: black;">
										<th>Select</th>
										<th>Product Code</th>
										<th>Product Name</th>
										<th>Product Price</th>
										<th>Quantity</th>
										<th>Total</th>
									</tr>
								</thead>
								
								<tbody>
									<?php
										if (isset($_GET['id'])) {
											include "config.php";
											$heldID = (int)$_GET['id'];
											$query = "SELECT `breakdown` FROM `held` WHERE heldID='$heldID' AND company_companyID='$companyID'";
											$query = $conn->query($query);
											
											while ($row = $query->fetch_assoc()) {
												$breakdown = $row['breakdown'];
											}
											
											$jsonArr = json_decode($breakdown, true);
											$row = 0;
											
											while ($rows < count($jsonArr)) {
												$productID 		= 	$jsonArr[$row]["productCode"];
												$productName 	= 	$jsonArr[$row]["productName"];
												$productPrice 	= 	$jsonArr[$row]["productPrice"];
												$productQty 	= 	$jsonArr[$row]["productQuantity"];
												$productTotal 	= 	$jsonArr[$row]["productTotal"];
												$productTextID 	= 	"TEMP".$productID;
												
												echo("<tr id='".$productID."'><td><input type='checkbox' name='record' ></td><td>".$productID."</td><td>".$productName."</td><td>".$productPrice."</td><td><input value='".$productQty."' type='number'  maxlength='10' class='qtyTextBox' style='width: 4em'  id='".$productTextID."'></td><td>".$productTotal."</td></tr>");
												$row++;
											}
											$conn->close();
										}
										else {
											$heldID = -1;
										}
										
										//Save in hidden field
										echo ("<input type='hidden' id='heldID' name='heldID' value='".$heldID."'>");
									?>
								</tbody>
							</table>
							
							<button type="button" onclick="deleteRow()" class="btn btn-default">Delete Item</button>
							
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">
							Amount (PKR)
						</div>
						
						<div class="panel-body">
							<table id="amountTable"  name="amountTable" width="100%" cellspacing="5" cellpadding="20" class="table table-bordered bootstrap-table">
								<tr>
									<td class="title" >Sub Total</td>
									<td class="amount" id="subTotal" name="subTotal">0.00</td>
								</tr>
								
								<tr>
									<td class="title">Discount (PKR)</td>
									<td class="amount" ><input id="discountAmount"  name="discountAmount" type="number"  value="0" style="width: 3em; text-align: right;" min="0"/></td>
								</tr>
								
								<tr>
									<td class="title">GST (<?php
										include "config.php";
										$query = "SELECT `taxrate` FROM `company` WHERE `companyID`='$companyID'";
										$query = $conn->query($query);
										while ($row = $query->fetch_assoc()) {
											$taxrate = $row['taxrate'];
										}
										echo $taxrate;
										$conn->close();
									?>%)</td>
									<td class="amount" id="afterTax" name="afterTax">0</td>
								</tr>
								
								<tr>
									<td class="title">Grand Total</td>
									<td class="amount" id="grandTotal" name="grandTotal">0.00</td>
								</tr>
							</table>
						</div>
						
						<div class="panel-footer">
							<div align="right">
								<button class="btn btn-success" data-toggle="modal" data-target="#paymentModal">Place Order</button>
								<button class="btn btn-primary" id="holdOrder">Hold Order</button>
								<button class="btn btn-danger" id="cancelOrder">Cancel Order</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
				
				</div>
			</div>
			
			<!--Payment Modal-->
			<div class="modal fade" id="paymentModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Basic Modal</h4>
							<button type="button" class="close" data-dismiss="modal"><span>x</span></button>
						</div>
					</div>
				</div>
			</div>
			<!--End Payment Modal-->
		</div>
		
		<script src="js/newOrder.js"></script>
	</body>
</html>