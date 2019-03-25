<?php
// Database details
include 'config.php';

$db_server   = $DB_HOST;
$db_username = $DB_USER;
$db_password = $DB_PASSWORD;
$db_name     = $DB_NAME;

// Get job (and id)
$job = '';
$id  = '';
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
  } else {
    $job = '';
  }
}

// Prepare array
$mysql_data = array();

// Valid job found
if ($job != ''){
  
  // Connect to database
  $db_connection = $conn;
  if ($conn->connect_error){
    $result  = 'error';
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $job     = '';
  }
  
  // Execute job
  if ($job == 'get_products'){
    
    // Get companies
    $query = "SELECT * FROM `hold_orders_table`";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_hold_order_edit"><a data-id="'   . $company['hold_order_id'] . '" data-name="' . $company['hold_order_id'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete_hold_order"><a data-id="' . $company['hold_order_id'] . '" data-name="' . $company['hold_order_id'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
		
		$date = date_create($company['time']);
		$formattedTime = date_format($date, 'H:i:s');
		  
		  
		  
        $mysql_data[] = array(
          "product_id"          => $company['hold_order_id'],
          "product_name"  => $company['date'],
          "product_price"    => $formattedTime,
          "description"       =>  $company['discount_amount'],
		  "category" => $company['grand_total'],		
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_product'){
    
    // Get product
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
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
  
  } elseif ($job == 'edit_product'){
 
    // Edit product
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE products_table SET ";
      if (isset($_GET['product_id']))         { $query .= "product_id         = '" . mysqli_real_escape_string($db_connection, $_GET['product_id'])         . "', "; }
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
      $query = "DELETE FROM hold_orders_table WHERE hold_order_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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
  
  
  // Close database connection
  mysqli_close($db_connection);

}

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