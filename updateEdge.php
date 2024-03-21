<?php
header('Content-Type: application/json');

$conn = new SQLite3("databasemap.db");

$json = file_get_contents('php://input');
error_log("Raw POST data: $json"); //Testing

$data = json_decode($json);

$response = [];

var_dump($data);

if (!empty($data->edge_id) && isset($data->image_name)) {

    $stmt = $conn->prepare("UPDATE Edges SET image = :image_name WHERE edge_id = :edge_id");

 
    $stmt->bindValue(':edge_id', $data->edge_id, SQLITE3_INTEGER);
    $stmt->bindValue(':image_name', $data->image_name, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Edge updated successfully.'];
    } else {
        $response = ['error' => false, 'message' => 'Failed to update edge.'];
    }
} else {
    $response = ['error' => false, 'message' => 'Missing required fields.'];
}


$conn->close();

echo json_encode($response);
