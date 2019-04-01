<?php

include('config.php');
session_start();

date_default_timezone_set('Asia/Karachi');

$subTotal = (float)$_POST["subTotal"];
$afterTax = (float)$_POST["afterTax"];
$discountAmount = (float)$_POST["discountAmount"];
$grandTotal = (float)$_POST["grandTotal"];
$breakdown = ($_POST["breakdown"]);
$paymentType = $_POST["paymentType"];
$orderType = $_POST["orderType"];
$holdOrderId = $_POST["holdOrderId"];
//$breakdownString = JSON.stringify($breakdown);

$orderStatus = $_POST['status'];
$queryStringHoldOrder="N/A";

$message = "status: ";

    if(isset( $subTotal )){

         // ORDER STATUS = 1 MEANS PROCEED WITH ORDER - CONSIDER FINAL
		if($orderStatus==1){
			
			$queryString = "INSERT INTO `orders`( `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `paymentID`, `ordertype`) VALUES ('".$subTotal."','".$afterTax."','".$discountAmount."','".$grandTotal."','".$breakdown."','".$paymentType."','".$orderType."')  "; 
			
			 if (mysqli_query($conn, $queryString)) {
					 $message= $message." Insert into Orders Table: SUCCESSFUL  ";
			 }else{
					 $message= $message." Insert into Orders Table: FAILED.  ";
			}
			
			
			
			
			if($holdOrderId!=-1){
				//If Proceed with order and order was previously on hold, remove from hold orders
				$queryStringHoldOrder = "DELETE FROM `held` WHERE `heldID`='".$holdOrderId."'";
				
				 if (mysqli_query($conn, $queryStringHoldOrder)) {					 
					  $message= $message." Delete from Hold Orders Table: SUCCESSFUL.  ";
				  }else{
					   $message= $message." Delete from Hold Orders Table: FAILED.  ";
				}
				
			} //End if previously held order condition
		} //End Proceed with order condition
		
		
		
		
		
		
		
		
		
		
		
		
			// ORDER STATUS = 2 MEANS ORDER WILL BE UPDATED LATER
		else if($orderStatus==2){
			
			
			   if($holdOrderId!=-1){		   
				  $queryString = "UPDATE `held` SET `subtotal`='".$subTotal."',`taxpaid`='".$afterTax."',`discount`='".$discountAmount."',`grandtotal`='".$grandTotal."',`breakdown`='".$breakdown."'`ordertype`='".$orderType."' WHERE `heldID`=".$holdOrderId.""; 	
				   if (mysqli_query($conn, $queryString)) {					 
					  $message= $message." Update Hold Orders Table: SUCCESSFUL.  ";
				  }else{
					   $message= $message." Update Hold Orders Table: FAILED.  ";
				}
			   }
			   
               						
			    else{
					$queryString = "INSERT INTO `held`( `sub_total`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `ordertype`) VALUES ('".$subTotal."','".$afterTax."','".$discountAmount."','".$grandTotal."','".$breakdown."','".$orderType."')  "; 
					
					if (mysqli_query($conn, $queryString)) {					 
					  $message= $message." Insert Hold Orders Table: SUCCESSFUL.  ";
				  }else{
					   $message= $message." Insert Hold Orders Table: FAILED.  ";
				}
				}
			
		}
		
		
		$data = array(
        		"subTotal"=> $subTotal,
    			"afterTax"=>$afterTax ,
   				"discountAmount"=> $discountAmount,
   				"grandTotal" =>$grandTotal,
			    "status"=> $message,
			    "query" => $queryString,
				"breakdown" => $breakdown,
				"paymentType" => $paymentType,
				"orderType" => $orderType //Array
        	);	
		
		
		
		
		
	
    }
	





$conn->close();
//echo json_encode("AND THE STATUS RECEIVED IS ".$orderStatus);
echo json_encode($data);







/*
$string= $string. "<table width=\"500\" cellspacing=\"5\" cellpadding=\"20\"><tbody> <tr><td>S. No</td><td>Item Name</td><td>Qty</td><td>Sub Total</td></tr>";
   
	 $count = 1;
	foreach ($breakdown as $key => $value) {
	$string= $string."<tr>";
	$string= $string."<td>". $count++."  </td>";
    $string= $string."<td>".$value["productName"]."  </td>";
	$string= $string."<td>".(+$value["productTotal"]/+$value["productPrice"])."</td>";
	$string= $string ."<td>".	$value["productTotal"]."</td>";
	$string= $string."</tr>";
	
  }  


$string= $string. "  </tbody></table></div></body></html>";
echo json_encode($string);






  foreach ($breakdown as $key => $value) {
    $string = $string."".$value["productCode"] . ", " . $value["productName"] .", ". $value["productPrice"] . ", " . $value["productQuantity"] .", ". $value["productTotal"]. "<br>";
  }


if(file_put_contents($fileName,$string)){
	echo json_encode($string);

}
else{
	echo "ERROR";
}
*/

	?>

    

