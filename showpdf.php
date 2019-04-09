<?php
ob_clean();
require_once('fpdf17/pdf_js.php');
require_once('barcode.php');
include('config.php');
session_start();

class PDF_AutoPrint extends PDF_JavaScript
{
    function AutoPrint($printer='')
    {
        // Open the print dialog
        if($printer)
        {
            $printer = str_replace('\\', '\\\\', $printer);
            $script = "var pp = getPrintParams();";
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            $script .= "pp.printerName = '$printer'";
            $script .= "print(pp);";
        }
        else
            $script = 'print(true);';
        $this->IncludeJS($script);
    }
}

date_default_timezone_set('Asia/Karachi');
$date = date('Y-m-d H:i:s');
$id = NULL;
$tempFilename = 'tempBarCode.png';
$companyID = (int)$_SESSION['companyID'];

$afterTax = number_format((float)$_SESSION['afterTax'], 2, '.',',');
$subTotal = number_format((float)$_SESSION['subTotal'], 2, '.',',');
$discountAmount = number_format((float)$_SESSION['discountAmount'], 2, '.',',');
$grandTotal = number_format((float)$_SESSION['grandTotal'], 2, '.',',');
//$grandTotal = $_SESSION['grandTotal'];
$breakdown =  $_SESSION['breakdown'];
$receiptProv = $_SESSION['receiptProv'];
$receiptFinal = $_SESSION['receiptFinal'];
//
$tableNum = $_SESSION['tableNum'];
$orderType = $_SESSION['orderType'];
$paymentType = $_SESSION['paymentType'];
$servername = $_SESSION['serverName'];
$custNum = $_SESSION['custNum'];
	
if ($receiptFinal === "No" && $receiptFinal === "No"){
	$id = $_SESSION['orderID'];
	$datetime = $_SESSION['orderDate'];
}
else {
	$balance = number_format((int)$_SESSION['balance']);
	$cashTendered = number_format((int)$_SESSION['cashTender']);
}

if ($receiptProv === "Yes" && $receiptFinal === "No") {
	$receiptHeader = "PROVISIONAL RECEIPT";
}
else if ($receiptProv === "No" && $receiptFinal === "Yes") {
	$receiptHeader = "ORDER RECEIPT";
	$query = ("SELECT * FROM `orders` WHERE company_companyID='$companyID' ORDER BY `orderID` DESC LIMIT 1");
	
	if ($result=mysqli_query($conn, $query)) {
		 	$id = (mysqli_fetch_row($result)[0]);
	 }
}
else {
	$receiptHeader = "DUPLICATE RECEIPT";
}

$numItems = sizeof($breakdown);


//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$newHeight = ($numItems * 5) + 110;
$pdf = new PDF_AutoPrint('P','mm',array(80,$newHeight));

$pdf->AddPage();

//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',8);

//Cell(width , height , text , border , end line , [align] )

$pdf->Cell(60	,5,'Techsflex Solutions',0,1,'C');

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',7);


$pdf->Cell(10	,5,'',0,0);//end of line
$pdf->Cell(40	,5,'Shop XX XX COMM AREA Karachi',0,1,'C');
$pdf->Cell(60	,5,'Phone: [+12345678]',0,1,'C');

//make a dummy empty cell as a vertical spacer
$pdf->Cell(10, 1, '', 0, 1);//end of line

$pdf->Cell(60	,5,$receiptHeader,0,1,'C');

if ($custNum != "0" || $custNum != "null") {
	$custID = (int)$custNum;
	$query = "SELECT * FROM customer WHERE custID='$custID' AND company_companyID='$companyID'";
	$result = $conn->query($query);
	
	while ($row = $result->fetch_assoc()){
		$pdf->Cell(15	,5,'Customer Name: ',0,0);
		$pdf->Cell(45	,5,$row['custname'],0,1, 'R');
		
		$pdf->Cell(15	,5,'Customer Address: ',0,0);
		$pdf->Cell(45	,5,$row['custaddress'],0,1, 'R');
		
		$pdf->Cell(15	,5,'Customer Phone: ',0,0);
		$pdf->Cell(45	,5,$row['custphone'],0,1, 'R');
	}
	$pdf->Cell(15	,1,'',0,1);
}

if (!is_null($id)) {
	$pdf->Cell(15	,5,'Invoice #',0,0);
	$pdf->Cell(10	,5,$id,0,0);
	
	$pdf->Cell(5, 1, '', 0, 0);	
	$pdf->Cell(30	,5,'Payment Method: ' . $paymentType,0,1,'R');
}
else {
	$pdf->Cell(25	,1,'',0,1);
}

