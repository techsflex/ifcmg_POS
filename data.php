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
        $functions .= '<li class="function_view_product"><a data-id="' . $company['skuID'] . '" data-name="' . $company['productname'] . '"><span>View</span></a></li>';
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
      $prodID = (int)$conn->real_escape_string($id);
      $query = "SELECT * FROM `products` INNER JOIN `cat` ON products.cat_catID=cat.catID WHERE skuID = '$prodID' AND company_companyID='$companyID'";
      $query = $conn->query($query);
      if (!$query){
        $result  = 'error';
        $message = 'Connection Failed!';
      } else {
        $result  = 'success';
        $message = 'Found matching product!';
        while ($company = $query->fetch_array()){
          $mysql_data[] = array(
            "product_id"    => $company['productID'],
            "product_name"  => $company['productname'],
            "product_price" => $company['productprice'],
			      "category"      => $company['catname'],	
            "description"   => $company['description'],
          );
        }
      }
    }
  } 

  elseif ($job == 'add_product'){
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
              } 
              else {
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
  
  } 

  elseif ($job == 'edit_product'){
    // Edit product
    if ($id == ''){
      $result  = 'error';
      $message = 'ProductID not found';
    } 
    else if (isset($_GET['product_id']) && isset($_GET['product_name']) && isset($_GET['product_price']) && isset($_GET['cat_name2']) && isset($_GET['description'])) {
      
      $skuID        = (int)$conn->real_escape_string($id);
      $productID    = $conn->real_escape_string($_GET['product_id']);
      $productname  = $conn->real_escape_string($_GET['product_name']);
      $productprice = $conn->real_escape_string($_GET['product_price']);
      $catID        = (int)$conn->real_escape_string($_GET['cat_name2']);
      $description  = $conn->real_escape_string($_GET['description']);

      
      //Save Previous Price of Product
      $query = "SELECT productprice FROM products WHERE skuID = '$skuID'";
      $query = $conn->query($query);
      $row = $query->fetch_array();
      $prevprice = $row[0];
      
      //Update price of product records
      $query =  "UPDATE products SET productID='$productID', ";
      $query .= "productname='$productname', productprice='$productprice', ";
      $query .= "description='$description', cat_catID='$catID'";
      $query .= "WHERE skuID='$skuID'";

      $query = $conn->query($query);
      if (!$query){
        $result  = 'error';
        $message = 'Unable to update table entry due to SQL error or undefined Category!';
      } 

      else if ($prevprice != $productprice) {
        $query = "SELECT count(*) as total FROM `pricehist` WHERE `products_skuID`='$skuID'";
        $query = $conn->query($query);
        $row = $query->fetch_assoc();
          
        if(!$query){
          $result  = 'error';
          $message = 'Successfully update Product: ' . $productname . ' - Error creating price history entry!';
        }

        else if ($row['total'] < 5){
          //Only add new record if records of price change don't go over 5
          $query =  "INSERT INTO `pricehist` (`created_by`, `price`, `products_skuID`) VALUES ";
          $query .= "('$name', '$prevprice', '$skuID')";
          $query = $conn->query($query);
          
          $result  = 'success';
          $message = 'Successfully update Product: ' . $productname;  
        }
        
        else {
          //Modify oldest record when price change for product is exceeding 5
          $query = "SELECT * FROM `pricehist` WHERE `products_skuID`='$skuID' ORDER BY date_modified ASC LIMIT 1";
          $query = $conn->query($query);
          
          while ($row = $query->fetch_assoc()) {
            $rowID = $row["priceID"];
          }
          $query =  "UPDATE pricehist SET date_modified=CURRENT_TIMESTAMP, ";
          $query .= "created_by='$name', price='$prevprice' WHERE priceID='$rowID'";
          $query = $conn->query($query);
            
          $result  = 'success';
          $message = 'Successfully update Product: ' . $productname;     
        }
      }
      else {
        $result  = 'success';
        $message = 'Successfully update Product: ' . $productname;  
      }
    }
  } 

  elseif ($job == 'delete_product'){
  // Delete product
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } 

    else {
      $skuID = (int)$conn->real_escape_string($id);
      $query = "DELETE FROM products WHERE skuID = '$skuID'";
      $query = $conn->query($query);
      
      if (!$query){
        $result  = 'error';
        $message = 'Unable to delete product due to Query Error';
      } else {
        $result  = 'success';
        $message = 'Product successfully deleted!';
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