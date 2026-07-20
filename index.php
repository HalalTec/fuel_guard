<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/database.php';

// Autoload controllers (kept explicit for clarity, no Composer required)
require_once __DIR__ . '/app/model/UserModel.php';
require_once __DIR__ . '/app/model/VehicleModel.php';
require_once __DIR__ . '/app/model/RouteModel.php';
require_once __DIR__ . '/app/model/TripModel.php';
require_once __DIR__ . '/app/services/AuthService.php';
require_once __DIR__ . '/app/services/TripAuditService.php';
require_once __DIR__ . '/app/controller/AuthController.php';
require_once __DIR__ . '/app/controller/TripController.php';
require_once __DIR__ . '/app/controller/DashboardController.php';
require_once __DIR__ . '/app/controller/AdminController.php';
require_once __DIR__ . '/app/controller/DriverController.php';

$routes = require __DIR__ . '/app/routes/routes.php';

// Resolve URI relative to this app's base path
$scriptDir  = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri        = trim(substr($requestUri, strlen($scriptDir)), '/');

// Strip query string segment that might bleed through
// strtok returns false on empty string, so coerce back to ''
$uri = strtok($uri, '?') ?: '';

if (array_key_exists($uri, $routes)) {
    [$controllerClass, $method] = $routes[$uri];
    $db         = getDBConnection();
    $controller = new $controllerClass($db);
    $controller->$method();
} else {
    http_response_code(404);
    require __DIR__ . '/app/view/partials/404.php';
}
