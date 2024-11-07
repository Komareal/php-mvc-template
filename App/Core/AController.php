<?php

namespace Core;

abstract class AController extends AUtility
{

    protected array $baseData = [];

    /**
     * @var array[]|AController[] $components Array of components to load - key is component name, value is array, where key is controller and value is action
     */
    protected array $components = [];

    protected array $data = [];

    protected array $get;

    protected array $params;

    protected array $post;

    protected string $view;

    public function __construct(array $params, array $post = [], array $get = [])
    {
        $this->params = $params;
        $this->post = $post;
        $this->get = $get;
    }

    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
    }

    public function getBaseData(): array
    {
        return $this->baseData;
    }

    public function loadComponents()
    {
        $router = Router::get();
        foreach ($this->components as $name => $component) {
            $controllerName = array_key_first($component);
            $action = $component[$controllerName];
            $this->components[$name] = $router->loadController($controllerName, $action, $this->params);
        }

    }

    public function renderView(): void
    {
        $this->beforeRender();
        if (!isset($this->view)) {
            return;
        }

        $viewPath = __DIR__ . "/../Views/{$this->view}.phtml";
        if (!file_exists($viewPath))
            $this->fatal("View {$this->view} does not exist");

        extract($this->secure($this->data)); // Makes secured variables available in the template
        extract($this->data, EXTR_PREFIX_ALL, "");
        require($viewPath);
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    protected function asset(string $name): string
    {
        return $this->getAbsoluteUrl() . "/public/assets/$name";
    }

    protected function assetImg(?string $path): string
    {

        $img = $this->asset("images/$path");
        if ($path === null || $path === "") {
            if (Config::$defaultImage === null) {
                $this->fatal("Default image is not set");
            }
            $img = $this->asset(Config::$defaultImage);
        }
        return $img;
    }

    protected function beforeRender()
    {
    }

    /**
     * @param string $name
     * @param array $params Params to pass to the component, - can be accessed only in beforeRender
     * @return void
     */
    protected function component(string $name, array $params = []): void
    {
        if (!isset($this->components[$name]) || !($this->components[$name] instanceof AController)) {
            $this->fatal("Component $name does not exist");
        }

        $controller = $this->components[$name];
        $controller->addParams($params);

        $controller->renderView();
    }

    protected function setView($view)
    {
        $this->view = $view;
    }

}