<?php
// Database details
session_start();
include 'config.php';

$name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$statusID   = (int)$_SESSION['statusID'];
$companyID  = (int)$_SESSION['companyID'];


// Get job (and id)
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_products' ||
      $job == 'get_product' ||
	  $job == 'delete_product'
	 ){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
    else {
      $id = '';
    }
  } else {
    $job = '';
  }
}

// Prepare array
$mysql_data = array();

// Valid job found
if ($job != ''){  
	// Execute job
	if ($job == 'get_products'){
		// Get companies
		$query = "SELECT * FROM `held` WHERE company_companyID='$companyID'";
		$query = $conn->query($query);
		
		if (!$query){
			$result  = 'error';
			$message = 'Unable to access database to retrieve On Hold Orders';
		}
		else {
			while ($company = mysqli_fetch_array($query)){
				$functions  = '<div class="function_buttons"><ul>';
				$functions .= '<li class="function_hold_order_edit"><a data-id="'   . $company['heldID'] . '" data-name="' . $company['heldID'] . '"><span>Edit</span></a></li>';
				$functions .= '<li class="function_delete_hold_order"><a data-id="' . $company['heldID'] . '" data-name="' . $company['heldID'] . '"><span>Delete</span></a></li>';
				$functions .= '</ul></div>';
				
				if ((int)$company['kitchenstatus'] === 0) {
					$kitchen = "Preparing";
				}
				elseif ((int)$company['kitchenstatus'] === 1) {
					$kitchen = "Ready";
				}
				else {
					$kitchen = "Rejected";
				}
				$mysql_data[] = array(
					"ID"			=> $company['heldID'],
					"date"  		=> $company['datetime'],
					"type" 			=> $company['ordertype'],
					"total"			=> $company['grandtotal'],
					"kitchen" 		=> $kitchen,		
					"functions"     => $functions
				);
			}
			$result  = 'success';
			$message = 'Successfully extracted On-hold orders';
		}
	}
	
	elseif ($job == 'get_product'){
		// Get product
		if ($id == ''){
			$result  = 'error';
			$message = 'id missing';
		}
		else {
			$query = "SELECT * FROM `hold_orders_table` WHERE `hold_order_id` = '" . mysqli_real_escape_string($db_connection, $id) . "'";
			$query = mysqli_query($db_connection, $query);
			
			if (!$query){
				$result  = 'error';
				$message = 'query error';
			} else {
				$result  = 'success';
				$message = 'query success';
				
				$date = date_create($company['time']);
				$formattedTime = date_format($date, 'H:i:s');
				
				while ($company = mysqli_fetch_array($query)){
					$mysql_data[] = array(
						"product_id"          => $company['hold_order_id'],
						"product_name"  => $company['date'],
						"product_price"    => $formattedTime,
						"description"       =>  $company['discount_amount'],
						"category" => $company['grand_total'],
					);
				}
			}
		}
	}
	
	elseif ($job == 'delete_product'){
		// Delete product
		if ($id == ''){
			$result  = 'error';
			$message = 'Selected ID is invalid ot not found!';
		}
		else {
			$heldID = $conn->real_escape_string($id);
			$query = "DELETE FROM held WHERE heldID = '$heldID'";
			
			$query = $conn->query($query);
			
			if (!$query){
				$result  = 'error';
				$message = 'Unable to delete record!';
			}
			else {
				$result  = 'success';
				$message = 'Selected record successfully deleted!';
			}
		}
	}
	// Close database connection
	$conn->close();
}

// Prepare data
$data = array(
	"result"  => $result,
	"message" => $message,
	"data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
echo $json_data;
?>