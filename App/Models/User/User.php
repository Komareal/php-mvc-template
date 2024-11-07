<?php

namespace Models\User;

use Core\Db\Datable;
use Core\Db\Identifier;

class User
{

    use Identifier;

    use Datable;

    private int $admin;

    private string $email;

    /**
     * @var string $password Hashed password
     */
    private string $password;

    private ?string $remember_token;

    private string $username;

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return string|null
     */
    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    /**
     * @param string|null $remember_token
     */
    public function setRememberToken(?string $remember_token): void
    {
        $this->remember_token = $remember_token;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function isAdmin(): bool
    {
        return $this->admin === 1;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin ? 1 : 0;
    }


}