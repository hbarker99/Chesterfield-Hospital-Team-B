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
$distance = $params->distance ?? 0;
$direction = $params->direction ?? 0;

if ($start_node_id <= 0 || $end_node_id <= 0) { //Testing
    echo json_encode(["error" => "Invalid input values lala"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Edges (start_node_id, end_node_id, distance, direction) VALUES (:start_node_id, :end_node_id, :distance, :direction)");

$stmt->bindValue(':start_node_id', $start_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':end_node_id', $end_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':distance', $distance, SQLITE3_INTEGER);
$stmt->bindValue(':direction', $direction, SQLITE3_INTEGER);

$stmt->execute();


$stmt = $conn->prepare("INSERT INTO Edges (start_node_id, end_node_id, distance, direction) VALUES (:start_node_id, :end_node_id, :distance, :direction)");

$stmt->bindValue(':start_node_id', $end_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':end_node_id', $start_node_id, SQLITE3_INTEGER);
$stmt->bindValue(':distance', $distance, SQLITE3_INTEGER);
$stmt->bindValue(':direction', (($direction + 2) % 4), SQLITE3_INTEGER);

if ($stmt->execute()) {
    echo json_encode(["id" => $conn->lastInsertRowID()]);
} else {
    echo json_encode(["error" => "Failed to create edge"]);
}

$stmt->close();
$conn->close();
?>
