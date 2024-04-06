<?php
session_start();



$json = file_get_contents('php://input');
error_log("Raw POST data:$json");
$params = json_decode($json);

$_SESSION['start_point'] = $_POST['startPoint'];
$_SESSION['end_point'] = $_POST['endPoint'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['getRoute'])) {
        // Store POST data in session
        $_SESSION['routeInfo'] = $_POST;
        header("Location: route.php"); // Redirect to your desired page
        exit; // Ensure that no other code is executed after redirection
    }
    if (isset($_POST['getRoutePDF'])) {
        // Store POST data in session
        $_SESSION['routeInfo'] = $_POST;
        header("Location: route-pdf.php"); // Redirect to your desired page
        exit; // Ensure that no other code is executed after redirection
    } 
}
# route info:
# startPoint -> starting node_id
# endPoint -> ending node_id
# accessibilityCheck -> tickbox for accessibility info