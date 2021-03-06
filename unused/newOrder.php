<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>New Order</title>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	 <link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">

	<link rel="stylesheet" href="./css/animate.min.css">
    <link rel="stylesheet" href="./css/customStyle.css">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	
		
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  
	<script src="js/jquery.min.js"></script>
	<script src="/js/wow.min.js"></script>
	<script>new WOW().init();</script>

	
     
	
</head>

<body>
	<div class="col-sm-12  col-lg-12  main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">New Order</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">New Order</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-md-6">	<!-- BEGIN LEFT COL --->
			
				<div class="panel panel-default articles"> 	<!-- BEGIN RECEIPTS --->
					<div class="panel-heading">	
						Receipt
					
						</div>
					<div class="panel-body articles-container table-responsive">
						
						
    <input type="checkbox" onClick="toggle(this)" /> Toggle All<br/>
    <table id="myTable" class="tableClass bootstrap-table table-bordered table-hover" >
        <thead>
            <tr>
                <th>Select</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead><br>
        <tbody>
         
        </tbody>
    </table>
    <button type="button" onclick="deleteRow()" class="color button dark-blue">Delete Item</button>
  </div>
</div><!--END RECEIPTS-->
				
				<div class="panel panel-default ">	<!--BEGIN EXTRA SECTION --->
					<div class="panel-heading">
						Amount (PKR)
					
						</div>
					<div class="panel-body">
					<table id="amountTable"  name="amountTable" width="100%" cellspacing="5" cellpadding="20" class="bootstrap-table table-responsive">
  						<tbody>
   						<tr>
      						<td class="title" >Sub Total</td>
      						<td class="amount" id="subTotal" name="subTotal">0.00</td>
    					</tr>
   						<tr>
      						<td class="title">GST (15%)</td>
      						<td class="amount" id="afterTax" name="afterTax">0</td>
    					</tr>
  						<tr>
      						<td class="title">Discount (PKR)</td>
      						<td class="amount" ><input id="discountAmount"  name="discountAmount" type="number"  value="0" style="width: 3em; text-align: right;" min="0" max="100"/></td>
    					</tr>
   						<tr>
      						<td class="title">Grand Total</td>
      						<td class="amount" id="grandTotal" name="grandTotal">0.00</td>
    					</tr>
 					 </tbody>
					</table>
					<br><div  align="right"><button class="btn btn-primary" id="confirmOrder" name="confirmOrder" >Proceed With Order</button><span>&nbsp;&nbsp;  </span><button class="btn btn-success" id="holdOrder" name="holdOrder">Hold Order</button><span>&nbsp;&nbsp;  </span><button class="btn btn-danger" id="cancelOrder">Cancel Order</button></div>
					</div>
				</div>	<!-- END EXTRA SECTION --->
				
					
			</div><!--END LEFT COL-->
			
			
			<!--RIGHT COL BEGINS  --->		
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
				      Categories
				
						</div>
					<div class="panel-body">
						<div id="calendar"></div>
							   <button class="btn btn-default filter-button" data-filter="all"> ALL </button>
		
								<?php
		
								include 'dynamic_cat_ddl.php';
								$allCats = array();
								while ($row = $query->fetch_assoc()) {
									$cat_code = str_replace(" ", "-", $row['cat_name']);
									echo "<button class=\"btn btn-default filter-button\" data-filter='".$cat_code."'> ".$row['cat_name']."</button>";
									array_push($allCats, $cat_code);
	    						}
								//print_r($allCats);
								?>
					</div>
				</div>
			
			
				<!-- PRODUCTS SECTION BEGINS --->
				<div class="panel panel-default">
					<div class="panel-heading">
						Products
				
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div id="calendar"></div>
							<?php
			 				include 'config.php';
							//print_r($allCats);
			
							//For each category
							foreach ($allCats as $cat_class) {
				
								//Remove hypens, replace with whitespace for exact cat names
			    				$cat_name= str_replace("-", " ", $cat_class);
								//Get All products from that category from DB -> get cat Id from cat name (INNER JOIN)
			     				$query_db = "SELECT  * FROM products_table t1 INNER JOIN  categories_table t2 ON t1.category= t2.cat_id WHERE t2.cat_name='".$cat_name."'";
								$query = $db->query($query_db);
            					while ($row = $query->fetch_assoc()) {	?>
                 					<div style="text-align: center;" class="col-lg-4 col-md-6 col-sm-6 col-xs-12 gallery-image filter <?php echo $cat_class;?>">
			     					<button class="color button items dark-blue" value="<?php echo $row['table_id']."/".$row['product_id']."/".$row['product_name']."/".$row['product_price']; ?>"><?php echo $row['product_name']; ?></button>
									</div>								
				 				<?php
									// echo $row['cat_name']." ".$row['table_id']." ".$row['product_id']." ".$row['product_name']." ".$row['product_price']." ".$row['description']." ".$row['cat_id']."<br>";
								}
							}		
						?>
					</div>
				</div>
				<!-- PRODUCT SECTION ENDS--->
			
			
			
			
			</div><!--RIGHT COL ENDS-->
			
			
			
			
		
			
			
			
		
		</div><!--/.row-->
			<div class="col-sm-12">
				<p class="back-link">Powered by <a href="https://www.techsflex.net">Techsflex</a></p>
			</div>
	</div>	<!--/.main-->
	
	
	
	
	

	<script>
				
            $(".items").click(function() {
               // alert(this.value); // or alert($(this).attr('id'));
				
				var allInfo = this.value;
				
				var splitInfo = allInfo.split('/');
				var rowId = splitInfo[0];
				var prodId = splitInfo[1];
				var prodName = splitInfo[2];
				var prodPrice = parseFloat(splitInfo[3]).toFixed(2)+'';
				
				
				//alert(rowId);
				
				
				if(document.getElementById(rowId)){
					
			
				  //alert("IT EXISTS");
				  
				  //Just updating name for now
				  var cell = document.getElementById(rowId).cells[4];
				
					
				  var quantity = (cell.innerHTML.split('value="')[1]).split('"')[0];
				   //alert(quantity);
				 	
				   quantity = parseFloat(quantity) + 1;
				   var textBoxId = 'it'+rowId;	
					//alert("new qty "+quantity);
				   cell.innerHTML = "<input value='"+quantity+"' maxlength='10' type='number' class='qtyTextBox' style='width: 4em'  id='"+textBoxId+"'>";
					//<input value="1" maxlength="10" type="text">
					
					var priceCellVal = parseFloat(document.getElementById(rowId).cells[3].innerHTML);
					//var totalPriceCellVal = parseFloat(document.getElementById(rowId).cells[5].innerHTML);
					
					var totalPrice = priceCellVal * quantity;
					
					
					if(!isNaN(totalPrice)){					
					    document.getElementById(rowId).cells[5].innerHTML = (totalPrice).toFixed(2);
					    updateAmount();
						
									
					}
					
					else{
						 document.getElementById(rowId).cells[5].innerHTML = (0).toFixed(2);
						
						
					}
				  
			  }
			
			//onchange='qtyChangeFun(\""+textBoxId+"\");'	onkeyup='this.onchange();' onpaste='this.onchange();' onkeypress='this.onchange();'  oninput='this.onchange();'
				
				else{
				var textBoxId = 'it'+rowId;	
				//alert(textBoxId);
				
				var markup = "<tr id='"+rowId+"'><td><input type='checkbox' name='record' ></td><td>" + prodId + "</td><td>"+ prodName + "</td><td>" + prodPrice + "</td><td><input value='1' type='number'  maxlength='10' class='qtyTextBox' style='width: 4em'  id='"+textBoxId+"'></td><td>"+prodPrice+"</td></tr>";
					
				//alert(markup);
                $("#myTable tbody").append(markup);
					
				//update subTotal
			     updateAmount();
				}

				
             });
		
		
		
		
		
           function updateAmount(){
			   var subTotal = parseFloat(0).toFixed(2);
			
			   var table = document.getElementById("myTable");
			   	for (var i = 1, row; row = table.rows[i]; i++) {
					 var tempCell  = parseFloat(row.cells[5].innerHTML).toFixed(2);
					subTotal =( +tempCell + +subTotal).toFixed(2);
					//alert(subTotal);
   				    
   					
				}
			   document.getElementById("subTotal").innerHTML = subTotal+'';
			   
			   document.getElementById("afterTax").innerHTML = (+subTotal * +1.15).toFixed(2) +'';
			   
			  grandTotal();
			   
		   }	
		
		
		  function grandTotal(){
			  
			  var afterTax =  +document.getElementById("afterTax").innerHTML ;
			  var discount = +(document.getElementById("discountAmount").value);
			  
			  var grandTotal = afterTax - discount ;
			  document.getElementById("grandTotal").innerHTML  = (+grandTotal).toFixed(2)+'/='
			  
			  
		  }
				
			</script>
	
    <!--Rights Side Ends --> 
	</div>
	


	<!-- For Fetching the Images According to their Categories -->
    <script>
		$(document).ready(function(){

			
	    	$(".filter-button").click(function(){
	        	var value = $(this).attr('data-filter');
	        
	        	if(value == "all"){
	            	//$('.filter').removeClass('hidden');
	            	$('.filter').show('1000');
	        	}
	        	else{
					// $('.filter[filter-item="'+value+'"]').removeClass('hidden');
					// $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
		            $(".filter").not('.'+value).hide('3000');
		            $('.filter').filter('.'+value).show('3000');
	            }
	    	});
	    
	    	if ($(".filter-button").removeClass("active")) {
				$(this).removeClass("active");
			}

			$(this).addClass("active");
			
		});
		
		$('#amountTable').bind('keyup', 'discountAmount', function(event){
			
			grandTotal();
			
			});
		
		
		
		// change textInput keypress keyup paste input	
		
		$('#myTable').bind('keyup', 'qtyTextBox', function(event){
			
		  // ID OF ELEMENT WHICH TRIGGERED EVENT -> event.target.id
		   var basicId = $(event.target).attr('id');
		   var index = basicId.substring(2,basicId.length);
		   var price = parseFloat(document.getElementById(index).cells[3].innerHTML);
		   var qty = parseFloat($(event.target).val());
			
		   var totalPrice = price *qty;
			
		   
		   
			
		//Update inner HTML of QTY textbox
		  var cell = document.getElementById(index).cells[4];
		  var textBoxId = 'it'+index;
		  cell.innerHTML =  "<input value='"+qty+"' maxlength='10' type='number' class='qtyTextBox' style='width: 4em' id='"+textBoxId+"'>";
			
		  //Update inner HTML of total Price
			
		 if(!isNaN(totalPrice)){
		 document.getElementById(index).cells[5].innerHTML = (totalPrice).toFixed(2);
		 updateAmount();
		 }
		
		else{
			document.getElementById(index).cells[5].innerHTML = parseFloat(0).toFixed(2);
		}
		
		   
	     // alert(" "+basicId+" "+index+" "+price+" "+qty+" "+totalPrice.toFixed(2));  
		 
	
		});
		
	
	
	</script>
	 <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script type="text/javascript">
 
			/*
		
         $(document).ready(function(){  
        // Find and remove selected table rows
        $(".delete-row").click(function(){							
        $("table tbody").find('input[name="record"]').each(function(){
			if($(this).is(":checked")){
                    $(this).parents("tr").remove();
				}
            });
        }); 
    });  
	
	*/ 
	
	document.getElementById("cancelOrder").onclick = function () {	
		var r = confirm("Cancel Order?");
			if (r == true) {
    		  location.href = "index.html";
			} 		
    };
	
	document.getElementById("holdOrder").onclick = function () {	
		alert("HOLD IT");		
    };
	
	
	document.getElementById("confirmOrder").onclick = function () {		
      alert("ASDASDA");
		/*
		  var discountAmount = $('#discountAmount').val();
     
		 var table = document.getElementById("myTable");
		//get product id, qty, subtotal, after tex, discount and grand total
	
	
		
		var jsonArr = [];

		for (var i = 1, row; row = table.rows[i]; i++) {
    		jsonArr.push({
        		productCode: row.cells[1].innerHTML,
				productName: row.cells[2].innerHTML,
				productPrice: row.cells[3].innerHTML,
				productQuantity: ((row.cells[4].innerHTML).split('value="')[1]).split('"')[0],
				productTotal:row.cells[5].innerHTML
        		
    		});
			}	
		
			
		
	var subTotal= document.getElementById("amountTable").rows[0].cells[1].innerHTML;
	var afterTax= document.getElementById("amountTable").rows[1].cells[1].innerHTML;
	var grandTotal = (document.getElementById("amountTable").rows[3].cells[1].innerHTML).replace("/=","");
		
		
	var jsonObj = { "subTotal":subTotal, "afterTax":afterTax, "discountAmount":discountAmount , "grandTotal":grandTotal , "breakdown":jsonArr} ;
			
	var jsonArrString = JSON.stringify(jsonArr);
		if(+grandTotal!=0){
		
	
		
		
		 $.ajax({
                       url: "generateReceipts.php",
                       type: "POST",
                       data: {
                           "subTotal":subTotal,
						   "afterTax":afterTax,
						   "discountAmount":discountAmount ,
						   "grandTotal":grandTotal,
						   "breakdown":jsonArrString
                       },
                       dataType: "JSON",
                       success: function (jsonStr) {
                       //alert(JSON.stringify(jsonStr));
						   alert("success!!");
                       }
                   });
			
		       var stringJSON = JSON.stringify(jsonObj);
		
			

	   /*
			 alert("Order Processed!");
		     $("#myTable tbody").find('input[name="record"]').each(function(){			
                    $(this).parents("tr").remove();
					updateAmount();   
					
            });
															  
	 
			// location.href = "createpdf.php?data="+jsonObj;
		 var newwindow = window.open("createpdf.php?data="+stringJSON, '_blank');
        newwindow .focus();
		} 
	*/
		
    };
	
	

	function deleteRow(el){
		//alert("DELETE");
      $("#myTable tbody").find('input[name="record"]').each(function(){
								
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
					updateAmount();
				}
		
		   
            });
		
		
	
	
	//Toggle checkboxes
function toggle(source) {
  checkboxes = document.getElementsByName('record');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
	
	</script>
	

</body>
</html>