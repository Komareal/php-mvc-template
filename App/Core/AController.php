<?php

namespace Core;

abstract class AController extends AUtility
{

    /**
     * @var array
     * @description  associative array of data to pass to the view. Propagates to all components and views
     */
    protected array $baseData = [];

    /**
     * @var array[]|AController[] $components Array of components to load - key is component name, value is array, where key is controller and value is action
     */
    protected array $components = [];

    /**
     * @var array
     * @description associative array of data to pass to the view. Does not propagate to components
     */
    protected array $data = [];

    /**
     * @var array
     * @description Handled GET parameters
     */
    protected array $get;

    /**
     * @var array
     * @description Parameters passed to the controller
     */
    protected array $params;

    /**
     * @var array
     * @description Handled POST parameters
     */
    protected array $post;

    /**
     * @var string
     * @description View to render
     */
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

    /**
     * @return void
     * @description Recursively prepares components
     */
    public function loadComponents()
    {
        $router = Router::get();
        foreach ($this->components as $name => $component) {
            $controllerName = array_key_first($component);
            $action = $component[$controllerName];
            $this->components[$name] = $router->loadController($controllerName, $action, $this->params);
        }

    }

    /**
     * @return void
     * @description Renders the view (if set) and propagates to components (do not call directly as it's called by the router)
     */
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

    /**
     * @param string $name
     * @return string
     * @description Returns the absolute URL of the application asset
     */
    protected function asset(string $name): string
    {
        return $this->getAbsoluteUrl() . "/public/assets/$name";
    }

    /**
     * @param string|null $path
     * @return string
     * @description Returns the absolute URL of the application image asset - if path is not set, returns the default image
     */
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

    /**
     * @return void
     * @description Hook to run before rendering the view (meant to be overridden)
     */
    protected function beforeRender()
    {
    }

    /**
     * @param string $name
     * @param array $params Params to pass to the component, - can be accessed only in beforeRender
     * @return void
     * @description Renders a component (call only from the view)
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

}