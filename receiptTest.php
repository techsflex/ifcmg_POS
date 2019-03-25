<html>	
<head>
		
</head>
<body>
<?php


  
    $subTotal      = $_POST["subTotal"];
    $afterTax   = $_POST["afterTax"];
    $discountAmount   = $_POST["discountAmount"];
    $grandTotal       = $_POST["grandTotal"];
    $breakdown = $_POST["breakdown"];

/*
    if(isset( $subTotal )){
        $data = array(
        "subTotal"=> $subTotal,
    	"afterTax"=>$afterTax ,
   		"discountAmount"=> $discountAmount,
   		"grandTotal" =>$grandTotal,
		"breakdown" => $breakdown //Array
        );
        echo json_encode($data);
    }


  foreach ($breakdown as $key => $value) {
    echo $value["productCode"] . ", " . $value["productName"] .", ". $value["productPrice"] . ", " . $value["productQuantity"] .", ". $value["productTotal"]. "<br>";
  }


*/


?>
				
<table width="500" cellspacing="5" cellpadding="20">
  <tbody>
    <tr>
     <td>S. No</td>
      <td>Item Name</td>
      <td>Qty</td>
       <td>Sub Total</td>

    </tr>
   <?php
	 $count = 1;
	foreach ($breakdown as $key => $value) {
	echo "<tr>";
	echo "(".$count++;
    echo "<td>".$value["productName"]."</td>";
	echo "<td>".	$value["productPrice"] ."</td>";
	echo "<td>".	(+$value["productPrice"]/+$value["productTotal"] ) ."</td>";
	echo "<td>".	$value["productTotal"]."</td>";
	echo "</tr>";
  }  
	  
	?>

    

  </tbody>
</table>

	</body>
</html>