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

if (!$params) {  //Testing
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

$start_node_id = $params->start_node_id;
$end_node_id = $params->end_node_id;

if ($start_node_id < 0 || $end_node_id < 0) { //Testing
    echo json_encode(["error" => "Invalid input values lala"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM Edges WHERE start_node_id=:start_node_id AND end_node_id=:end_node_id");

$stmt->bindValue(':start_node_id', $start_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':end_node_id', $end_node_id, SQLITE3_INTEGER);

$stmt->execute();


$stmt = $conn->prepare("DELETE FROM Edges WHERE start_node_id=:end_node_id AND end_node_id=:start_node_id");

$stmt->bindValue(':start_node_id', $start_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':end_node_id', $end_node_id, SQLITE3_INTEGER);

$stmt->execute();

$stmt->close();
$conn->close();

echo json_encode(["success" => "Noice"]);
?>
