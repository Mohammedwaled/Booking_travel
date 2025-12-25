<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Set error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<div style='background: #ffebee; padding: 20px; margin: 20px; border: 2px solid #f44336; border-radius: 5px;'>";
    echo "<h3 style='color: #d32f2f;'>PHP Error:</h3>";
    echo "<p><strong>Error:</strong> $errstr</p>";
    echo "<p><strong>File:</strong> $errfile</p>";
    echo "<p><strong>Line:</strong> $errline</p>";
    echo "</div>";
    return true;
});

try {
    require_once __DIR__ . '/../core/Router.php';
} catch (Throwable $e) {
    echo "<div style='background: #ffebee; padding: 20px; margin: 20px; border: 2px solid #f44336; border-radius: 5px;'>";
    echo "<h3 style='color: #d32f2f;'>Fatal Error Loading Router:</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
    exit;
}

session_start();

$router = new Router();

// Routes
$router->addRoute('POST', '/auth/register', 'AuthController', 'register');
$router->addRoute('POST', '/auth/login', 'AuthController', 'login');
$router->addRoute('GET', '/auth/logout', 'AuthController', 'logout');

$router->addRoute('POST', '/bookings', 'BookingController', 'store');

$router->addRoute('GET', '/api/packages', 'PackageController', 'index');
$router->addRoute('GET', '/api/packages/{id}', 'PackageController', 'show');

$router->addRoute('GET', '/api/cities/{id}', 'CityController', 'show');
$router->addRoute('GET', '/api/search', 'CityController', 'search');

$router->addRoute('POST', '/admin/packages', 'AdminController', 'addPackage');
$router->addRoute('GET', '/admin/packages/{id}/{action}', 'AdminController', 'approvePackage');

$router->addRoute('POST', '/contact', 'ContactController', 'store');

$router->dispatch();

