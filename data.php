<?php
// Database details
session_start();
include 'config.php';

$first_name = $_SESSION['first_name'];
$last_name  = $_SESSION['last_name'];
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
    $query = "SELECT * FROM `products` INNER JOIN `cat` ON products.cat_catID=cat.catID WHERE company_companyID='$companyID' ORDER BY skuID";
    $query = $conn->query($query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['skuID'] . '" data-name="' . $company['productname'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete_product"><a data-id="' . $company['skuID'] . '" data-name="' . $company['productname'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
		
        $mysql_data[] = array(
          "product_id"          => $company['productID'],
          "product_name"  => $company['productname'],
          "product_price"    => $company['productprice'],
          "description"       =>  $company['description'],
		      "category" => $company['catname'],		
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
      $query = "SELECT * FROM `products_table` INNER JOIN `categories_table` ON products_table.category=categories_table.cat_id WHERE table_id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "product_id"          => $company['product_id'],
            "product_name"  => $company['product_name'],
            "product_price"    => $company['product_price'],
			      "category" => $company['cat_name'],	
            "description"       => $company['description'],

          );
        }
      }
    }
  
  } elseif ($job == 'add_product'){
    
	  
	$product_id_ap = "N/A";
	$product_name_ap = "N/A";
	$product_price_ap = 0.00;
	$product_cat_ap = 1;
	$product_desc_ap = "N/A";
	  
	if (isset($_GET['product_id'])) {
    $product_id_ap = $conn->real_escape_string($_GET['product_id']);
    if (isset($_GET['product_name'])) {
      $product_name_ap = $conn->real_escape_string($_GET['product_name']);
      if (isset($_GET['product_price'])) {
        $product_price_ap = $conn->real_escape_string($_GET['product_price']);
        if (isset($_GET['description'])) {
          $product_desc_ap = $conn->real_escape_string($_GET['description']);
          if (isset($_GET['cat_name2'])) {
            $product_cat_ap = (int)$conn->real_escape_string($_GET['cat_name2']);
            $query = "INSERT INTO `products`( `productID`, `productname`, `productprice`, `description`, `cat_catID`) VALUES 
                          ('$product_id_ap', '$product_name_ap', '$product_price_ap', '$product_desc_ap', '$product_cat_ap')";
            $query=$conn->query($query);
            if (!$query){
              $result  = "error";
              $message = 'Error: Problem INSERTING product to Database';
            } else {
              $result  = 'success';
              $message = 'Product "' . $product_name_ap . '" successfully created!';
            }
          }
          else {
            $result  = "error";
            $message = 'Error: Product Category not set';
          }
        }
        else {
          $result  = "error";
          $message = 'Error: Product Description not set';
        }  
      }

      else {
        $result  = "error";
        $message = 'Error: Product Price not set';
      }
    }
    else {
      $result  = "error";
      $message = 'Error: Product Name not set';
    }
  }
  else {
    $result  = "error";
    $message = 'Error: ProductID not set';
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
      if (isset($_GET['cat_name2']))   { $query .= "category   = '" . mysqli_real_escape_string($db_connection, $_GET['cat_name2'])   . "', "; }
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