<?php 
include('./PDF/fpdf186/fpdf.php');
include("sessionHandling.php");
include("mapping-algo.php");
include("dbString.php");

// Function to get the names of the start node and end node
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

// Creating PDF instance
ob_clean();
$pdf = new FPDF();
$pdf->AddPage();
$pdf->setFont('Arial' ,'', 22);

// NHS logo and Directions header
//$pdf->Image('./PDF/NHSBlue.jpg', 155, null, 50);
$pdf->Image('./PDF/CRHNHS.png', 155, null, 50);
//$pdf->Ln(10);
$directionInfo = 'Your directions for ' . $startName . ' to ' . $endName . ':';
$pdf->MultiCell(0,10, $directionInfo, 0, 'C');
$pdf->Ln();

// Loop through each step and add content to the PDF
foreach ($final_path as $index => $step) {
    if ($step['direction'] == 'left' || $step['direction'] == 'right'){
        $movementDescriptor = ' Turn';
    } else if ($step['direction'] == 'upstairs' || $step['direction'] == 'downstairs') {
        $movementDescriptor = ' Use the stairs/lift to continue';
    } 

    // Step #: *Edge notes* . Direction change . Direction
    // Step 1: You will see... Turn right. 
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Step ' . ($index + 1), 0, 1,);
    $pdf->SetFont('Arial', '', 14);
    $directions = $step['notes'] . $movementDescriptor . ' ' .  $step['direction'] . '.';
    $pdf->MultiCell(0,5,$directions);
    $pdf->Ln();
}
$pdf->Output();