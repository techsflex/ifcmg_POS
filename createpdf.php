<?php
session_start();

$allData =  json_decode(($_GET['data']), true);
$_SESSION['afterTax'] = $allData['afterTax'];
$_SESSION['subTotal']= $allData['subTotal'];
$_SESSION['discountAmount'] = $allData['discountAmount'];
$_SESSION['grandTotal'] = $allData['grandTotal'];
$_SESSION['breakdown'] =  ($allData['breakdown']);
$_SESSION['receiptProv'] = $allData['receiptProv'];
$_SESSION['receiptFinal'] = $allData['receiptFinal'];
$_SESSION['tableNum'] = $allData['tableNum'];
$_SESSION['orderType'] = $allData['orderType'];
$_SESSION['paymentType'] = $allData['paymentType'];
$_SESSION['serverName'] = $allData['serverName'];

if ($allData['receiptFinal'] === "No" && $allData['receiptFinal'] === "No"){
	$_SESSION['orderID'] = $allData['orderID'];
	$_SESSION['orderDate'] = $allData['orderDate'];
}
else {
	$_SESSION['balance'] = $allData['balance'];
	$_SESSION['cashTender'] = $allData['cashTender'];
}

header ("Location: showpdf.php");
?>