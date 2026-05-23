<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method = 'index') {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($url) {
        $url = trim($url, '/');
        $url = ($url === '') ? '/' : $url;

        if (array_key_exists($url, $this->routes)) {
            $route = $this->routes[$url];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            require_once "app/controllers/{$controllerName}.php";
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
            } else {
                die("Method '$methodName' not found in $controllerName");
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Page not found.";
        }
    }
}