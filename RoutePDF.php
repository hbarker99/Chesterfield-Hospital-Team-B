<?php 
include("sessionHandling.php");
include("mapping-algo.php");

ob_clean();
include('./PDF/fpdf186/fpdf.php');
$pdf = new FPDF();

// Loop through each step and add content to the PDF
foreach ($final_path as $index => $step) {
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 18);
    $pdf->Cell(0, 10, 'Step ' . ($index + 1), 0, 1, 'C');
    $pdf->Cell(0, 10, 'Turn ' . $step['direction'], 0, 1, 'C');
    $pdf->Ln();
    $pdf->Image('./img/' . $step['image'], 55, null, 100);
}

$pdf->Output();