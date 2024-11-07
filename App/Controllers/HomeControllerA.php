<?php

namespace Controllers;

use Core\AAuthedAController;

class HomeControllerA extends AAuthedAController
{

    public function index()
    {
        $this->userOnly();
        $this->view = 'home';
        $this->baseData['style'] = 'home';
    }
}