<?php
header('Content-Type: application/json');

$conn = new SQLite3("databasemap.db");

$json = file_get_contents('php://input');
error_log("Raw POST data: $json"); //Testing

$data = json_decode($json);

$response = [];

if (!empty($data->id) && isset($data->name)) {

    $stmt = $conn->prepare("UPDATE Node SET name = :name WHERE node_id = :id");

 
    $stmt->bindValue(':id', $data->id, SQLITE3_INTEGER);
    $stmt->bindValue(':name', $data->name, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Node name updated successfully.'];
    } else {
        $response = ['success' => false, 'message' => 'Failed to update node name.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Missing required fields.'];
}


$conn->close();

echo json_encode($response);
