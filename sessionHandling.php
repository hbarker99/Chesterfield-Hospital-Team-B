<?php
session_start();

// Check if the session data exists and is not empty
if(isset($_SESSION['routeInfo']) && !empty($_SESSION['routeInfo'])) {
    // Retrieve the POST data stored in the session
    $routeInfo = $_SESSION['routeInfo'];
    
    // Now you can access the POST data as needed
    $startPoint = $routeInfo['startPoint'];
    $endPoint = $routeInfo['endPoint'];
    // Check if the 'accessibilityCheck' key exists in the array before accessing it
    $accessibilityCheck = isset($routeInfo['accessibilityCheck']) ? $routeInfo['accessibilityCheck'] : 'off'; 
     
    // Now you can process the data further or use it as required
    
    // Don't forget to unset or clear the session data if it's no longer needed
    // unset($_SESSION['routeInfo']);
} else {
    // Handle the case where session data is not available
    echo "Session data not found!";
}