if ($tableNum === "0"){
	$pdf->Cell(15	,5,'Table #',0,0);
	$pdf->Cell(10	,5,'None',0,0);
}
else {
	$pdf->Cell(15	,5, 'Table #',0,0);
	$pdf->Cell(10	,5, $tableNum,0,0);
}

$pdf->Cell(5, 1, '', 0, 0);	
$pdf->Cell(30	,5,'Order Type: ' . $orderType,0,1,'R');

$pdf->Cell(15	,5,'Waiter: ',0,0);
$pdf->Cell(10	,5,$servername,0,1);

if ($receiptProv === "No" && $receiptFinal === "No"){
	$pdf->Cell(60	,5, "Original Order Date: " . $datetime,0,1,'C');
}

$pdf->Cell(60	,0.1, '',1,1,'C');
//invoice contents
$pdf->SetFont('Arial','B',7);

$pdf->Cell(30	,5,'Description',0,0, 'L');
$pdf->Cell(15	,5,'Quantity',0,0, 'C');
$pdf->Cell(15	,5,'Amount',0,1,'R');//end of line

$pdf->SetFont('Arial','',7);
$pdf->Cell(60	,0.1, '',1,1,'C');
//Numbers are right-aligned so we give 'R' after new line parameter


$tax = 0; //total tax
$amount = 0; //total amount

//display the items
foreach ($breakdown as $key => $value) {
	 	$pdf->Cell(30	,5, $value["productName"],0,0, 'L');
	 	$pdf->Cell(15	,5, $value["productQuantity"],0,0, 'C');
	    $pdf->Cell(15	,5, number_format((float)$value["productTotal"], 2, '.',','),0,1,'R');//end of line
  }
$pdf->Cell(60	,0.1, '',1,1,'C');

//summary
$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'Subtotal (PKR)',0,0);
$pdf->Cell(20	,5,$subTotal,0,1,'R');//end of line

$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'Discount (PKR)',0,0);
//$pdf->Cell(5	,5,'PKR',1,0);
$pdf->Cell(20	,5,$discountAmount,0,1,'R');//end of line

$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'GST (PKR)',0,0);
//$pdf->Cell(5	,5,'PKR',1,0);
$pdf->Cell(20	,5,$afterTax,0,1,'R');//end of line

$pdf->Cell(60	,0.1, '',1,1,'C');

if ($receiptFinal === "No" && $receiptFinal === "No"){
	//Do Nothing
}
else {
	$pdf->SetFont('Arial','B',7);
	if ($paymentType === "Cash"){
		$pdf->Cell(15	,5,'',0,0);
		$pdf->Cell(25	,5,'Cash Tendered (PKR)',0,0);
		$pdf->Cell(20	,5,$cashTendered,0,1,'R');//end of line

		$pdf->Cell(15	,5,'',0,0);
		$pdf->Cell(25	,5,'Change (PKR)',0,0);	
		$pdf->Cell(20	,5,$balance,0,1,'R');//end of line
	}
}

$pdf->SetFont('Arial','B',8);

$pdf->Cell(60	,0.1, '',1,1,'C');

$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'Total (PKR)',0,0);
//$pdf->Cell(5	,5,'PKR',1,0);
$pdf->Cell(20	,5,$grandTotal,0,1,'R');//end of line

$pdf->Cell(60	,0.1, '','B',1,'C');

$pdf->SetFont('Arial','',7);

//Detect barcode length if less than 11, resize to 11
$newid = (string)$id;
$newidLength = strlen($newid);

if ($newidLength < 11) {
	$fillers = 11 - $newidLength;
	for ($i = 1; $i <= $fillers ; $i++) {
		$newid = '0' . $newid;
	}
}

//Print barcode
if (!is_null($id)) {
	$pdf->Cell(60, 2, '', 0, 1);//end of line	
	$barcode = barcode($tempFilename,$newid);
	$pdf->Cell(60,6,$pdf->Image($tempFilename, $pdf->GetX()+7,$pdf->GetY()),0,1,'C');
	unlink($tempFilename);
}

//Printed data/time
$pdf->Cell(10	,5,'Printed:',0,0);
$pdf->Cell(10	,5, $date,0,1);//end of line

$conn->close();
//Output to pdf
$pdf->AutoPrint();
$pdf->Output();
?>