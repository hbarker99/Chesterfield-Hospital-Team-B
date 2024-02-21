<?php 
include("sessionHandling.php");
include("mapping-algo.php");

ob_clean();
include('./PDF/fpdf186/fpdf.php');
$pdf = new FPDF();

// NHS looking these are you steps page (maybe not needed)
$pdf->AddPage();
$pdf->setFont('Arial' ,'B', 24);
$pdf->Image('./PDF/NHSBlue.jpg', 150, null, 50);
$pdf->Cell(0,20,'Hello! Your directions:', 0, 1, 'C');

// Loop through each step and add content to the PDF
foreach ($final_path as $index => $step) {
    $directions = 'Step ' . ($index + 1). ': ' . $step['notes'] . ' Now turn ' . $step['direction'] . '.';
    $pdf->SetFont('Arial', '', 14);
    $pdf->MultiCell(0,10,$directions);
    $pdf->Ln();
    //$pdf->Image('./img/' . $step['image'], 55, null, 100);
}

$pdf->Output();