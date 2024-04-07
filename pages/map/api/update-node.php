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
