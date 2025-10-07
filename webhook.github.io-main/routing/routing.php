<?php
define('ROOT_DIR', dirname(__DIR__));
require_once 'routes.php';
require_once ROOT_DIR . '/backend/' . 'utility.php';

$request = explode("?", $_SERVER['REQUEST_URI'])[0];
$request = trim($request, "/");

$filePath = resolveRoute($request);

if ($filePath)
{
    require ROOT_DIR . '/' . $filePath;
}
else
{
    echo "Routing error";
}

function resolveRoute($routeString)
{
    global $routes;

    if (isset($routes[$routeString]))
    {
        return $routes[$routeString];
    }

    killscreen404($routeString);
}

function killscreen404($routeString)
{
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    http_response_code(404);
    echo "Route " . $routeString . " not found.";
    die();

}
