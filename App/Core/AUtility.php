<?php

namespace Core;

/**
 * Class AService
 * @package Core
 * @description Abstract class that provides common utilities
 */
abstract class AUtility
{

    protected function currentRoute(): string
    {
        return Router::get()->getCurrentRoute();
    }

    /**
     * @param ...$vars
     * @return void
     * @description Dumps variables and dies
     */
    protected function dd(...$vars): void
    {
        echo "<pre>";
        foreach ($vars as $var) {
            var_dump($var);
            echo '<br><br>';
        }
        echo "</pre>";
        die;
    }

    /**
     * @param ...$vars
     * @return void
     * @description Dumps variables
     */
    protected function dump(...$vars): void
    {
        echo "<pre>";
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo "</pre>";
    }

    /**
     * @param string $safeMessage if not in dev mode, this message will be shown
     * @param string $message if in dev mode, this message will be shown (if provided)
     * @return void
     * @description Adds an error message to the session
     */
    protected function error(string $safeMessage, string $message = ''): void
    {
        if (!Config::$dev || $message === '') {
            $message = $safeMessage;
        }
        $orig = SessionManager::getInstance()->get('error');
        if ($orig === null) {
            $orig = [];
        }
        $orig[] = $message;
        SessionManager::getInstance()->set('error', $orig);
    }

    /**
     * @return bool
     * @description Checks if there are any errors in the session
     */
    protected function errorExist(): bool
    {
        return SessionManager::getInstance()->get('error') !== null && count(SessionManager::getInstance()->get('error')) > 0;
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $safeMessage if not in dev mode, this message will be shown. If 'true' is passed, $message will be shown
     * @return void
     */
    protected function fatal(string $message, int $code = 500, string $safeMessage = ''): void
    {
        ob_end_clean();
        http_response_code($code);
        if (!Config::$dev) {
            if ($safeMessage !== 'true') {
                $message = $safeMessage;
            }
        }
        require(__DIR__ . '/../Views/error-site.phtml');
        exit;
    }

    /**
     * @return string
     */
    protected function getAbsoluteUrl(): string
    {
        //$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $protocol = Config::$protocol;
        $host = $_SERVER['HTTP_HOST'];
        return "$protocol://$host";

    }

    /**
     * @param array $input
     * @return array
     */
    protected function indexModelArrayById(array $input): array
    {
        $indexed = [];
        foreach ($input as $product) {
            $indexed[$product->getId()] = $product;
        }
        return $indexed;
    }

    /**
     * @param string $target target URL / route
     * @param array $params parameters for the route
     * @param int $code HTTP code
     * @param bool $isRoute if true, the target is a route, otherwise it's a URL
     * @return void
     * @description Redirects to a given URL / route
     */
    protected function redirect(string $target, array $params = [], int $code = 302, bool $isRoute = true): void
    {
        if ($isRoute) {
            $target = $this->route($target, $params);
        }
        http_response_code($code);
        header("Location: " . "$target");
        header("Connection: close");
        exit;
    }

    /**
     * @return void
     * @description Redirects back to the previous page
     */
    protected function redirectBack(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER'], [], false);
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @description Returns the URL of a given route
     */
    protected function route(string $name, array $params = []): string
    {
        return Router::get()->getRoute($name, $params);
    }

    /**
     * Secures string or array from XSS attacks
     * @param $var mixed
     * @return array|mixed|string|null
     */
    protected function secure(mixed $var): mixed
    {
        if (is_string($var))
            return htmlspecialchars($var, ENT_QUOTES);
        elseif (is_array($var)) {
            foreach ($var as $key => $item) {
                $var[$key] = $this->secure($item);
            }
            return $var;
        } else {
            return $var;
        }
    }

    /**
     * @param string $message
     * @return void
     * @description Adds a success message to the session
     */
    protected function success(string $message): void
    {
        $orig = SessionManager::getInstance()->get('success');
        if ($orig === null) {
            $orig = [];
        }
        $orig[] = $message;
        SessionManager::getInstance()->set('success', $orig);
    }
}