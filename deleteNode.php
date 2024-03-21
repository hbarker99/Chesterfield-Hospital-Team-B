<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$conn = new SQLite3("databasemap.db");

$json = file_get_contents('php://input');
error_log("Raw POST data: $json"); //Testing
$params = json_decode($json);
if (json_last_error() !== JSON_ERROR_NONE) { //Testing
    error_log("JSON decoding error: " . json_last_error_msg());
    echo json_encode(["error" => "JSON decoding error: " . json_last_error_msg()]);
    exit;
}

if (!$params) { 
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

$node_id = $params->node_id;

if ($node_id < 0) {
    echo json_encode(["error" => "Invalid input values lala"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM Node WHERE node_id=:node_id");

$stmt->bindValue(':node_id', $node_id, SQLITE3_INTEGER);

if ($stmt->execute()) {
    echo json_encode("Success");
} else {
    echo json_encode(["error" => "Failed to add node"]);
}


$stmt->close();
$conn->close();
?>
