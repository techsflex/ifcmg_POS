$(document).ready(function(){
	"use strict";
	
	$("#searchProdCat").on("change", function(){
		if (this.value === "na"){
			$('#searchProdName').val("na"); // Element(s) are now disabled
			$('#searchProdName').prop("disabled", true); // Element(s) are now disabled
		}
		else{
			$('#searchProdName').prop("disabled", false); // Element(s) are now enabled
			var request = $.ajax({
				url: "dataProdHist.php",
				type: "POST",
				data: {
					ID: this.value,
					job: "getProducts",
				},
			});
			request.done(function(prodData){
				var text = JSON.parse(prodData);
				//alert(text);
				var select = document.getElementById('searchProdName');
				while (select.firstChild) {
					select.removeChild(select.firstChild);
				}

				var opt = document.createElement('option');
				opt.value = "na";
				opt.innerHTML = "--Select Product--";
				select.appendChild(opt);

				for (var key in text) {
					if (text.hasOwnProperty(key)) {
						var val = text[key];
						var newopt = document.createElement('option');
						newopt.value = val;
						newopt.innerHTML = key;
						select.appendChild(newopt);
					}
				}
				//alert("Children appended");
			});
		}
	});
	
	
	
	$("#searchProdHist").click(function(){
		if ($("#searchProdName").val() === "na") {
			alert("Please select Category & Name");
		}
		else {
			var ID = $("#searchProdName").val();
			var request = $.ajax({
				url: "dataProdHist.php",
				type: "POST",
				data: {
					ID: ID,
					job: "searchHist",
				},
			});
			request.done(function(dataObj){
				var obj = JSON.parse(dataObj);
				$(".priceHistory").empty();
				var table = document.getElementById("priceHistory");
				var tr = table.insertRow();
				
				if (obj.length === 0){
					var tabCell = tr.insertCell();
					tabCell.innerHTML = "No records found!";
					tabCell.colSpan = 3;
				}
				else {
					
					for (var i=0; i<obj.length; i++){
							tr = table.insertRow();
							var cell = tr.insertCell();
							cell.innerHTML = obj[i].date;
						
							cell = tr.insertCell();
							cell.innerHTML = obj[i].user;
						
							cell = tr.insertCell();
							cell.innerHTML = obj[i].cost;
						
					}
				}
			});
		}
	});
});