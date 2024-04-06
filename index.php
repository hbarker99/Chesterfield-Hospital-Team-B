<?php
$requestUrl = isset($_GET['url']) ? $_GET['url'] : '';

$routes = [
    '' => './pages/route-picker/route-picker.php',
    'route' => './pages/route-picker/route-picker.php',
    'map' => './pages/map/map.php',
];

if (array_key_exists($requestUrl, $routes)) {
    include $routes[$requestUrl];
} else {
    http_response_code(404);
    include("./error.php");
}
?>