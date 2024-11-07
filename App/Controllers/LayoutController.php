<?php

namespace Controllers;

use Core\Config;
use Core\AController;
use Core\SessionManager;
use Models\User\AuthService;

class LayoutController extends AController
{

    public function __construct(array $params)
    {
        $this->view = 'layout';
        if (!isset($params['content'])) {
            $this->fatal("Content is not set in controller " . get_class($this), 500);
        }
        $this->components['content'] = $params['content'];
        parent::__construct($params);
    }

    public function index()
    {
        $this->data['auth'] = AuthService::get()->isLoggedIn();
        $this->data['isAdmin'] = AuthService::get()->isAdmin();

        if (Config::$dev)
        {
            $this->error("Dev mode is enabled");
        }
    }

    public function renderView(): void
    {
        ob_start();
        parent::renderView();
        ob_end_flush();
    }

    public function setLayoutView(string $view)
    {
        $this->view = $view;
    }

    protected function beforeRender()
    {
        if (isset($this->params['title'])) {
            $this->data['title'] = $this->params['title'];
        } else {
            $this->data['title'] = 'MVC Template';
        }

        $this->getMessages();
        parent::beforeRender();
    }

    protected function content()
    {
        $this->component('content');
    }

    private function getMessages()
    {
        $session = SessionManager::getInstance();
        $types = ['error', 'success'];
        $this->data['messageTypes'] = [];
        foreach ($types as $type) {
            $messages = $session->get($type);
            if ($messages === null) {
                $messages = [];
            }
            $this->data['messageTypes'][$type] = $messages;
            $session->remove($type);
        }
    }
}