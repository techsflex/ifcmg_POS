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

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link href="css/kitchen.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row">
			
			</div>
		</div>
	</body>
</html>