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
	// On page load datatable for Held Orders
	var table_hold_order = $('#table_hold_order').dataTable({
		"ajax": "dataViewHoldOrderLog.php?job=get_products",
		"columns": [
			{ data: "ID" },
			{ data: "date" },
			{ data: "type" },
			{ data: "total" },
			{ data: "kitchen" },      
			{ data: "functions" }
		],
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
	
	// Delete product
	$(document).on('click', '.function_delete_hold_order a', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var urlVal = 'dataViewHoldOrderLog.php?job=delete_product&id=';  
		var product_name = $(this).data('name');
		
		if (confirm("Are you sure you want to delete on-hold ID: '" + product_name + "'?")){
			show_loading_message();
			var id      = $(this).data('id');
			var request = $.ajax({
				url:          urlVal + id,
				cache:        false,
				dataType:     'json',
				contentType:  'application/json; charset=utf-8',
				type:         'get'
			});
			
			request.done(function(output){
				if (output.result === 'success'){
					//alert(output.result);
					$("#main_content").load("viewHoldOrderLog.php");
					hide_loading_message();
					show_message(output.message, output.result);
				}
				else{
					hide_loading_message();
					show_message(output.message, output.result);
				}
			});
			
			request.fail(function(jqXHR, textStatus){
				hide_loading_message();
				show_message('Delete request failed: ' + textStatus, 'error');
			});
		}
	});
	
	// Edit hold Order button
	$(document).on('click', '.function_hold_order_edit a', function(){
		var id      = $(this).data('id'); 
		$("#main_content").load("newOrder.php?id="+id);
	});
});