<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
header('Content-Type: application/json');
require("../../../components/db_config.php");
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}
$results = $db->query("SELECT name, category, x, y, node_id FROM node");

$nodes = [];
while ($row = $results->fetch_assoc()) {

    $nodes[] = $row;
}

echo json_encode($nodes);

$db->close();
?>