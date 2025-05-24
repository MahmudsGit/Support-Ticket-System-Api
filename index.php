<?php
require_once 'routes/api.php';

function runMiddleware($middlewares, $handler, ...$params) {
    foreach ($middlewares as $middleware) {
        $result = $middleware();
        if ($result === false) {
            // Middleware handled response (e.g., sent 401), stop execution
            return;
        }
    }
    call_user_func_array($handler, $params);
}

// Your routing logic follows...
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

$routeMatched = false;

foreach ($routes as $route) {
    if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
        array_shift($matches); // Remove full match

        if (isset($route['middleware'])) {
            runMiddleware($route['middleware'], $route['handler'], ...$matches);
        } else {
            call_user_func_array($route['handler'], $matches);
        }

        $routeMatched = true;
        break;
    }
}

if (!$routeMatched) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}