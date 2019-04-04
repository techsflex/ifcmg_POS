function createButton(id, table){
	"use strict";
	var $input = $('<input type="button" class="btn btn-success" value="Order Ready" id="' + table + "-" + id +'"/>');
    $input.appendTo($(".processKitchen"));
}

function openDetails(id) {
	"use strict";
	//alert("Button Clicked with ID: " + id);
	
	var request = $.ajax({
		url:	'dataKitchenStatus.php',
		cache:	false,
		data:   {
			id: id,
			job: "view",
		},
		type:	'post'
	});
	
	request.done(function(output) {
		$(".kitchenOrderType").empty();
		$(".kitchenOrderDetail").empty();
		$(".processKitchen").empty();
		
		output = JSON.parse(output);
		//alert(output.data[0].breakdown);
		//Extract Order Type and Store in element h4
		var orderText = document.createElement("h4");
		var orderType = output.data[0].ordertype;
		orderText.textContent = "Order Type: " + orderType;
		
		//Extract breakdown
		var obj = JSON.parse(output.data[0].breakdown);
		
		//Create Dynamic Table
		var table = document.createElement("table");
		table.className = "viewClass";
		// CREATE HTML TABLE HEADER ROW
		var tr = table.insertRow();
		var col = ["Product Name", "Qty"];
		for (var i = 0; i < col.length; i++) {
			var th = document.createElement("th");      // TABLE HEADER.
			th.innerHTML = col[i];
			tr.appendChild(th);
		}
		
		for (i = 0; i < obj.length; i++) {
			tr = table.insertRow();
			var tabCell = tr.insertCell();
			tabCell.innerHTML = obj[i].productName;
			var newCell = tr.insertCell();
			newCell.innerHTML = obj[i].productQuantity;
		}
		
		$(".kitchenOrderType").html(orderText);
		$(".kitchenOrderDetail").html(table);
		createButton(output.data[0].id, output.data[0].table);
	});
}

$(document).ready(function(){
	"use strict";
	$(document).on("click", "input", function(e){		
		e.preventDefault();
		e.stopImmediatePropagation();
		//alert(this.id);
		var request = $.ajax({
			url:	'dataKitchenStatus.php',
			cache:	false,
			data: {
				id:	 this.id,
				job: "done",
			},
			type:	'post'
		});
		request.done(function(){
			$("#main_content").load("kitchenstatus.php");
		});
	});
	
	setInterval(function(){
		if(document.getElementById('noOrders')){
		   $("#main_content").load("kitchenstatus.php");
		   }
	}, 15000);
	
});
