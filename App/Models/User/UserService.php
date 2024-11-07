<?php

namespace Models\User;

use Core\Db\Db;
use Core\Db\DbParam;
use Core\AUtility;
use Exception;
use PDO;

class UserService extends AUtility
{

    /**
     * Singleton instance
     * @var UserService
     */
    private static UserService $instance;

    private function __construct()
    {
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $sql = 'SELECT id, username, email, password, remember_token, created_at, admin FROM users';
        try {
            $users = Db::get()->getAll($sql, User::class);
        } catch (Exception $e) {
            $this->fatal('Error while getting users from DB <br>' . $e->getMessage());
        }
        return $users;
    }

    public function getByEmail(string $email): ?User
    {
        $sql = 'SELECT id, username, email, password, remember_token, created_at, admin FROM users WHERE email = :email';
        try {
            $user = Db::get()->getOne($sql, User::class, [new DbParam('email', $email, PDO::PARAM_STR)]);
        } catch (Exception $e) {
            $this->fatal('Error while getting user from DB <br>' . $e->getMessage());
        }
        return $user;
    }

    public function getById($getUserId)
    {
        $sql = 'SELECT id, username, email, password, remember_token, created_at, admin FROM users WHERE id = :id';
        try {
            $user = Db::get()->getOne($sql, User::class, [new DbParam('id', $getUserId, PDO::PARAM_INT)]);
        } catch (Exception $e) {
            $this->fatal('Error while getting user from DB <br>' . $e->getMessage());
        }
        return $user;
    }

    public function getByRememberToken(string $token): ?User
    {
        $sql = 'SELECT id, username, email, password, remember_token, created_at, admin FROM users WHERE remember_token = :token';
        try {
            $user = Db::get()->getOne($sql, User::class, [new DbParam('token', $token, PDO::PARAM_STR)]);
        } catch (Exception $e) {
            $this->fatal('Error while getting user from DB <br>' . $e->getMessage());
        }
        return $user;
    }

    public function getByUsername(string $username): ?User
    {
        $sql = 'SELECT id, username, email, password, remember_token, created_at, admin FROM users WHERE username = :username';
        try {
            $user = Db::get()->getOne($sql, User::class, [new DbParam('username', $username, PDO::PARAM_STR)]);
        } catch (Exception $e) {
            $this->fatal('Error while getting user from DB <br>' . $e->getMessage());
        }
        return $user;
    }

    public function insert(User $user): bool
    {
        $res = 0;
        $params = [];
        if ($user->getRememberToken()) {
            $sql = 'INSERT INTO users (username, email, password, admin, remember_token) VALUES (:username, :email, :password, :admin, :token)';
            $params[] = new DbParam('token', $user->getRememberToken(), PDO::PARAM_STR);
        } else {
            $sql = 'INSERT INTO users (username, email, password, admin) VALUES (:username, :email, :password, :admin)';
        }
        try {
            $params[] = new DbParam('username', $user->getUsername(), PDO::PARAM_STR);
            $params[] = new DbParam('email', $user->getEmail(), PDO::PARAM_STR);
            $params[] = new DbParam('password', $user->getPassword(), PDO::PARAM_STR);
            $params[] = new DbParam('admin', $user->isAdmin(), PDO::PARAM_BOOL);
            $res = Db::get()->execute($sql, $params);
        } catch (Exception $e) {
            $this->fatal('Error while inserting user into DB <br>' . $e->getMessage());
        }
        return $res != 0;
    }

    public function update(User $user): void
    {
        $isAdmin = $user->isAdmin();
        $loggedUser = AuthService::get()->getUser();
        if ($loggedUser && $loggedUser->getId() == $user->getId())
            $isAdmin = $loggedUser->isAdmin();

        $toUpdate = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'admin' => $isAdmin ? 1 : 0,
            'remember_token' => $user->getRememberToken(),
        ];
        $params = [];
        $sql = 'UPDATE users SET ';
        foreach ($toUpdate as $key => $value) {
            if ($value !== null) {
                $sql .= $key . ' = :' . $key . ', ';
                $params[] = new DbParam($key, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE id = :id';
        $params[] = new DbParam('id', $user->getId(), PDO::PARAM_INT);
        try {
            Db::get()->execute($sql, $params);
        } catch (Exception $e) {
            $this->fatal('Error while updating user in DB <br>' . $e->getMessage());
        }
    }

    /**
     * Singleton Getter
     * @return UserService
     */
    public static function get(): UserService
    {
        if (!isset(self::$instance))
            self::$instance = new UserService();
        return self::$instance;
    }
}