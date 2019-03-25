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
header ("Location: showpdf.php");
?>