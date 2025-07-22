<?php
namespace App\Core;

class Router 
{
    /**
     * Résout la route et appelle le contrôleur approprié
     * @param array $routes
     * @return mixed
     * @throws \Exception
     */
    public static function resolve(array $routes) {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $matchedRoute = null;
        $params = [];

        foreach ($routes as $routeKey => $route) {
            [$routeMethod, $routePath] = explode(' ', $routeKey, 2);
            if ($method !== $routeMethod) continue;

            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                $matchedRoute = $route;
                break;
            }
        }

        if ($matchedRoute) {
            if (!empty($matchedRoute['middlewares'])) {
                $middlewareResult = self::runMiddlewares($matchedRoute['middlewares']);
                if (!$middlewareResult) {
                    throw new \Exception('Accès non autorisé', 401);
                }
            }
            $controllerName = $matchedRoute['controller'];
            $actionName = $matchedRoute['action'];
            if (class_exists($controllerName) && method_exists($controllerName, $actionName)) {
                $controller = new $controllerName();
                $response = call_user_func_array([$controller, $actionName], $params);
                echo $response; // Ajout de l'affichage de la réponse
                return;
            }
        }
        throw new \Exception('Route non trouvée', 404);
    }
    /**
     * Exécute les middlewares pour une route donnée
     * @param array $middlewares
     * @return bool
     */
    private static function runMiddlewares(array $middlewares): bool {
        $middlewareConfig = require_once __DIR__ . '/../config/middlewares.php';
        
        foreach ($middlewares as $middleware) {
            if (isset($middlewareConfig[$middleware])) {
                $middlewareClass = $middlewareConfig[$middleware];
                $instance = new $middlewareClass();
                if (method_exists($instance, 'handle')) {
                    $result = $instance->handle();
                    if ($result !== true) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
