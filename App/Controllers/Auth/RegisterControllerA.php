<?php

namespace Controllers\Auth;

use Core\AAuthedAController;
use Models\User\User;
use Models\User\UserService;

class RegisterControllerA extends AAuthedAController
{

    public function index()
    {
        $this->guestOnly();
        $this->view = 'register';
        $this->baseData['title'] = 'Registration site';
        $this->baseData['style'] = 'login';
    }

    public function register()
    {
        $this->guestOnly();
        if (!isset($this->post['email'])) {
            $this->error('Please fill in your email');
        } else {
            if (!filter_var($this->post['email'], FILTER_VALIDATE_EMAIL)) {
                $this->error('Email is not valid');
            }
        }

        if (!isset($this->post['username'])) {
            $this->error('Please fill in your username');
        } else {
            if (!preg_match('/^[a-zA-Z0-9]{3,}$/', $this->post['username'])) {
                $this->error('Username must be at least 3 characters long and contain only letters and numbers');
            }
        }
        if (!isset($this->post['password'])) {
            $this->error('Please fill in your password');
        } else {

            if (strlen($this->post['password']) < 5) {
                $this->error('Password must be at least 5 characters long');
            }
        }
        if (!isset($this->post['password2'])) {
            $this->error('Please fill in your password again');
        }
        if ($this->post['password'] !== $this->post['password2']) {
            $this->error('Passwords do not match');
        }

        if ($this->errorExist()) {
            $this->redirect('register');
        }

        if (UserService::get()->getByEmail($this->post['email'])) {
            $this->error('User with this email already exists');
        }

        if (UserService::get()->getByUsername($this->post['username'])) {
            $this->error('User with this username already exists');
        }

        if ($this->errorExist()) {
            $this->redirect('register');
        }

        $user = new User();
        $user->setEmail($this->post['email']);
        $user->setUsername($this->post['username']);
        $user->setPassword($this->post['password']);
        $user->setAdmin(false);
        $user->setRememberToken(uniqid());
        if (!UserService::get()->insert($user)) {
            $this->fatal(
                'User could not be registered',
                500, true);
        }
        $this->success('User registered successfully');
        $this->redirect('login');
    }

}