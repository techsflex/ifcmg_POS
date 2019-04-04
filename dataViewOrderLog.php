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
      $job == 'get_product'
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
		$query = "SELECT * FROM `orders` WHERE company_companyID='$companyID'";
		$query = $conn->query($query);
		if (!$query){
			$result  = 'error';
			$message = 'Unable to access database!';
		}
		else {
			while ($company = $query->fetch_array()){
				$functions  = '<div class="function_buttons"><ul>';
				$functions .= '<li class="function_order_edit"><a data-id="'.$company['orderID'].'" data-name="'.$company['orderID'].'"><span title="View Details"><i class="fa fa-list"></i></span></a></li>';
				$functions .= '<li class="function_order_print"><a data-id="'.$company['orderID'].'" data-name="'.$company['orderID'].'"><span title="Print"><i class="fa fa-print"></i></span></a></li>';
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
				  "orderID"    		=> $company['orderID'],
				  "datetime"   		=> $company['datetime'],
				  "paymentID"  		=> $company['paymentID'],
				  "ordertype"  		=> $company['ordertype'],
				  "grandtotal" 		=> $company['grandtotal'],
				  "kitchenstatus" 	=> $kitchen,
				  "functions"  		=> $functions
				);
			}
			$result  = 'success';
			$message = 'Previous orders successfully extracted!';
		}
	} 
	
	elseif ($job == 'get_product'){
		// Get Order Details
		if ($id == ''){
			$result  = 'error';
			$message = 'Order ID missing';
		} 
		
		else {
			$orderID = (int)$conn->real_escape_string($id);
			$query = "SELECT * FROM `orders` WHERE `orderID` = '$orderID' AND company_companyID='$companyID'";
			$query = $conn->query($query);
			
			if (!$query){
				$result  = 'error';
				$message = 'Order Details Query Error';
			}
			else {
				while ($company = $query->fetch_assoc()){
					$mysql_data[] = array(
						"orderID" 			=> $company['orderID'],
						"paymentType"		=> $company['paymentID'],
						"orderType" 		=> $company['ordertype'],
						"date"				=> $company['datetime'],
						"sub_total"			=> $company['subtotal'],
						"tax_rate"			=> $company['taxpaid'],
						"discount_amount"	=> $company['discount'],
						"grand_total" 		=> $company['grandtotal'],
						"breakdown" 		=> $company['breakdown']
					);
				}
				$result  = 'success';
				$message = 'Order Details Extracted Successfully';
			}
		}
	}
}
// Close database connection
$conn->close();

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
print $json_data;
?>