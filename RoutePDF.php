<?php 
include("sessionHandling.php");
include("mapping-algo.php");
include("dbString.php");

function getStartEndNames($nodeId) {
    $db = new SQLite3(get_string());
    $stmt = $db->prepare('SELECT name FROM Node WHERE node_id=:nodeId');
    $stmt->bindParam(':nodeId', $nodeId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $data = $result->fetchArray(SQLITE3_ASSOC);    
    return $data['name'];
}

$startName = getStartEndNames($startPoint);
$endName = getStartEndNames($endPoint);



ob_clean();
include('./PDF/fpdf186/fpdf.php');
$pdf = new FPDF();

// NHS looking these are you steps page (maybe not needed)
$pdf->AddPage();
$pdf->setFont('Arial' ,'B', 24);
$pdf->Image('./PDF/NHSBlue.jpg', 150, null, 50);
$pdf->Ln();
$pdf->Ln();
$directionInfo = 'Your directions for ' . $startName . ' to ' . $endName . ':';
$pdf->MultiCell(0,10, $directionInfo);

// Loop through each step and add content to the PDF
foreach ($final_path as $index => $step) {
    $directions = 'Step ' . ($index + 1). ': ' . $step['notes'] . ' Now turn ' . $step['direction'] . '.';
    $pdf->SetFont('Arial', '', 14);
    $pdf->MultiCell(0,5,$directions);
    $pdf->Ln();
    //$pdf->Image('./img/' . $step['image'], 55, null, 100);
}

$pdf->Output();