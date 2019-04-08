// Show message
function show_message(message_text, message_type){
	$('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
	$('#message_container').show();
	if (typeof timeout_message !== 'undefined'){
		window.clearTimeout(timeout_message);
	}
	timeout_message = setTimeout(function(){
		hide_message();
	}, 8000);
}
  
// Hide message
function hide_message(){
	$('#message').html('').attr('class', '');
	$('#message_container').hide();
}

// Show loading message
  function show_loading_message(){
    $('#loading_container').show();
  }
  // Hide loading message
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  // Show lightbox
  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  // Hide lightbox
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }

// Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  function fixedWidth(str, length) {
    if ( str.length >= length) {
      return str.concat(substring(str, length-3), "...");
    }
    else {
      var step;
      var filler = " ";

      padding = length - str
      for (step = 0; step < padding; step++) {
        str += "&nbsp;";
      }
    }
    return str
  }

$(document).ready(function(){
	// On page load: Create and load Orders datatable
	var table_order = $('#table_products').DataTable({
		"ajax": "dataViewOrderLog.php?job=get_products",
		"columns": [
			{ data: "orderID" },
			{ data: "datetime"},
			{ data: "paymentID" },
			{ data: "ordertype" },
			{ data: "grandtotal" },
			{ data: "kitchenstatus" },
			{ data: "functions" }
		],
		"order": [[1, "desc"]],
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [-1] }
		],
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"oLanguage": {
			"oPaginate": {
				"sFirst":       " ",
				"sPrevious":    " ",
				"sNext":        " ",
				"sLast":        " ",
			},
			"sLengthMenu":    "Records per page: _MENU_",
			"sInfo":          "Total of _TOTAL_ records (showing _START_ to _END_)",
			"sInfoFiltered":  "(filtered from _MAX_ total records)"
		}
	});
	
	// Lightbox background
	$(document).on('click', '.lightbox_bg', function(){
		hide_lightbox();
	});
	
	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
		hide_lightbox();
	});
	
	// Escape keyboard key
	$(document).keyup(function(e){
		if (e.keyCode == 27){
			hide_lightbox();
		}
	});
	
	// Edit product button
	$(document).on('click', '.function_order_edit a', function(e){
		//alert("ORDER LOG ID"+id);
		e.stopImmediatePropagation();
		e.preventDefault();
		hide_lightbox();
		var id = $(this).data('id');
		show_loading_message();
		
		var request = $.ajax({
			url:          'dataViewOrderLog.php?job=get_product',
			cache:        false,
			data:         'id='+id,
			dataType:     'json',
			contentType:  'application/json; charset=utf-8',
			type:         'get'
		});
		
		request.done(function(output){
			if (output.result == 'success'){
				// LIGHTBOX HEADER
				var h2 = document.createElement("h2");
				h2.textContent = "View Order Details:";
				//Extract Order Type Details:
				var orderType = output.data[0].orderType;
				var orderText = document.createElement("h5");
				orderText.textContent = "Order Type: " + orderType;
				orderText.style.cssFloat = "left";
				
				//Extract Payment Method Details:
				var orderPayment = output.data[0].paymentType;
				var paymentText = "Payment Method: " + orderPayment;
				var paymentMethod = document.createElement("h5");
				paymentMethod.textContent = paymentText;
				paymentMethod.style.cssFloat = "right";
				
				var orderBreakdown = output.data[0].breakdown;
				var obj = JSON.parse(orderBreakdown);
				var headers     = {
					productCode: "Product Code",
					productName: "Product Name",
					productPrice: "Unit Cost",
					productQuantity: "Quantity",
					productTotal: "Total Cost"
				};
				
				var orderHeader = {
					orderID: "Order #",
					date: "Order Date"
				};
				
				var orderTotals = {
					sub_total: "Sub Total",
					discount_amount: "Discount",
					taxrate: "Tax (%)",
					grand_total: "Grand Total"
				};
				
				// EXTRACT VALUE FOR HTML HEADER. 
				var col = [];
				for (var i = 0; i < obj.length; i++) {
					for (var key in obj[i]) {
						if (col.indexOf(key) === -1) {
							col.push(key);
						}
					}
				}
				
				// CREATE DYNAMIC TABLE.
				var table = document.createElement("table");
				table.setAttribute('class', 'viewOrderLog');
				
				// CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.
				var tr = table.insertRow(-1);                   // TABLE ROW.
				for (i = 0; i < col.length; i++) {
					var th = document.createElement("th");      // TABLE HEADER.
					th.innerHTML = headers[col[i]];
					tr.appendChild(th);
				}
				
				// ADD JSON DATA TO THE TABLE AS ROWS.
				for (i = 0; i < obj.length; i++) {
					tr = table.insertRow(-1);
					for (var j = 0; j < col.length; j++) {
						var tabCell = tr.insertCell(-1);
						tabCell.innerHTML = obj[i][col[j]];
					}
				}
				
				// FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
				var divContainer = document.getElementById("showData");
				divContainer.innerHTML = "";
				divContainer.appendChild(h2);
				divContainer.appendChild(orderText);
				divContainer.appendChild(paymentMethod);
				divContainer.appendChild(table);

				hide_loading_message();
				show_lightbox();
			}
			else {
				hide_loading_message();
				show_message('Information request failed', 'error');
			}
		});
		
		request.fail(function(jqXHR, textStatus){
			hide_loading_message();
			show_message('Information request failed: ' + textStatus, 'error');
		});
	});
	
	// Print order button
  $(document).on('click', '.function_order_print a', function(e){
	  hide_lightbox();
	  var id = $(this).data('id');
	
	  //alert("ORDER LOG ID"+id);
	  e.stopImmediatePropagation();
	  e.preventDefault();
	  
	  show_loading_message();
	  
	  var request = $.ajax({
		  url:          'dataViewOrderLog.php?job=get_product',
		  cache:        false,
		  data:         'id='+id,
		  dataType:     'json',
		  contentType:  'application/json; charset=utf-8',
		  type:         'get'
	  });
	  request.done(function(output){
		  if (output.result == 'success'){
			  var receiptBreakdown = JSON.parse(output.data[0].breakdown);
			  var newJSON = {
				  				"orderID":			output.data[0].orderID,
				  				"orderDate": 		output.data[0].date,				
			  					"subTotal":			output.data[0].sub_total,
			  					"afterTax":			output.data[0].tax_rate,
			  					"discountAmount":	output.data[0].discount_amount,
			  					"grandTotal":		output.data[0].grand_total,
			  					"breakdown":		receiptBreakdown,
			  					"paymentType":		output.data[0].paymentType,
			  					"orderType":		output.data[0].orderType,
				  				"receiptProv":		"No",
				  				"receiptFinal":		"No",
								"tableNum": 		output.data[0].tablenum,
			  					};
			  var newStringJSON = JSON.stringify(newJSON);
			  hide_loading_message();
			  show_message('Duplicate receipt printing successful', 'success');
			  //window.open("createDuplicateReceipt.php?data="+newStringJSON, '_blank');
			  window.open("createpdf.php?data="+newStringJSON, '_blank');
		  }
	  });
	  request.fail(function(jqXHR, textStatus){
		  hide_loading_message();
		  show_message('Information request failed: ' + textStatus, 'error');
	  });
  });
});