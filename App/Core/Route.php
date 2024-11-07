<?php

namespace Core;

class Route extends AUtility
{

    private string $action;

    private string $controller;

    private array $params;

    private array $paths;

    private bool $isPost;

    private function __construct(string $path, string $controller, string $action, bool $post = false)
    {
        $this->isPost = $post;
        $this->controller = $controller;
        $this->action = $action;

        // Split path into parts
        $this->paths = explode("/", $path);
        // Remove first empty element
        array_shift($this->paths);
        $this->params = array_reduce($this->paths, function ($carry, $item) {
            if (str_starts_with($item, '{') && str_ends_with($item, '}')) {
                $carry[array_search($item, $this->paths)] = substr($item, 1, -1);
            }
            return $carry;
        }, []);

    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controller;
    }

    public function getName()
    {
    }

    public function getRoute(array $inputParams): string
    {
        $route = $this->paths;
        foreach ($this->params as $key => $paramName) {
            if (!key_exists($paramName, $inputParams)) {
                $this->fatal("Parameter $paramName is missing");
            }
            $route[$key] = $inputParams[$paramName];
        }

        return $this->getAbsoluteUrl() . '/' . implode("/", $route);
    }

    public function isPost(): bool
    {
        return $this->isPost;
    }

    /**
     * @param array $input
     * @return bool|array Returns false if not match, array of parameters if match
     */
    public function matchPath(array $input): false|array
    {
        if (count($this->paths) != count($input)) {
            return false;
        }

        $res = [];

        foreach ($input as $key => $item) {
            if (!key_exists($key, $this->params)) {
                if ($this->paths[$key] != $item) {
                    return false;
                }
            } else {
                $res[$this->params[$key]] = $item;
            }
        }

        return $res;
    }

    public static function get(string $name, string $path, string $controller, string $action): void
    {
        Router::get()->addRoute($name, new Route($path, $controller, $action));
    }

    public static function post(string $name, string $path, string $controller, string $action): void
    {
        Router::get()->addRoute($name, new Route($path, $controller, $action, true));

    }

}