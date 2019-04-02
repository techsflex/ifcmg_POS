<?php

	function seoUrl($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}

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
	  $statusID   = (int)$_SESSION['statusID'];
	  $companyID  = (int)$_SESSION['companyID'];
	  $data = array();
	  
	  $oper = $_POST['oper'];
	  
	  if ($oper === "gst") {
		  $value = (float)$_POST['value'];
		  $query = "UPDATE `company` SET `taxrate` = '$value' WHERE companyID='$companyID'";
		  $query = $conn->query($query);
		  if (!$query){
			  $result = "error";
			  $message = "Unable to update GST, please try again!";
		  }
		  else {
			  $result = "success";
			  $message = "GST updated successfully";
		  }
	  }
	  
	  else if ($oper === "newwaiter") {
		  $value = $_POST['value'];
		  
		  $checkString = seoUrl($value);
		  $checkResult = True;
		  
		  $query = "SELECT * FROM `server` WHERE `company_companyID`='$companyID'";
		  $query = $conn->query($query);
		  
		  while ($row = $query->fetch_assoc()) {
			  $DBservername = seoUrl($row['servername']);
			  if ($DBservername === $checkString) {
				  $checkResult = False;
				  break;
			  }
			  else {
				  continue;
			  }
		  }
		  
		  if(!$checkResult){
			  $result = "error";
			  $message = "Unable to Add New Waiter as Waiter Name already exists";
		  }
		  else {
			  $query = "INSERT INTO `server` (`servername`, `company_companyID`) VALUES ('$value', '$companyID')";
			  $query = $conn->query($query);
			  if (!$query){
				  $result = "error";
				  $message = "Unable to add new waiter, please try again!";
			  }
			  else {
				  $result = "success";
				  $message = "New waiter added successfully";
			  }
		  }
	  }
	  
	  else if ($oper === "delwaiter") {
		  $value = (int)$_POST['value'];
		  $query = "DELETE FROM `server` WHERE serverID='$value' AND company_companyID='$companyID'";
		  $query = $conn->query($query);
		  if (!$query){
			  $result = "error";
			  $message = "Unable to remove waiter, please try again!";
		  }
		  else {
			  $result = "success";
			  $message = "Waiter removed successfully";
		  }
	  }
	  
	  else if ($oper === "updateNumTable") {
		  $value = (int)$_POST['value'];
		  $query = "UPDATE `company` SET `numtables` = '$value' WHERE companyID='$companyID'";
		  $query = $conn->query($query);
		  if (!$query){
			  $result = "error";
			  $message = "Unable to update Number of Tables, please try again!";
		  }
		  else {
			  $result = "success";
			  $message = "Number of Tables Updated Successfully";
		  }
	  }
	  
	  else if ($oper === "newCustomer"){
		  $custname = $_POST['custname'];
		  $custaddr = $_POST['custaddr'];
		  $custphne = $_POST['custnum'];
		  
		  $checkString = seoUrl($custname);
		  $checkResult = True;
		  $query = "SELECT `custname` FROM `customer` WHERE `company_companyID`='$companyID'";
		  $query = $conn->query($query);
		  
		  while ($row = $query->fetch_assoc()) {
			  $DBcustname = seoUrl($row['custname']);
			  if ($DBcustname === $checkString) {
				  $checkResult = False;
				  break;
			  }
			  else {
				  continue;
			  }
		  }
		  
		  if(!$checkResult){
			  $result = "error";
			  $message = "Unable to Add New Customer as customer already exists";
		  }
		  else {
			  $query = "INSERT INTO `customer` (`custname`, `custaddress`, `custphone`, `company_companyID`) VALUES ('$custname', '$custaddr', '$custphne', '$companyID')";
			  $query = $conn->query($query);
			  
			  if (!$query){
				  $result = "error";
				  $message = "Unable to Create Customer Entry, please try again!";  
			  }
			  else {
				  $result = "success";
				  $message = "Customer Added Successfully";
			  }
		  }
	  }
	  
	  else if ($oper === "delCustomer") {
		  $value = (int)$_POST['value'];
		  $query = "DELETE FROM `customer` WHERE custID='$value' AND company_companyID='$companyID'";
		  $query = $conn->query($query);
		  if (!$query){
			  $result = "error";
			  $message = "Unable to delete customer record, please try again!";
		  }
		  else {
			  $result = "success";
			  $message = "Customer record deleted successfully";
		  }
	  }
	  
	  else if ($oper === "viewCustomer") {
		  $value = (int)$_POST['value'];
		  $query = "SELECT * FROM `customer` WHERE custID='$value' AND company_companyID='$companyID'";
		  $query = $conn->query($query);
		  if (!$query){
			  $result = "error";
			  $message = "Unable to delete customer record, please try again!";
		  }
		  else {
			  while ($row = $query->fetch_assoc()){
				  $data[] = $row['custname'];
				  $data[] = $row['custaddress'];
				  $data[] = $row['custphone'];
				  
			  }
			  $result = "update";
			  $message = "Customer record extracted successfully";
		  }
	  }
	  
	  $conn->close();
	  $jsonData = array(
		  "result"  => $result,
		  "message" => $message,
		  "data"	=> $data
	  );
	  // Convert PHP array to JSON array
	  $json_data = json_encode($jsonData);
	  echo $json_data;
  }
?>