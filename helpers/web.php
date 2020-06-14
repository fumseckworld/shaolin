<?php

use Imperium\Http\Request\Request;

if (!function_exists('url')) {

    /**
     *
     * Generate the full url
     *
     * @param string ...$urls The url parts.
     *
     * @return string
     *
     */
    function url(string ...$urls): string
    {
        if (php_sapi_name() == 'cli') {
            return sprintf('/%s', join('/', $urls));
        }
        if (https()) {
            return sprintf('https://%s/%s', Request::make()->server()->get('HTTP_HOST'), join('/', $urls));
        }
        return  sprintf('http://%s/%s', Request::make()->server()->get('HTTP_HOST'), join('/', $urls));
    }
}

if (!function_exists('https')) {

    /**
     *
     * Check if the server is secure
     *
     * @return bool
     *
     */
    function https(): bool
    {
        return php_sapi_name() == 'cli' ? false : Request::make()->secure();
    }
}
