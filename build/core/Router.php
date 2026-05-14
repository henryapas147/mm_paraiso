<?php

class Router
{
    private $routes = [];
    private $params = [];

    public function add($route, $params = [])
    {
        // Convert route to regex: /properties-rooms/properties -> #^properties-rooms/properties$#
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            // Check if this is a direct file route (for OAuth callbacks, etc.)
            if (isset($this->params['file'])) {
                require_once __DIR__ . '/../' . $this->params['file'];
                return;
            }

            // RBAC Check - skip for auth routes
            if (!in_array($url, ['sign-in', 'sign-up', 'logout', 'get-refresh-token', 'build/google-callback', 'verify-email', 'build/verify-email', 'customer/dashboard', 'customer/reservations', 'customer/payments', 'customer/profile'])) {
                require_once __DIR__ . '/RBAC.php';
                \App\Core\RBAC::requireLogin();

                // Check route access
                if (!\App\Core\RBAC::canAccessRoute($url)) {
                    \App\Core\RBAC::unauthorized();
                }
            }

            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;
            if (getenv('APP_DEBUG') === 'true') {
                error_log("Router: Full controller class name: $controller");
            }

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (method_exists($controller_object, $action)) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action in controller $controller not found");
                }
            } else {
                throw new \Exception("Controller $controller not found");
            }
        } else {
            throw new \Exception("No route matched for URL: $url");
        }
    }

    private function match($url)
    {
        $debug = getenv('APP_DEBUG') === 'true';
        if ($debug) {
            error_log("Router Match - Trying to match URL: '$url'");
            error_log("Router Match - Number of routes: " . count($this->routes));
        }
        foreach ($this->routes as $route => $params) {
            if ($debug) error_log("Router Match - Checking route pattern: '$route' against URL: '$url'");
            if (preg_match($route, $url, $matches)) {
                if ($debug) error_log("Router Match - Route matched!");
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    private function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    private function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    private function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    private function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
