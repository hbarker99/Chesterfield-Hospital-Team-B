<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$conn = new SQLite3("databasemap.db");

$results = $conn->query("SELECT * FROM edges");

$edges = [];
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $edges[] = $row;
}

echo json_encode($edges);

$conn->close();
