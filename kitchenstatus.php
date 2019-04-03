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
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/kitchenStatus.js"></script>
		
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row"><h2 class="text-center"><strong>KITCHEN ORDERS</strong></h2></div>
			<div class="col-md-6">
				<div class="kitchenContainer border-right">
					<h3 class="text-center"><strong>Pending Orders</strong></h3>
					<!--echo "<div class=$order>$value</div>";-->
					<?php
						include "config.php";
						//Initialize array to storequery results
						$kitchenList = array();
						//search for this status
						$kitchenstatus = 0; //0->Preparing, 1->Ready

						//Extract from Placed Orders Table
						$query = "SELECT `datetime`, `orderID`, `ordertype`, `breakdown` FROM `orders` WHERE `kitchenstatus`='$kitchenstatus' AND company_companyID='$companyID' ORDER BY `datetime` ASC";
						$query = $conn->query($query);
						while ($row = $query->fetch_assoc()){
							$kitchenList[] = array(
								"datetime"	=> $row['datetime'],
								"orderID"   => $row['orderID'],
								"orderType" => $row['ordertype'],
								"breakdown" => $row['breakdown'],
							);
						}

						//Extract from Hold Orders Table
						$query = "SELECT `datetime`, `heldID`, `ordertype`, `breakdown` FROM `held` WHERE `kitchenstatus`='$kitchenstatus' AND company_companyID='$companyID' ORDER BY `datetime` ASC";
						$query = $conn->query($query);
						while ($row = $query->fetch_assoc()){
							$kitchenList[] = array(
								"datetime"	=> $row['datetime'],
								"heldID"   => $row['heldID'],
								"orderType" => $row['ordertype'],
								"breakdown" => $row['breakdown'],
							);
						}

						usort($kitchenList, function($a, $b){
							$ad = new DateTime($a['datetime']);
							$bd = new DateTime($b['datetime']);

							if ($ad == $bd){
								return 0;
							}

							return $ad < $bd ? -1 : 1;
						});

						//Create Button List for Kitchen Status Page
						foreach ($kitchenList as $index){
							if ($index['orderType'] === "Dine-In"){
								$orderButton = "<button class='dinein' ";
							}
							else {
								$orderButton = "<button class='takeaway' ";
							}

							if (array_key_exists("heldID", $index)){
								$orderButton .= "id='hID" . $index['heldID'] . "' onClick='openDetails(this.id)'>";
								$datetime = "HOLD " . $index['datetime'];
							}
							else {
								$orderButton .= "id='oID" . $index['orderID'] . "' onClick='openDetails(this.id)'>";
								$datetime = "ORDER " . $index['datetime'];
							}

							echo "$orderButton $datetime</button>";
						}
						$conn->close();
					?>
				</div>
			</div>
			
			<div class="col-md-6">
				<h3 class="text-center"><strong>Order Detail</strong></h3>
				
			</div>
		</div>
	</body>
</html>