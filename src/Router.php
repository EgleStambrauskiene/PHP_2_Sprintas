<?php
function matchRoute($path, $httpMethod)
{
    require_once 'routes.php';

    if ($path == '') {
        $path = '/';
    }

    foreach ($routes[$httpMethod] as $index => $route) {
        $pattern = '/' . str_replace('/', '\/', $route['_pattern']) . '/';
        if (preg_match($pattern, $path, $matches)) {
            if ($matches[0] == $path) {
                return $route;
                break;
            }
        }
    }
    return false;
}
