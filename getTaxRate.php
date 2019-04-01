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
		$companyID  = (int)$_SESSION['companyID'];
		$query = "SELECT `taxrate` FROM `company` WHERE companyID='$companyID'";
		$result = $conn->query($query);
		
		while ($row = $result->fetch_assoc()){
			$output = $row["taxrate"];
		}
		$conn->close();
		echo $output;
	}
?>