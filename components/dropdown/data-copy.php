<?php


function locationFill(){
    $host = 'localhost'; // Replace with hosted server
    $username = 'root';
    $password = '1234';
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
$nodes = locationFill();
$json_nodes = json_encode($nodes, JSON_PRETTY_PRINT);

echo $json_nodes;
?>

