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

<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <title>jQuery SCRUD system</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1000, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    
    <script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
    <script charset="utf-8" src="./js/addProductsJS.js"></script>

    <link rel="stylesheet" href="layout.css">
  </head>
  
  <body>
    <div class="col-sm-12  col-lg-12   main">
      <div class="row">
        <ol class="breadcrumb">
          <li><a href="#">
            <em class="fa fa-home"></em>
          </a></li>

          <li class="active">Products</li>
        </ol>
      </div><!--/.row-->

      <div class="row">
        <h1 class="page-header">Products</h1>
      </div><!--/.row-->

      <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">	<!-- BEGIN LEFT COL --->
          <div class="panel panel-default articles"> 	<!-- BEGIN RECEIPTS --->
            <div class="panel-heading">Products List</div>
            <div class="panel-body articles-container table-responsive ">
              <button type="button" class="button" id="add_product">Add Product</button>
              <table class="datatable bootstrap-table table-bordered table-hover" id="table_products">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product Name</th>
            				<th>Product Price</th>
            				<th>Category</th>
            				<th>Description</th>
            				<th>Functions</th>
                  </tr>
                </thead>

                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div> <!---END LEFT COL--->
      </div> <!--END ROW -->
		
	  <!--CONTAINER FOR EDIT PRODUCT-->
      <div class="lightbox_container">
        <div class="lightbox_close"></div>
        <div class="lightbox_content">
          <h2>NEW PRODUCT</h2>

          <form class="form add" id="form_product" data-id="" novalidate>
            <div class="input_container" >
              <label for="product_id">Custom ID: <span class="required">*</span></label>
              <div class="field_container">
                <input type="text"  class="text" name="product_id" id="product_id" value="" required>
              </div>
            </div>

            <div class="input_container">
              <label for="product_name">Product Name: <span class="required">*</span></label>
              <div class="field_container">
                <input type="text" class="text" name="product_name" id="product_name" value="" required>
              </div>
            </div>

            <div class="input_container">
              <label for="product_price">Product Price: <span class="required">*</span></label>
              <div class="field_container">
                <input type="text" class="text" name="product_price" id="product_price" value="" required>
              </div>
            </div>

            <div class="input_container">
              <label for="cat_name">Current Category: <span class="required">*</span></label>
              <div class="field_container">          
                <input id="cat_name" name="cat_name" disabled>
              </div>
            </div>

            <div class="input_container">
              <label for="cat_name">New Product Category: <span class="required">*</span></label>
              <div class="field_container">          
                <select id="cat_name2" name="cat_name2" class="select" >
                  <option value="None" selected>-- Please select category --</option>
                  
                  <?php
                    include 'config.php';
                    $query = "SELECT * FROM `cat` WHERE company_companyID='$companyID' ORDER BY `catname` ASC";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                      echo "<option value='".$row['catID']."'>".$row['catname']."</option>";         
                    }
                    $conn->close();
                  ?>

                </select>
              </div>
            </div>

            <div class="input_container">
              <label for="description">Description: </label>
              <div class="field_container">
                <input type="text" class="text" name="description" id="description" value="">
              </div>
            </div>

            <div class="button_container">
              <button type="submit">Add Product</button>
            </div>
          </form>
        </div>
      </div>
		
      <noscript id="noscript_container">
        <div id="noscript" class="error">
          <p>JavaScript support is needed to use this page.</p>
        </div>
      </noscript>

      <div id="message_container">
        <div id="message" class="success">
          <p>This is a success message.</p>
        </div>
      </div>

      <div id="loading_container">
        <div id="loading_container2">
          <div id="loading_container3">
            <div id="loading_container4">
              Loading, please wait...
            </div>
          </div>
        </div>
      </div>
    </div> <!-- END MAIN DIV -->
  </body>
</html>