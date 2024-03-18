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

$name = $params->name ?? 'default_name';
$category = $params->category ?? 0; 
$x = $params->x ?? 0; 
$y = $params->y ?? 0; 

error_log("Received name: $name, category: $category, x: $x, y: $y"); // Testing

if ($x <= 0 || $y <= 0 || $category < 0) { //Testing
    echo json_encode(["error" => "Invalid input values lala"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Node (name, category, x, y) VALUES (:name, :category, :x, :y)");

$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':category', $category, SQLITE3_INTEGER);
$stmt->bindValue(':x', $x, SQLITE3_INTEGER);
$stmt->bindValue(':y', $y, SQLITE3_INTEGER);

if ($stmt->execute()) {
    echo json_encode(["id" => $conn->lastInsertRowID()]);
} else {
    echo json_encode(["error" => "Failed to add node"]);
}

$stmt->close();
$conn->close();
?>
