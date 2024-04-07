<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require($_SERVER['DOCUMENT_ROOT'] . "/components/db_config.php");
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

$results = $db->query("SELECT * FROM edges");

$edges = [];
while ($row = $results->fetch_assoc()) {
    $edges[] = $row;
}

echo json_encode($edges);

$db->close();
