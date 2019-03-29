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
      $job == 'get_product'   ||
      $job == 'add_product'   ||
      $job == 'edit_product'  ||
      $job == 'delete_product'){
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
				$functions .= '<li class="function_order_edit"><a data-id="'.$company['orderID'].'" data-name="'.$company['orderID'].'"><span>Edit</span></a></li>';
				$functions .= '<li class="function_order_print"><a data-id="'.$company['orderID'].'" data-name="'.$company['orderID'].'"><span>Edit</span></a></li>';
				$functions .= '</ul></div>';
				$mysql_data[] = array(
				  "product_id"    => $company['orderID'],
				  "product_name"   => $company['datetime'],
				  "product_price"  => $company['paymentID'],
				  "category"  => $company['ordertype'],
				  "description" => $company['grandtotal'],		
				  "functions"  => $functions
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
			$query = "SELECT * FROM `orders` WHERE `orderID` = '$orderID'";
			$query = $conn->query($query);
			
			if (!$query){
				$result  = 'error';
				$message = 'Order Details Query Error';
			}
			else {
				while ($company = $query->fetch_array()){
					$mysql_data[] = array(
						"orderID" => $company['orderID'],
						"paymentType"		=> $company['paymentID'],
						"orderType" 		=> $company['ordertype'],
						"date"				=> $company['datetime'],
						"sub_total"			=> $company['subtotal'],
						"tax_rate"			=> $company['taxrate'],
						"discount_amount"	=> $company['discount'],
						"grand_total" 		=> $company['grandtotal'],
						"breakdown" 		=> $company['breakdown']
					);
				}
				$result  = 'success';
				$message = 'qOrder Details Extracted Successfully';
			}
		}
	}
	
	elseif ($job == 'add_product'){
    
    // Add product
    $query = "INSERT INTO products_table SET ";
    if (isset($_GET['orderID']))         { $query .= "orderID         = '" . mysqli_real_escape_string($db_connection, $_GET['orderID'])         . "', "; }
    if (isset($_GET['product_name'])) { $query .= "product_name = '" . mysqli_real_escape_string($db_connection, $_GET['product_name']) . "', "; }
    if (isset($_GET['product_price']))   { $query .= "product_price   = '" . mysqli_real_escape_string($db_connection, $_GET['product_price'])   . "', "; }
    if (isset($_GET['description']))      { $query .= "description      = '" . mysqli_real_escape_string($db_connection, $_GET['description'])      . "' "; }

    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_product'){
 
    // Edit product
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE products_table SET ";
      if (isset($_GET['orderID']))         { $query .= "orderID         = '" . mysqli_real_escape_string($db_connection, $_GET['orderID'])         . "', "; }
      if (isset($_GET['product_name'])) { $query .= "product_name = '" . mysqli_real_escape_string($db_connection, $_GET['product_name']) . "', "; }
      if (isset($_GET['product_price']))   { $query .= "product_price   = '" . mysqli_real_escape_string($db_connection, $_GET['product_price'])   . "', "; }
		     
      if (isset($_GET['description']))      { $query .= "description      = '" . mysqli_real_escape_string($db_connection, $_GET['description'])      . "' "; }
  
		
      $query .= "WHERE table_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
		
       $queryString  = $query; 
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = $queryString;
      } else {
        $result  = 'success';
       // $message = $queryString;
		  $message ='Updated Successful!';
      }
	
    }
    
  } elseif ($job == 'delete_product'){
  
    // Delete product
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM products_table WHERE table_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
	$queryString = $query;
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query successful';
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