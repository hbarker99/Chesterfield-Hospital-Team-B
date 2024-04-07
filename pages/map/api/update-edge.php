<?php
header('Content-Type: application/json');

require("../../../components/db_config.php");
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

$json = file_get_contents('php://input');
error_log("Raw POST data: $json"); //Testing

$data = json_decode($json);

$response = [];

var_dump($data);
$edge_id = $data->edge_id;
$image_name = $data->image_name;

if (!empty($edge_id) && isset($image_name)) {

    $success = $db->query("UPDATE Edges SET image = '$image_name' WHERE edge_id = $edge_id");


    if ($success) {
        $response = ['success' => true, 'message' => 'Edge updated successfully.'];
    } else {
        $response = ['error' => false, 'message' => 'Failed to update edge.'];
    }
} else {
    $response = ['error' => false, 'message' => 'Missing required fields.'];
}


$db->close();

echo json_encode($response);
