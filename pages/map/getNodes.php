<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
header('Content-Type: application/json');

$db = new mysqli('localhost', 'root', '', 'chesterfield');

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}    

$results = $db->query("SELECT name, category, x, y, node_id FROM node");

$nodes = [];
while ($row = $results->fetch_assoc()) {

    $nodes[] = $row;
}

echo json_encode($nodes);

$db->close();
?>