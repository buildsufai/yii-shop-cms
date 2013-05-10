<?php
/*
 * TCPDF Commands for creating invoices for CRM
 * These PDFs will use:
 * All text in RGB Black
 */
Yii::import('ext.tcpdf.tcpdf.*');

class MYPDF extends TCPDF {
	public function Header() {
		$this->setJPEGQuality(90);
                $this->Image(Yii::app()->request->hostInfo . Yii::app()->baseUrl.'/images/factuurk.jpg');
                $this->SetY(15);
                $this->Cell(0, 5, 'Fralioshop', 0, false, 'R');
                $this->SetY(20);
                $this->Cell(0, 5, 'Spurkstraat 58', 0, false, 'R');
                $this->SetY(25);
                $this->Cell(0, 5, '5275 JD Den Dungen', 0, false, 'R');
                $this->SetY(30);
                $this->Cell(0, 5, '073 - 851 46 62', 0, false, 'R');
	}
	public function Footer() {
		$this->SetY(-40);
		$this->SetFont(PDF_FONT_NAME_MAIN, '', 8);
		$this->Cell(0, 5, 'Rabobank te Sint-Michielsgestel', 0, false, 'L');
                $this->SetY(-35);
                $this->Cell(0, 5, 'ten name van Fralio-shop', 0, false, 'L');
                $this->SetY(-30);
                $this->Cell(0, 5, 'Rekeningnummer: 1032.77.412', 0, false, 'L');
                $this->SetY(-25);
                $this->Cell(0, 5, 'KvK nummer: 17277993', 0, false, 'L');
                $this->SetY(-20);
                $this->Cell(0, 5, 'Ingeschreven: s\'Hertogenbosch', 0, false, 'L');
                $this->SetY(-15);
                $this->Cell(0, 5, 'Btw nummer: NL822050377B0', 0, false, 'L');
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L', $valign='T') {
		//$this->SetXY($x+20, $y); 
                $x = $x+20; // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->MultiCell($width, $height, $textval, 0, $align, false, 1, $x, $y, true, 0, false, true, 30, $valign);
	}
}

$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8');
//$pdf = Yii::createComponent('ext.tcpdf.ETcPdf', 'P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator("Cloud CRM");
$pdf->SetAuthor(Yii::app()->name);
$pdf->SetTitle("Invoice");
$pdf->SetSubject("Invoice");

// ---------------------------------------------------------

//Back of the page
$pdf->AddPage();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('dejavusans', 'B', 12);

// create address box

// create a PDF object
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
// set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Cloud CRM');
$pdf->SetTitle('Factuur');
$pdf->SetSubject($model->invoiceIdText);
 
// add a page
$pdf->AddPage();
 
// create address box
$lpadding = 100;
$pdf->CreateTextBox('Factuur voor: ', 0, 55, 50, 10, 16);
if(!empty($model->customer->company))
{
    $pdf->CreateTextBox($model->customer->company, $lpadding, 55, 80, 10, 10, 'B');
    $pdf->CreateTextBox($model->customer->name, $lpadding, 60, 80, 10, 10);
}
else
    $pdf->CreateTextBox($model->customer->name, $lpadding, 55, 80, 10, 10, 'B');

$pdf->CreateTextBox($model->customer->address, $lpadding, 65, 80, 10, 10);
$pdf->CreateTextBox($model->customer->postalcode . "  " .  $model->customer->city, $lpadding, 70, 80, 10, 10);
 
// invoice title / number
$pdf->CreateTextBox('Factuur nr: '. $model->invoiceIdText, 0, 90, 120, 10, 11);
 
// date, order ref
$pdf->CreateTextBox('Datum: '.$model->create_date, 0, 90, 0, 10, 11, '', 'R');
$pdf->CreateTextBox('Order ref.: '.$model->id, 0, 95, 0, 10, 11, '', 'R');

// list headers
$pdf->CreateTextBox('Beschrijving', 0, 122, 125, 5, 10, 'B', 'L');
$pdf->CreateTextBox('Aantal', 120, 122, 20, 5, 10, 'B', 'L');
$pdf->CreateTextBox('BTW', 132, 122, 20, 5, 10, 'B', 'L');
$pdf->CreateTextBox('Prijs', 150, 122, 25, 5, 10, 'B', 'R');
 
$pdf->Line(20, 129, 195, 129);
 
$currY = 130;
$total = 0;
foreach ($model->orderDetails as $detail) {
	$pdf->CreateTextBox($detail->sku . " " .$detail->name, 0, $currY, 125, 10, 10, '');
  $pdf->CreateTextBox($detail->getQuantity(), 120, $currY, 20, 10, 10, '');
	$pdf->CreateTextBox($detail->getBtwGroupText(), 132, $currY, 20, 10, 10, '');
	$pdf->CreateTextBox($detail->priceText, 150, $currY, 25, 10, 10, '', 'R');
	$currY = $currY+10;
}
$pdf->Line(20, $currY+4, 195, $currY+4);

// output the total row
$pdf->CreateTextBox('Verzend- en administratiekosten', 20, $currY+5, 125, 10, 10, 'B', 'R');
$pdf->CreateTextBox($model->shippingCostsText, 145, $currY+5, 30, 10, 10, 'B', 'R');

$pdf->CreateTextBox('Totaal excl. BTW', 20, $currY+10, 125, 10, 10, 'B', 'R');
$pdf->CreateTextBox($model->btwAmountText, 145, $currY+10, 30, 10, 10, 'B', 'R');

$pdf->CreateTextBox('BTW bedrag', 20, $currY+15, 125, 10, 10, 'B', 'R');
$pdf->CreateTextBox($model->btwPriceText, 145, $currY+15, 30, 10, 10, 'B', 'R');

$pdf->CreateTextBox('Totaal', 20, $currY+20, 125, 10, 10, 'B', 'R');
$pdf->CreateTextBox($model->totalPriceText, 145, $currY+20, 30, 10, 10, 'B', 'R');
 
// some payment instructions or information
$pdf->setXY(20, $currY+50);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
//$pdf->MultiCell(175, 10, '<em>Lorem ipsum dolor sit amet, consectetur adipiscing elit</em>. <br />Vestibulum sagittis venenatis urna, in pellentesque ipsum pulvinar eu. In nec, eu sagittis diam. Aenean egestas pharetra urna, et tristique metus egestas nec. Aliquam erat volutpat. Fusce pretium dapibus tellus.', 0, 'L', 0, 1, '', '', true, null, true);

if(!isset($outputDest))
	$outputDest = 'I';
if($outputDest == 'S')
	echo $pdf->Output($model->id . '.pdf', $outputDest);
else
	$pdf->Output($model->id . '.pdf', $outputDest);

?>
