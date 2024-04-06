<?php 

$json = file_get_contents('php://input');
error_log("Raw POST data:$json");
$params = json_decode($json);

$_SESSION['start_point'] = $params -> start_point;
$_SESSION['end_point'] = $params -> end_point;
?>