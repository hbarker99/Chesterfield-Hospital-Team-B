<?php
function startLocation($mysqli) {
    
    $stmt = $mysqli->prepare('SELECT name FROM Node WHERE node_id=?');
    $stmt->bind_param('i', $_GET['location']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return $data['name'];
}
