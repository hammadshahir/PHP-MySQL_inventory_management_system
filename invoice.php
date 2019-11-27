<?php 

	// Call the FPDF library

	require_once('fpdf181/fpdf.php');

	// Default margin: 10mm each side

	// Writeable horizontal : 219 - (10*2) = 199mm

	// create fpdf object

	$pdf = new FPDF('p', 'mm', 'A4'); // default page settings

	// String orientation (P or L) - portrait or landscape

	// String unit (pt, mm, cm and in) - measure unit

	// Mixed format (A3, A4, A5, Letter and Legal) - page formates

	// Add new page

	$pdf->AddPage();

	// Set Fonts

	$pdf->setFont('Arial', 'B', '16');

	// Load Data in Cell
	
	$pdf->cell(80, 10, 'Hello World', 0, 0);

	// Output results

	$pdf->Output();

	

?>