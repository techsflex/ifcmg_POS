<?php
//Setup headers and classes
ob_clean();
$getData =  json_decode($_GET['data'], true);

//Import all values
$orderID 		= $getData['orderID'];
$orderDate 		= $getData['orderDate'];
$breakdown 		= ($getData['breakdown']);
$paymentType 	= $getData['paymentType'];
$orderType 		= $getData['orderType'];
$afterTax 		= number_format((float)$getData['taxRate'], 2, '.',',');
$subTotal 		= number_format((float)$getData['subTotal'], 2, '.',',');
$discountAmount = number_format((float)$getData['discount'], 2, '.',',');
$grandTotal 	= number_format((float)$getData['grandTotal'], 0, '.',',');
				
require_once('fpdf17/pdf_js.php');
require_once('barcode.php');

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

//Date parameters
date_default_timezone_set('Asia/Karachi');
$date = date('Y-m-d H:i:s');

//Temporary file for barcode printing
$tempFilename = 'tempBarCode.png';

//Receipt Header
$receiptHeader = "DUPLICATE RECEIPT";

//Number of items in breakdown
$numItems = sizeof($breakdown);



//Start printing mechanism
////////////////////////////////
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


$pdf->Cell(15	,5,'Invoice #',0,0);
$pdf->Cell(10	,5,$orderID,0,1);//end of line

$pdf->Cell(15	,5,'Order Date: ',0,0, 'L');
$pdf->Cell(10	,5,$orderDate,0,1,'L');//end of line

$pdf->Cell(15	,5,'Order Type: ',0,0,'L');
$pdf->Cell(10	,5,$orderType,0,1,'L');//end of line

$pdf->Cell(15	,5,'Payment: ',0,0,'L');
$pdf->Cell(10	,5,$paymentType,0,1,'L');//end of line

$pdf->Cell(60	,0.1, '',1,1,'C');

//invoice contents
$pdf->SetFont('Arial','B',7);

$pdf->Cell(30	,5,'Description',0,0, 'L');
$pdf->Cell(15	,5,'Quantity',0,0, 'C');
$pdf->Cell(15	,5,'Amount',0,1,'R');//end of line

$pdf->SetFont('Arial','',7);
$pdf->Cell(60	,0.1, '',1,1,'C');

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
$pdf->Cell(20	,5,$discountAmount,0,1,'R');//end of line

$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'Tax Rate (%)',0,0);
$pdf->Cell(20	,5,$afterTax,0,1,'R');//end of line

$pdf->Cell(60	,0.1, '',1,1,'C');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(15	,5,'',0,0);
$pdf->Cell(25	,5,'Total Due (PKR)',0,0);
$pdf->Cell(20	,5,$grandTotal,0,1,'R');//end of line
$pdf->Cell(60	,0.1, '',1,1,'C');

$pdf->SetFont('Arial','',7);

//Detect barcode length if less than 11, resize to 11
$newid = (string)$orderID;
$newidLength = strlen($newid);

if ($newidLength < 11) {
	$fillers = 11 - $newidLength;
	for ($i = 1; $i <= $fillers ; $i++) {
		$newid = '0' . $newid;
	}
}

//Print barcode
$pdf->Cell(60, 2, '', 0, 1);//end of line	
$barcode = barcode($tempFilename,$newid);
$pdf->Cell(60,6,$pdf->Image($tempFilename, $pdf->GetX()+7,$pdf->GetY()),0,1,'C');
unlink($tempFilename);

//Printed data/time
$pdf->Cell(10	,5,'Printed:',0,0);
$pdf->Cell(10	,5, $date,0,1);//end of line

//Output to pdf
$pdf->AutoPrint();
$pdf->Output();
?>