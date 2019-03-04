<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Helpers.php';

$rootDir = __DIR__;

// Set protocol
$httpProtocol = 'http://';

if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
    $httpProtocol = 'https://';
}

// Set http root
$httpRoot = $httpProtocol . $_SERVER['HTTP_HOST'];

// Set http directory
$httpDir  = dirname($_SERVER['SCRIPT_NAME']);

if ($httpDir == DIRECTORY_SEPARATOR) {
    $httpDir = '';
}

// Set path
$path = '';

if (isset($_SERVER['REQUEST_URI'])) {
    $path = urldecode(substr($_SERVER['REQUEST_URI'], strlen($httpDir)));
}

// We must split path from query, fragment, etc.
$path  = parse_url($path, PHP_URL_PATH);
//$query = parse_url($path, PHP_URL_QUERY);


if ($path == '/') {
    $path = '';
}

// Set method
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Load app parts
require_once sanitizeSlash(__DIR__ . '/config/config.php');
require_once sanitizeSlash(__DIR__ . '/lib/Response.php');
require_once sanitizeSlash(__DIR__ . '/src/Router.php');
require_once sanitizeSlash(__DIR__ . '/lib/Translate.php');

// Routing
if ($route = matchRoute($path, $httpMethod)) {

    // Load controller
    require_once __DIR__ . '/src/controllers/' . $route['_controller']
    . '.php';

    // Set action function
    $function = $route['_action'];

    // Set arguments
    if ($route['_args']) {
        $arg = explode('/', $path)[$route['_args']];

        // Get response from controller
        $response = $function($arg);
    } else {
        $response = $function();
    }
} else {
    // 404 :)
    require_once __DIR__ . '/src/controllers/HttpStatusController.php';
    $response = httpStatusAction();
}

// send html response
echo $response;
exit(0);
