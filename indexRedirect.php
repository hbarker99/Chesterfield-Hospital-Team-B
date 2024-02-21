<?php
session_start();

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
        header("Location: routePDF.php"); // Redirect to your desired page
        exit; // Ensure that no other code is executed after redirection
    } 
}
# route info:
# startPoint -> starting node_id
# endPoint -> ending node_id
# accessibilityCheck -> tickbox for accessibility info