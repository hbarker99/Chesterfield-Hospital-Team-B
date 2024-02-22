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

// Image
$imageX = $pdf->GetPageWidth() - 60; // Set X position for the image (right aligned)
$pdf->Image('./PDF/CRHNHS.png', $imageX, 10, 50); // Adjust Y position as needed

// Set Y position for the text (below the title)
$textY = $pdf->GetY() + 5; // You can adjust this offset as needed

// Text
$directionInfo = 'Your directions - ' . $startName . ' to ' . $endName . ':';
$pdf->SetXY(10, $textY); // Set X and Y position for the text (left aligned)
$pdf->MultiCell(140, 10, $directionInfo, 0, 'L');
$pdf->Ln(10);

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