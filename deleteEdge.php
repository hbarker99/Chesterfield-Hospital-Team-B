<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$db = new mysqli('localhost', 'root', '', 'chesterfield');

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}    

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

$success = $db->query("DELETE FROM edges WHERE start_node_id=$start_node_id AND end_node_id=$end_node_id");
$success = $db->query("DELETE FROM edges WHERE start_node_id=$end_node_id AND end_node_id=$start_node_id");

$db->close();

echo json_encode(["success" => "Noice"]);
?>
