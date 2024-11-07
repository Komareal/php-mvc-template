<?php

namespace Controllers\Auth;

use Core\AAuthedController;
use Models\User\AuthService;

class LoginController extends AAuthedController
{

    public function index()
    {
        $this->guestOnly();
        $this->view = 'login';
        $this->baseData['title'] = 'Login site';
        $this->baseData['style'] = 'login';
    }

    public function login()
    {
        $this->guestOnly();
        if (!isset($this->post['email'])) {
            $this->error('Email is required');
        }
        if (!isset($this->post['password'])) {
            $this->error('Password is required');
        }
        if ($this->errorExist()) {
            $this->redirect('login');
        }
        if (AuthService::get()->login($this->post['email'], $this->post['password'], ($this->post['remember'] ?? null) !== null)) {
            $this->success('You have been successfully logged in');
            $this->redirect('home');
        }
        $this->error('Invalid email or password');
        $this->redirect('login');
    }

    public function logout()
    {
        AuthService::get()->logout();
        $this->success('You have been successfully logged out');
        $this->redirect('login');
    }

}