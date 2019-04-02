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
$holdOrderId = $_POST["holdOrderId"];
//$breakdownString = JSON.stringify($breakdown);

$orderStatus = $_POST['status'];
$queryStringHoldOrder="N/A";

if(isset( $subTotal )){
	// ORDER STATUS = 1 MEANS PROCEED WITH ORDER - CONSIDER FINAL
	if($orderStatus == 1){
		$queryString = "INSERT INTO `orders`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `paymentID`, `ordertype`, `company_companyID`) VALUES ('$subTotal','$afterTax','$discountAmount','$grandTotal','$breakdown','$paymentType','$orderType','$companyID')"; 
		
		if (mysqli_query($conn, $queryString)) {
			$message = "Insert into Orders Table: SUCCESSFUL  ";
		}
		else {
			$message = "Insert into Orders Table: FAILED.  ";
		}
		
		if ($holdOrderId!=-1){
			//If Proceed with order and order was previously on hold, remove from hold orders
			$queryStringHoldOrder = "DELETE FROM `held` WHERE `heldID`='$holdOrderId' AND company_companyID='$companyID'";
			
			if (mysqli_query($conn, $queryStringHoldOrder)) {					 
				$message .= "AND Delete from Hold Orders Table: SUCCESSFUL";
			}
			else {
				$message .= "AND Delete from Hold Orders Table: FAILED";
			}
		} //End if previously held order condition
	} //End Proceed with order condition
	
	// ORDER STATUS = 2 MEANS ORDER WILL BE UPDATED LATER
	elseif ($orderStatus == 2){
		if($holdOrderId!=-1){		   
			$queryString = "UPDATE `held` SET `subtotal`='$subTotal',`taxpaid`='$afterTax',`discount`='$discountAmount',`grandtotal`='$grandTotal',`breakdown`='$breakdown'`ordertype`='$orderType' WHERE `heldID`='$holdOrderId' AND company_companyID='$companyID'"; 	
			
			if (mysqli_query($conn, $queryString)) {					 
				$message = "Update Hold Orders Table: SUCCESSFUL";
			}
			else {
				$message = " Update Hold Orders Table: FAILED";
			}
		}
		else {
			$queryString = "INSERT INTO `held`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `ordertype`, `company_companyID`) VALUES ('$subTotal','$afterTax','$discountAmount','$grandTotal','$breakdown','$orderType', '$companyID')"; 
			
			if (mysqli_query($conn, $queryString)) {					 
				$message= "New entry for Hold Orders: SUCCESSFUL.  ";
			}
			else {
				$message= "New entry for Hold Orders: FAILED.  ";
			}
		}
	}
	
	$data = array(
		"message"=> $message,
	);
}
$conn->close();
//echo json_encode("AND THE STATUS RECEIVED IS ".$orderStatus);
echo json_encode($data);

?>