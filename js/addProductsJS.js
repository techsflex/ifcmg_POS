$(document).ready(function(){

  // On page load: datatable
  var table_products = $('#table_products').dataTable({
    "ajax": "data.php?job=get_products",
    "columns": [
                { data: "product_id" },
                { data: "product_name" },
                { data: "product_price" },
                { data: "category" },
                { data: "description" },      
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
  
  // On page load: form validation
  jQuery.validator.setDefaults({
    success: 'valid',
    rules: {
      fiscal_year: {
        required: true,
        min:      2000,
        max:      2025
      }
    },
    errorPlacement: function(error, element){
      error.insertBefore(element);
    },
    highlight: function(element){
      $(element).parent('.field_container').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
    }
  });
  var form_product = $('#form_product');
  form_product.validate();

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
  
  // Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  // Add product button
  $(document).on('click', '#add_product', function(e){
    e.preventDefault();
    $('.lightbox_content h2').text('New Product');
    $('#form_product button').text('Add ');
    $('#form_product').attr('class', 'form add');
    $('#form_product').attr('data-id', '');
    $('#form_product .field_container label.error').hide();
    $('#form_product .field_container').removeClass('valid').removeClass('error');
    $('#form_product #product_id').val('');
    $('#form_product #product_name').val('');
    $('#form_product #product_price').val('');
	  $('#form_product #cat_name').val('None');
    $('#form_product #description').val('');
 
    show_lightbox();
  });

  // Add product submit form
  $(document).on('submit', '#form_product.add', function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    // Validate form
    if (form_product.valid() === true){
      // Send product information to database
      hide_ipad_keyboard();
      hide_lightbox();
      //show_loading_message();
      
      var form_data = $('#form_product').serialize();
      var request   = $.ajax({
        url:          'data.php?job=add_product',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });

      request.done(function(output){
        hide_loading_message();
        show_message(output.message, output.result);
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Add request failed: ' + textStatus, 'error');
      });
    }
  });

  // Find relevant prodcut when edit product button is clicked
  $(document).on('click', '.function_edit a', function(e){
    e.preventDefault();
	  e.stopImmediatePropagation();
    // Get product information from database
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'data.php?job=get_product',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Edit product');
        $('#form_product button').text('Edit product');
        $('#form_product').attr('class', 'form edit');
        $('#form_product').attr('data-id', id);
        $('#form_product .field_container label.error').hide();
        $('#form_product .field_container').removeClass('valid').removeClass('error');
        $('#form_product #product_id').val(output.data[0].product_id);
        $('#form_product #product_name').val(output.data[0].product_name);
        $('#form_product #product_price').val(output.data[0].product_price);
		    $('#form_product #cat_name').val(output.data[0].category);
        $('#form_product #description').val(output.data[0].description);
     
        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message(output.message, output.result);
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Information request failed: ' + textStatus, 'error');
    });
  });
	
  // Edit product submit form
  $(document).on('submit', '#form_product.edit', function(e){
    e.preventDefault();
	  e.stopImmediatePropagation();
    // Validate form
    if (form_product.valid() === true){
      // Send product information to database
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_product').attr('data-id');
      var form_data = $('#form_product').serialize();
      var request   = $.ajax({
        url:          'data.php?job=edit_product&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        hide_loading_message();
        show_message(output.message, output.result);
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed: ' + textStatus, 'error');
      });
    }
  });

  // Delete product
  $(document).on('click', '.function_delete_product a', function(e){
    
     e.preventDefault();
	 e.stopImmediatePropagation();
	 var urlVal='data.php?job=delete_product&id=';
	  
    var product_name = $(this).data('name');
    if (confirm("Are you sure you want to delete '" + product_name + "'?")){
      //show_loading_message();
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
          // Reload datable
			
				 table_products.api().ajax.reload(function(){
            	//hide_loading_message();
            	show_message("Product '" + product_name + "' deleted successfully.", 'success');
          		}, true); 
	      		
		   } else {
          hide_loading_message();
          show_message('Delete request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Delete request failed: ' + textStatus, 'error');
      });
    }
  });

});// JavaScript Document