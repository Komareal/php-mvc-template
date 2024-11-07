<?php

namespace Core;

class SessionManager extends AUtility
{

    /**
     * @var SessionManager
     * @description Singleton instance of SessionManager
     */
    private static SessionManager $instance;

    /**
     * @param false|int $cacheExpire
     * @param string|null $cacheLimiter
     * @description SessionManager singleton constructor.
     */
    private function __construct(false|int $cacheExpire = false, string $cacheLimiter = null)
    {
        if (session_status() === PHP_SESSION_NONE) {

            session_cache_limiter($cacheLimiter);
            session_cache_expire($cacheExpire);

            session_start();
        }
    }

    /**
     * @return void
     * @description Destroys the session
     */
    public function clear(): void
    {
        session_unset();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return SessionManager
     */
    public function set(string $key, mixed $value): SessionManager
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * @param string|null $cacheExpire
     * @param string|null $cacheLimiter
     * @return SessionManager
     * @description Returns the instance of SessionManager
     */
    public static function getInstance(string $cacheExpire = null, string $cacheLimiter = null): SessionManager
    {
        if (!isset(self::$instance)) {
            self::$instance = new SessionManager($cacheExpire, $cacheLimiter);
        }
        return self::$instance;

    }
}
