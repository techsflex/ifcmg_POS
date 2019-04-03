function openDetails(id) {
	"use strict";
	//alert("Button Clicked with ID: " + id);
	
	var request = $.ajax({
		url:	'dataKitchenStatus.php',
		cache:	false,
		data: {
				id:	 id,
			  },
		type:	'post'
	});
	
	request.done(function(output) {
		alert(output.message);
	});
}
