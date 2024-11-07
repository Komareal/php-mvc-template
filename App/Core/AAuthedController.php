<?php

namespace Core;

use Models\User\AuthService;
use Models\User\User;

abstract class AAuthedController extends AController
{

    protected ?User $user;

    public function __construct(array $params, array $post = [], array $get = [])
    {
        if (!isset($this->user)) {
            $this->user = AuthService::get()->getUser();
        }
        parent::__construct($params, $post, $get);
    }

    protected function adminOnly(string $message = 'You do not have permission to access this page', string $userMessage = '')
    {
        if ($userMessage !== '') {
            $this->userOnly($userMessage);
        } else {
            $this->userOnly();
        }
        if ($this->user === null || !$this->user->isAdmin()) {
            $this->error($message);
            $this->redirect('home', [], 403);
        }
    }

    protected function beforeRender()
    {
        if (!isset($this->data['user'])) {
            $this->data['user'] = $this->user;
        }
        if (!isset($this->data['isAdmin'])) {
            if ($this->user) {
                $this->data['isAdmin'] = $this->user->isAdmin();
            } else {
                $this->data['isAdmin'] = false;
            }
        }
        parent::beforeRender();
    }

    protected function guestOnly(string $message = "You are already logged in")
    {
        if ($this->user !== null) {
            $this->error($message);
            $this->redirect('home');
        }
    }

    protected function userOnly(string $message = 'You must be logged in to access this page')
    {
        if (!isset($this->user)) {
            $this->user = AuthService::get()->getUser();
        }

        if (!$this->user) {
            $this->error($message);
            $this->redirect('login', [], 403);
        }

    }
}