<head>
	<title>Your PDF Route</title>
</head>
<?php 
include($_SERVER['DOCUMENT_ROOT'] . "/pdf/fpdf186/fpdf.php");
include($_SERVER['DOCUMENT_ROOT'] . "/pages/route-picker/session-handling.php");
include($_SERVER['DOCUMENT_ROOT'] . "/pages/route-picker/api/mapping-algo.php");
require($_SERVER['DOCUMENT_ROOT'] . "/components/db_config.php");

// Function to get the names of the start node and end node
function getStartEndNames($nodeId) {
    $db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }
    
    $result = $db->query('SELECT name FROM Node WHERE node_id='.$nodeId);
    $data = $result->fetch_array();    
    return $data['name'];
}
$startPoint = $_SESSION['start_point'];
$endPoint = $_SESSION['end_point'];

$startName = getStartEndNames($startPoint);
$endName = getStartEndNames($endPoint);

// Creating PDF instance
ob_clean();
$pdf = new FPDF();
$pdf->AddPage();
$pdf->setFont('Arial' ,'', 22);

// NHS logo and Directions header
$pdf->Image($_SERVER['DOCUMENT_ROOT'] . '/pdf/CRHNHS.png', 155, null, 50);
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
        $directions = $step['notes'] . ' ' .  $step['instruction'];
        // Replace HTML <b> tag with FPDF's bold formatting
        $directions = str_replace('<b>', '', $directions);
        $directions = str_replace('</b>', '', $directions);
    }

    if($accessibilityCheck == 'on'){
        $access = $step['accessibility_notes'];
    }
    else{
        $access = '';
    }
    $pdf->MultiCell(0,5,$directions .  ' '. $access);
    $pdf->Ln();
}
$pdf->Output();