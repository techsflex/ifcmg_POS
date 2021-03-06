<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <title>jQuery SCRUD system</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1000, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen:400,700">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="layout.css">
   
    <script charset="utf-8" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
   
    <script charset="utf-8" src="./js/webapp.js"></script>
    
 

  </head>
  <body>

    <div id="page_container">

      <h1>Product List</h1>

      <button type="button" class="button" id="add_product">Add Product</button>

      <table class="datatable" id="table_products">
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

    <div class="lightbox_bg"></div>

    <div class="lightbox_container">
      <div class="lightbox_close"></div>
      <div class="lightbox_content">
        
        <h2>NEW PRODUCT</h2>
        <form class="form add" id="form_product" data-id="" novalidate>
  
          
         
          <div class="input_container">
            <label for="product_id">ID: <span class="required">*</span></label>
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
            <label for="cat_name">Current Product Category: <span class="required">*</span></label>
          <div class="field_container">          
			  <input   id="cat_name" name="cat_name" >
     
            </div>
          </div>
          
           <div class="input_container">
            <label for="cat_name">New Product Category: <span class="required">*</span></label>
          <div class="field_container">          
			  <select   id="cat_name2" name="cat_name2" >
			  <option value="None" selected> List of Cateogries   </option>
			  	
			  	<?php
  
				  include 'dynamic_cat_ddl.php';
				   while ($row = $query->fetch_assoc()) {
                   echo "<option value='".$row['cat_id']."'>".$row['cat_name']."</option>";         
    }
				  
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

  </body>
</html>