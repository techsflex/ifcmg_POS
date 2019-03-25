<?php
include "config.php";
$tillOperation =  $_POST['tillProcess'];
$tillBalance   =  (int)$_POST['tillBalance'];

date_default_timezone_set('Asia/Karachi');
$date = date("Y-m-d");
$time = date("H:i:s");

//Setup MySQL Queries to be run
if ($tillOperation === "0"){
	$queryString = "INSERT INTO `till_totals_table` (`till_open_date`, `till_open_time`, `till_open_cash`, `till_close_date`, `till_close_time`) VALUES
					(CURRENT_DATE, CURRENT_TIME, '".$tillBalance."', CURRENT_DATE, CURRENT_TIME);";
	
	if (mysqli_query($conn, $queryString)) {
		$message= "Day Open Status: SUCCESSFUL";
	}
	else{
		$message= "Day Open Status: FAILED";
	}
}

else if ($tillOperation === "1"){
	$queryString = "SELECT * FROM `till_totals_table` ORDER BY `till_id` DESC LIMIT 1";
	$result = $conn->query($queryString);
	$row = $result->fetch_array();
	$targetID = (int)$row['till_id'];
	
	if (is_null($row['till_close_cash'])) {
		$queryString = "UPDATE `till_totals_table` 
					SET `till_close_date` = CURRENT_DATE,
						`till_close_time` = CURRENT_TIME,
						`till_close_cash` = $tillBalance
					WHERE `till_id`= $targetID";
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