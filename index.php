<?php
$requestUrl = isset($_GET['url']) ? $_GET['url'] : '';

$routes = [
    '' => '/pages/route-picker/route-picker.php',
    'route' => '/pages/route-picker/route-picker.php',
    'show-route' => '/pages/route-picker/show/route.php',
    'map' => '/pages/map/map.php',
    'show-pdf' => '/pages/route-picker/pdf/route-pdf.php'
];

if (array_key_exists($requestUrl, $routes)) {
    include $_SERVER['DOCUMENT_ROOT'] . $routes[$requestUrl];
} else {
    http_response_code(404);
    include($_SERVER['DOCUMENT_ROOT'] . "/error.php");
}
?>