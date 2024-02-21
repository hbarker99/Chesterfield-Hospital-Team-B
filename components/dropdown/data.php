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
    $db = new SQLite3("../../database.db");
    $stmt = $db->prepare('SELECT * FROM Node WHERE endpoint=1');
    $result = $stmt->execute();
    $rows_array = [];
    while ($row=$result->fetchArray())
    {
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

