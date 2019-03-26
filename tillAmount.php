<?php
/* Displays user information and some useful messages */
session_start();
include "config.php";
$tillOperation = (int)$_POST['tillProcess'];
$tillBalance   = (int)$_POST['tillBalance'];
$companyID     = $_SESSION['companyID'];

date_default_timezone_set('Asia/Karachi');

//Setup MySQL Queries to be run
if ($tillOperation == 5){
	$queryString = "INSERT INTO `tillamount` (`tillopendate`, `tillopencash`, `company_companyID`) VALUES
					(CURRENT_TIMESTAMP, '$tillBalance', '$companyID')";
	
	if (mysqli_query($conn, $queryString)) {
		$message= "Day Open Status: SUCCESSFUL";
	}
	else{
		$message= "Day Open Status: FAILED";
	}
}

else if ($tillOperation == 6){
	$queryString = "SELECT * FROM `tillamount` ORDER BY `tillID` DESC LIMIT 1";
	$result = $conn->query($queryString);
	$row = $result->fetch_array();
	$targetID = (int)$row['tillID'];
	
	if (is_null($row['tillclosecash'])) {
		$queryString = "UPDATE `tillamount` 
					SET `tillclosedate` = CURRENT_TIMESTAMP,
						`tillclosecash` = $tillBalance
					WHERE `tillID`= $targetID";
		if (mysqli_query($conn, $queryString)) {
			$message= "Day Close Status: SUCCESSFUL";
		}
		else{
			$message= "Day Close Status: FAILED";
		}
	}
	
	else {
		$message = "ERROR: Day was already closed!";
	}
}

else {
	$message = "Invalid Operation Encountered!";
}

echo $message;
mysqli_close($conn);
?>