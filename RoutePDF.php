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
$pdf->Ln(10);
$pdf->SetTextColor(0,94,184);
$pdf->MultiCell(0,10,'Directions for your visit' , 0, 'L');
$pdf->SetTextColor(0,0,0);
$directionInfo = $startName . ' to ' . $endName . ':';
$pdf->MultiCell(0,10, $directionInfo, 0, 'L');
$pdf->Ln();

// Loop through each step and add content to the PDF
foreach ($final_path as $index => $step) {


    $pdf->SetFont('Arial', 'B', 14);
    if($index == sizeof($final_path)-1){
        $directions = "You have reached " . $endName . ".";
    }
    else{
    $pdf->Cell(0, 10, 'Step ' . ($index + 1), 0, 1,);
    $pdf->SetFont('Arial', '', 14);
    $directions = $step['notes'] . ' ' .  $step['instruction']  ;
    }
    $pdf->MultiCell(0,5,$directions);
    $pdf->Ln();
}
$pdf->Output();