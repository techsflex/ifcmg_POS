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
	$data = array();
	// Makes it easier to read
	$statusID   = (int)$_SESSION['statusID'];
	$companyID  = (int)$_SESSION['companyID'];
	//Get Operation ID & Job
	$temp_id = $_POST['id'];
	$job = $_POST['job'];
	//Split ID to get relevant parts
	$str_id = explode("-", $temp_id);
	//Extract correct ID
	$id = (int)$str_id[1];
	$table = $str_id[0];
	
	if ($job === "view"){
		//Set correct table to use for query
		if ($table === 'oID'){
			$query = "SELECT `ordertype`, `breakdown` FROM `orders` WHERE `orderID`='$id' AND `company_companyID`='$companyID'";
			$query = $conn->query($query);
			if (!$query){
				$result  = "error";
				$message = "Unable to get orders for viewing, please try again!";
			}
			else {
				while ($row = $query->fetch_assoc()){
					$data[] = array (
						"id" => $id,
						"table" => "o",
						"ordertype" => $row['ordertype'],
						"breakdown" => $row['breakdown'],
					);
				}
				$result  = "success";
				$message = "Orders for kitchen display pulled successully!";
			}
		}
		elseif ($str_id[0] === 'hID') {
			$query = "SELECT `ordertype`, `breakdown` FROM `held` WHERE `heldID`='$id' AND `company_companyID`='$companyID'";
			$query = $conn->query($query);
			if (!$query){
				$result  = "error";
				$message = "Unable to get orders for viewing, please try again!";
			}
			else {
				while ($row = $query->fetch_assoc()){
					$data[] = array (
						"id" => $id,
						"table" => "h",
						"ordertype" => $row['ordertype'],
						"breakdown" => $row['breakdown'],
					);
				}
				$result  = "success";
				$message = "Orders for kitchen display pulled successully!";
			}
		}
	}
	
	else if ($job === "done"){
		$newvalue=1;
		if ($table === "o"){
			$query = "UPDATE `orders` SET `kitchenstatus`='$newvalue' WHERE `orderID`='$id' AND `company_companyID`='$companyID'";
			$query = $conn->query($query);
		}
		else if($table === "h"){
			$query = "UPDATE `held` SET `kitchenstatus`='$newvalue' WHERE `heldID`='$id' AND `company_companyID`='$companyID'";
			$query = $conn->query($query);
		}
		
		if (!$query){
			//Set output messages
			$result  = "error";
			$message = "Unable to process order completion, please try again!";
		}
		else {
			$result  = "success";
			$message = "Order completed successfully!";
		}
	}
	
	//Save Output and close connections
	$conn->close();
	$jsonData = array(
		"result"  => $result,
		"message" => $message,
		"data"	=> $data,
	);
	
	// Convert PHP array to JSON array
	$json_data = json_encode($jsonData);
	echo $json_data;
}
?>