<?php
session_start();
function fetchData() {
    if (isset($_SESSION['location_data'])) {
        return $_SESSION['location_data'];
    } else {
        $data = locationFill();
        $_SESSION['location_data'] = $data;
        return $data;
    }
}

function locationFill(){
    $host = 'localhost'; // Replace with hosted server
    $username = 'root';
    $password = '';
    $database = 'arundel';
    // Create a connection
    $mysqli = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($mysqli->connect_error) {
        die('Connection failed: ' . $mysqli->connect_error);
    }

    $query = 'SELECT * FROM node WHERE category = 5 or category = 2';
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rows_array[]=$row;
    }
    return $rows_array;
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $data = fetchData();
    $filteredData = array_filter($data, function($item) use ($search) {
        return stripos($item['name'], $search) !== false;
    });
    echo json_encode(array_values($filteredData));
    
}
error_log('Unexpected case reached in data.php');
?>

