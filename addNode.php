<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$databasePath = __DIR__ . '/databasemap.db'; // Adjust the path as necessary

$conn = new SQLite3($databasePath);

$json = file_get_contents('php://input');
$params = json_decode($json);

// Validate input
if (!$params) {
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

$name = $params->name ?? 'default_name'; // Provide default value or handle error
$category = $params->category ?? 0; // Default category ID
$x = $params->x ?? 0; // Default X coordinate
$y = $params->y ?? 0; // Default Y coordinate

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
