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
	  
	  include "config.php";
	  $ID  = (int)$_POST['ID'];
	  $job = $_POST['job'];
	  
	  $prodName = array();
	  
	  if ($job === "getProducts") {
		  $query = "SELECT * FROM products WHERE cat_catID='$ID'";
		  if ($result = $conn->query($query)) {
			  while ($row = $result->fetch_assoc()){
				  $prodName[$row['productname']] = $row['skuID'];
			  }
		  }
	  }
	  
	  else if ($job === "searchHist") {
		  $query = "SELECT * FROM pricehist WHERE products_skuID='$ID' ORDER BY date_modified DESC";
		  $result = $conn->query($query);
		  if (!$result){
			  $result  = "error";
			  $message = "Unable to retrieve Product Price History, please try again";
		  }
		  else {
			  while ($row = $result->fetch_assoc()){
				  $prodName[] = array(
					  "date" => $row['date_modified'],
					  "user" => $row['created_by'],
					  "cost" => $row['price'],
				  );
			  }
		  }
	  }
	  $conn->close();
	  echo json_encode($prodName);
  }
?>