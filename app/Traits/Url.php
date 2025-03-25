<?php

namespace App\Traits;

trait Url
{
    /**
     * Check if the current connection is secure (HTTPS).
     *
     * @return bool
     */
    protected function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || ($_SERVER['SERVER_PORT'] == 443);
    }

    /**
     * Retrieve a parameter from URL, POST, GET, REQUEST, or SERVER.
     *
     * @param string $paramName
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $paramName, mixed $default = null): mixed
    {
        $urlData = [
            'scheme' => $_SERVER['REQUEST_SCHEME'] ?? '',
            'host' => $_SERVER['HTTP_HOST'] ?? '',
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'query' => $_SERVER['QUERY_STRING'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? '',
            'port' => $_SERVER['SERVER_PORT'] ?? '',
            'full_url' => ($this->isSecure() ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
        ];

        return $urlData[$paramName]
            ?? filter_input(INPUT_GET, $paramName)
            ?? filter_input(INPUT_POST, $paramName)
            ?? filter_input(INPUT_SERVER, $paramName)
            ?? $default;
    }
}
