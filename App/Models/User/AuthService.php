<?php

namespace Models\User;

use Core\AUtility;
use Core\SessionManager;

class AuthService extends AUtility
{

    /**
     * Singleton instance
     * @var AuthService
     */
    private static AuthService $instance;

    private ?User $loggedUser;

    private function __construct()
    {
        $this->loggedUser = null;
        $this->auth();
    }

    public function getUser(): User|null
    {
        return $this->loggedUser;
    }

    public function isAdmin(): bool
    {
        return $this->loggedUser !== null && $this->loggedUser->isAdmin();
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedUser !== null;
    }

    public function login(string $login, string $password, bool $remember = false): bool
    {
        if (preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $login))
            $user = UserService::get()->getByEmail($login);
        else
            $user = UserService::get()->getByUsername($login);

        if ($user === null || $user === false) {
            $this->error('User not found');
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            $this->error('Wrong password');
            return false;
        }
        $token = $user->getRememberToken();

        $this->setUserSession($token, $remember);

        $this->setLoggedUser($user);
        return true;
    }

    public function logout(): void
    {
        $this->loggedUser = null;
        $this->destroyUserSession();
    }

    private function auth(): void
    {
        $token = SessionManager::getInstance()->get('user_token');

        if ($token === null) {
            return;
        }
        $tokenExpires = SessionManager::getInstance()->get('user_token_expires');
        if ($tokenExpires === null || $tokenExpires < time()) {
            $this->error('Byli jste odhlášeni z důvodu neaktivity');
            $this->destroyUserSession();
            return;
        }

        $user = UserService::get()->getByRememberToken($token);
        if (!$user) {
            $this->destroyUserSession();
            return;
        }
        $this->setUserSession($token, SessionManager::getInstance()->get('remember_user') ?? false);
        $this->setLoggedUser($user);

    }

    private function destroyUserSession()
    {
        SessionManager::getInstance()->remove('user_token');
        SessionManager::getInstance()->remove('user_token_expires');
        SessionManager::getInstance()->remove('remember_user');
    }

    private function setLoggedUser(User $user)
    {
        $this->loggedUser = $user;

    }

    /**
     *      * Session
     *  - user_token - remember token
     * - remember_user - if true, user_token_expires will be set to 30 days
     * - user_token_expires - token expiration time (3 minutes by default)
     * @param string $token
     * @param bool $remember
     * @return void
     */
    private function setUserSession(string $token, bool $remember): void
    {
        SessionManager::getInstance()->set('user_token', $token);
        if ($remember) {
            SessionManager::getInstance()->set('remember_user', true);
            SessionManager::getInstance()->set('user_token_expires', time() + 60 * 60 * 24 * 30);
            return;
        }
        SessionManager::getInstance()->set('user_token_expires', time() + 60 * 3);
    }

    /**
     * Singleton Getter
     * @return AuthService
     */
    public static function get(): AuthService
    {
        if (!isset(self::$instance))
            self::$instance = new AuthService();
        return self::$instance;
    }
}