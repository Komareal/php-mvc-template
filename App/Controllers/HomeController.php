<?php

namespace Controllers;

use Core\AAuthedController;

class HomeController extends AAuthedController
{

    public function index()
    {
        $this->userOnly();
        $this->view = 'home';
        $this->baseData['style'] = 'home';
    }
}