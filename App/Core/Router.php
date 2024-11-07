<?php

namespace Core;

use Controllers\LayoutController;

/**
 * @class Router
 * @description Router class is responsible for routing the application. It matches the URL to a route and calls the controller and action.
 *
 */
class Router extends AUtility
{

    /**
     * @var string
     * @description Current route name
     */
    private string $currentRoute;

    /**
     * @var Router
     * @description Singleton instance of Router
     */
    private static Router $instance;

    /**
     * @var array $params
     */
    private array $params;

    /**
     * @var Route[]
     */
    private array $routes;

    private function __construct()
    {
        $this->routes = [];
        $this->params = [];
    }

    /**
     * @param string $name
     * @param Route $route
     * @return void
     * @description Adds a route to the router
     */
    public function addRoute(string $name, Route $route)
    {
        $this->routes[$name] = $route;
    }

    /**
     * @return string
     * @description Returns the current route name
     */
    public function getCurrentRoute(): string
    {
        return $this->currentRoute;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @description Returns the route URL with potential parameters
     */
    public function getRoute(string $name, array $params = []): string
    {
        if (!isset($this->routes[$name])) {
            $this->fatal("Route $name does not exist");
        }
        $route = $this->routes[$name];
        return $route->getRoute($params);
    }

    /**
     * @param string $controllerName
     * @param string $action
     * @param array $controllerData
     * @return AController
     * @description Loads a controller and calls an action
     */
    public function loadController(string $controllerName, string $action, array $controllerData = []): AController
    {
        /** @var AController $controller */
        $controller = null;
        $post = $this->secure($_POST);
        $get = $this->secure($_GET);
        $controllerData = array_merge($this->params, $controllerData);
        if (!class_exists($controllerName) || !($controller = new $controllerName($controllerData, $post, $get)) instanceof AController) {
            $this->fatal("Controller $controllerName does not exist", 500);
        }
        if (!method_exists($controller, $action)) {
            $this->fatal("Action $action does not exist in controller $controllerName", 500);
        }

        $controller->$action();
        $controller->loadComponents();

        $this->params = array_merge($this->params, $controller->getBaseData());

        return $controller;
    }

    /**
     * @param string $url
     * @return void
     * @description Loads a page based on the URL
     *  checks if the URL matches a route and loads the controller
     */
    public function loadPage(string $url): void
    {
        require_once __DIR__ . "/../../config/routes.php";
        $parsedUrl = $this->parseUrl($url);

        /** @var Route $route */
        foreach ($this->routes as $name => $route) {
            $params = $route->matchPath($parsedUrl);
            if (!is_array($params)) {
                continue;
            }

            $this->currentRoute = $name;
            $this->params = $params;
            if ($route->isPost()) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->fatal("Route $url is not accessible via GET", 405, 'Stránka nenalezena');
                }
                $this->loadController($route->getControllerName(), $route->getAction());
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->fatal("Route $url is not accessible via POST", 405, 'Stránka nenalezena');
            }
            $baseController = $this->loadController(LayoutController::class, 'index', [
                'content' => [$route->getControllerName() => $route->getAction()],
            ]);
            $baseController->setParams($this->params);
            $baseController->renderView();

            return;
        }
        $this->fatal('Stránka nenalezena', 404, 'true');
    }

    private function parseUrl(string $url): array
    {
        // parsing URL to array
        $parsedURL = parse_url($url);
        // remove first slash
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        // remove whitespaces
        $parsedURL["path"] = trim($parsedURL["path"]);
        // Remove last slash
        $parsedURL["path"] = preg_replace("/\/$/", "", $parsedURL["path"]);
        // Will return array of parameters cut from URL by slashes
        return explode("/", $parsedURL["path"]);
    }

    public static function get(): Router
    {
        if (!isset(self::$instance)) {
            require_once __DIR__ . '/../../config/general.php';
            self::$instance = new Router();
        }
        return self::$instance;
    }

}