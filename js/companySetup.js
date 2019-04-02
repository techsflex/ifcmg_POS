function runAjax(id, val, done) {
	"use strict";
	var request = $.ajax({
		url: 'dataCompanySetup.php',
		type: 'post',
		data: {
			oper: id,
			value: val,
		},
		success: function(output){
			var jsonObj = JSON.parse(output);
			if(jsonObj.result === "success" || jsonObj.result === "error") {
				alert(jsonObj.message);
			}
			else {
				updateCustomerView(jsonObj.data);
			}
		}
	}); //END OF AJAX QUERY
	if (done==="true") {
		request.done(function(){
			$("#main_content").load("companysetup.php");
		});
	}
}

function updateCustomerView(jsonData){
	"use strict";
	if (jsonData.length === 3) {	
		$("#displayCustName").html(jsonData[0]);
		$("#displayCustAddress").html(jsonData[1]);
		$("#displayCustContact").html(jsonData[2]);
	}
	else {
		
		$("#displayCustName").html("No selection");
		$("#displayCustAddress").html("No selection");
		$("#displayCustContact").html("No selection");
	}
}

$(document).ready(function(){
	"use strict";
	$("button").on("click", function(e){
		//alert(this.id);
		e.preventDefault();
		e.stopImmediatePropagation();
		var val = "";
		if (this.id === "gst") {
			val = $("#taxrate").val();
			runAjax(this.id, val, "true");
		}
		
		else if (this.id === "newwaiter") {
			val = $("#newserver").val();
			runAjax(this.id, val, "true");
		}
		
		else if (this.id === "delwaiter") {
			val = $("#removeserver").val();
			runAjax(this.id, val, "true");
		}
		
		else if (this.id === "updateNumTable") {
			val = $("#numtable").val();
			runAjax(this.id, val, "true");
		}
		
		else if (this.id === "delCustomer") {
			val = $("#deletecustomer").val();
			runAjax(this.id, val, "true");
		}
		
		else if (this.id === "viewCustomer") {
			val = $("#deletecustomer").val();
			runAjax(this.id, val, "false");
		}
	}); //END BUTTON CLICK DETECTION
	
	$("#newCustomer").submit(function(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		
		var request = $.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: {
				oper: this.id,
				custname: $('#customerName').val(),
				custaddr: $('#customerAddress').val(),
				custnum: $('#customerPhone').val(),
			},
			success: function(output){
				var message = JSON.parse(output);
				alert(message.message);
			},
			fail: function(){
				alert("AJAX Query Failed!");
			}
		});
		request.done(function(){
			$("#main_content").load("companysetup.php");
		});
	});
});