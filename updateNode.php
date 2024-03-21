<?php
header('Content-Type: application/json');

$db = new mysqli('localhost', 'root', '', 'chesterfield');

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}    

$json = file_get_contents('php://input');
error_log("Raw POST data: $json"); //Testing

$data = json_decode($json);

$response = [];

if (!empty($data->id) && isset($data->name)) {

    $success = $db->query("UPDATE node SET name = '$data->name' WHERE node_id = $data->id");


    if ($success) {
        $response = ['success' => true, 'message' => 'Node name updated successfully.'];
    } else {
        $response = ['success' => false, 'message' => 'Failed to update node name.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Missing required fields.'];
}


$db->close();

echo json_encode($response);
