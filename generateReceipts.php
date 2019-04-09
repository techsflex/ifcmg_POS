<?php

include('config.php');
session_start();

date_default_timezone_set('Asia/Karachi');

$companyID = (int)$_SESSION['companyID'];

$subTotal = (float)$_POST["subTotal"];
$afterTax = (float)$_POST["afterTax"];
$discountAmount = (float)$_POST["discountAmount"];
$grandTotal = (float)$_POST["grandTotal"];
$breakdown = ($_POST["breakdown"]);
$paymentType = $_POST["paymentType"];
$orderType = $_POST["orderType"];
$tableNum = (int)$_POST["tableNum"];
$serverName = $_POST["serverName"];
$custNum = (int)$_POST["custNum"];
$holdOrderId = $_POST["holdOrderId"];

//$breakdownString = JSON.stringify($breakdown);

if ($_POST["serverName"] === "--Select Waiter--" || $_POST["serverName"] === "--No Waiters--"){
	$serverName = "None";
}

$orderStatus = $_POST['status'];
$queryStringHoldOrder="N/A";

if(isset( $subTotal )){
	// ORDER STATUS = 1 MEANS PROCEED WITH ORDER - CONSIDER FINAL
	if($orderStatus == 1){
		if ($custNum == 0) {
			$queryString = "INSERT INTO `orders`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `paymentID`, `ordertype`, `tablenum`, `servername`, `company_companyID`) VALUES ('$subTotal', '$afterTax', '$discountAmount', '$grandTotal', '$breakdown', '$paymentType', '$orderType', '$tableNum', '$serverName', '$companyID')";
		}
		else {
			$queryString = "INSERT INTO `orders`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `paymentID`, `ordertype`, `tablenum`, `servername`, `company_companyID`, `customer_custID`) VALUES ('$subTotal', '$afterTax', '$discountAmount', '$grandTotal', '$breakdown', '$paymentType', '$orderType', '$tableNum', '$serverName', '$companyID', '$custNum')";
		}
		
		if (mysqli_query($conn, $queryString)) {
			$status = "success";
			$message = "Insert into Orders Table: SUCCESSFUL  ";
		}
		else {
			$status = "error";
			$message = "Insert into Orders Table: FAILED.  ";
		}
		
		if ($holdOrderId!=-1){
			//If Proceed with order and order was previously on hold, remove from hold orders
			$queryStringHoldOrder = "DELETE FROM `held` WHERE `heldID`='$holdOrderId' AND company_companyID='$companyID'";
			
			if (mysqli_query($conn, $queryStringHoldOrder)) {					 
				$status = "success";
				$message .= "AND Delete from Hold Orders Table: SUCCESSFUL";
			}
			else {
				$status = "error";
				$message .= "AND Delete from Hold Orders Table: FAILED";
			}
		} //End if previously held order condition
	} //End Proceed with order condition
	
	// ORDER STATUS = 2 MEANS ORDER WILL BE UPDATED LATER
	elseif ($orderStatus == 2){
		if($holdOrderId!=-1){
			if($custNum == 0){
				$queryString = "UPDATE `held` SET `subtotal`='$subTotal',`taxpaid`='$afterTax',`discount`='$discountAmount',`grandtotal`='$grandTotal',`breakdown`='$breakdown',`ordertype`='$orderType', `tablenum`='$tableNum', `servername`='$serverName' WHERE `heldID`='$holdOrderId' AND company_companyID='$companyID'";
			}
			else {
				$queryString = "UPDATE `held` SET `subtotal`='$subTotal',`taxpaid`='$afterTax',`discount`='$discountAmount',`grandtotal`='$grandTotal',`breakdown`='$breakdown',`ordertype`='$orderType', `tablenum`='$tableNum', `servername`='$serverName', `customer_custID`='$custNum' WHERE `heldID`='$holdOrderId' AND company_companyID='$companyID'";
			}
			
			if (mysqli_query($conn, $queryString)) {					 
				$status = "success";
				$message = "Update Hold Orders Table: SUCCESSFUL";
			}
			else {
				$status = "error";
				$message = " Update Hold Orders Table: FAILED";
			}
		}
		else {
			if ($custNum == 0){
				$queryString = "INSERT INTO `held`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `ordertype`, `tablenum`, `servername`, `company_companyID`) VALUES ('$subTotal','$afterTax','$discountAmount','$grandTotal','$breakdown','$orderType', '$tableNum', '$serverName', '$companyID')"; 
			}
			else {
				$queryString = "INSERT INTO `held`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `ordertype`, `tablenum`, `servername`, `company_companyID`, `customer_custID`) VALUES ('$subTotal','$afterTax','$discountAmount','$grandTotal','$breakdown','$orderType', '$tableNum', '$serverName', '$companyID', '$custNum')"; 
			}
			if (mysqli_query($conn, $queryString)) {					 
				$status = "success";
				$message= "New entry for Hold Orders: SUCCESSFUL.  ";
			}
			else {
				$status = "success";
				$message= "New entry for Hold Orders: FAILED.  ";
			}
		}
	}
	
	$data = array(
		"status" => $status,
		"message"=> $message,
	);
}
$conn->close();
//echo json_encode("AND THE STATUS RECEIVED IS ".$orderStatus);
echo json_encode($data);

?>