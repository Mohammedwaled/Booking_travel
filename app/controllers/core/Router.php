<?php

// Database ليس مطلوباً هنا - Models تستدعيه
require_once __DIR__ . '/BaseController.php';

class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // معالجة خاصة للـ POST requests من النماذج (قبل معالجة URI)
        if ($method === 'POST') {
            // التحقق من وجود أزرار خاصة في POST
            if (isset($_POST['register_btn'])) {
                $uri = '/auth/register';
            } elseif (isset($_POST['login_btn'])) {
                $uri = '/auth/login';
            } elseif (isset($_POST['confirm_booking'])) {
                $uri = '/bookings';
            } elseif (isset($_POST['add_package_btn'])) {
                $uri = '/admin/packages';
            } elseif (isset($_POST['submit_contact'])) {
                $uri = '/contact';
            } else {
                // إذا لم يكن هناك زر خاص، استخدم URI العادي
                $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
                // إزالة basePath من URI
                if ($basePath !== '/' && $basePath !== '\\' && strpos($uri, $basePath) === 0) {
                    $uri = substr($uri, strlen($basePath));
                }
                $uri = rtrim($uri, '/') ?: '/';
            }
        } else {
            // GET requests
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            // إزالة basePath من URI
            if ($basePath !== '/' && $basePath !== '\\' && strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
            }
            $uri = rtrim($uri, '/') ?: '/';
            
            // إضافة query string للبحث
            if (isset($_GET['q']) && $uri === '/api/search') {
                // URI صحيح بالفعل
            }
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // إزالة الـ full match
                
                $controllerName = $route['controller'];
                $action = $route['action'];
                
                $controllerPath = __DIR__ . "/../{$controllerName}.php";
                
                if (!file_exists($controllerPath)) {
                    http_response_code(500);
                    echo "Error: Controller file not found: $controllerPath<br>";
                    echo "Looking for: {$controllerName}.php in " . __DIR__ . "/../<br>";
                    return;
                }
                
                try {
                    require_once $controllerPath;
                } catch (Throwable $e) {
                    http_response_code(500);
                    echo "Error loading controller: " . $e->getMessage() . "<br>";
                    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
                    return;
                }
                
                try {
                    $controller = new $controllerName();
                } catch (Throwable $e) {
                    http_response_code(500);
                    echo "Error instantiating controller: " . $e->getMessage() . "<br>";
                    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
                    return;
                }
                
                if (method_exists($controller, $action)) {
                    try {
                        call_user_func_array([$controller, $action], $matches);
                        return;
                    } catch (Throwable $e) {
                        http_response_code(500);
                        echo "Error executing action: " . $e->getMessage() . "<br>";
                        echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
                        echo "<pre>" . $e->getTraceAsString() . "</pre>";
                        return;
                    }
                } else {
                    http_response_code(500);
                    echo "Error: Method '$action' not found in controller '$controllerName'<br>";
                    return;
                }
            }
        }

        // إذا لم يتم العثور على route
        http_response_code(404);
        echo "404 - الصفحة غير موجودة<br>";
        echo "Method: $method<br>";
        echo "URI: " . (isset($uri) ? $uri : 'undefined') . "<br>";
        echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'undefined') . "<br>";
        echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'undefined') . "<br>";
        echo "Available routes:<br>";
        foreach ($this->routes as $route) {
            echo "- {$route['method']} {$route['path']} -> {$route['controller']}::{$route['action']}<br>";
        }
    }

    private function convertToRegex($path) {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}

