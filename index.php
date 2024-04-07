<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/user.php');
$requestUrl = isset($_GET['url']) ? $_GET['url'] : '';


$routes = [
    '' => '/pages/route-picker/route-picker.php',
    'route' => '/pages/route-picker/route-picker.php',
    'show-route' => '/pages/route-picker/show/route.php',
    'map' => '/pages/map/map.php',
    'admin' => '/pages/login/login.php',
    'show-pdf' => '/pages/route-picker/pdf/route-pdf.php'
];

$requiresAuth = [
    'map' => TRUE,
];

if (array_key_exists($requestUrl, $routes)) {
    if (array_key_exists($requestUrl, $requiresAuth) && $_SESSION['user'] == NULL) {
        header("Location: /admin");
        exit;
    }

    include $_SERVER['DOCUMENT_ROOT'] . $routes[$requestUrl];
} else {
    http_response_code(404);
    include($_SERVER['DOCUMENT_ROOT'] . '/error.php');
}
?>