<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
header('Content-Type: application/json');

$conn = new SQLite3("databasemap.db");

$results = $conn->query("SELECT name, category, x, y, node_id FROM Node");

$nodes = [];
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $nodes[] = $row;
}

echo json_encode($nodes);

$conn->close();
?>