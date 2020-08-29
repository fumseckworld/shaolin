<?php

use Nol\Collection\Collect;
use Nol\Http\Request\Request;

if (!function_exists('url')) {

    /**
     *
     * Generate the full url
     *
     * @param array $base The base urls.
     * @param string ...$urls The url arguments.
     *
     * @return string
     */
    function url(array $base, string ...$urls): string
    {
        $urls = array_merge($base, $urls);

        if (php_sapi_name() == 'cli') {
            return sprintf('/%s', join('/', $urls));
        }
        if (https()) {
            return sprintf('https://%s/%s', Request::make()->server()->get('HTTP_HOST'), join('/', $urls));
        }
        return sprintf('http://%s/%s', Request::make()->server()->get('HTTP_HOST'), join('/', $urls));
    }
}

if (!function_exists('cli')) {

    /**
     *
     * Check if the code is execute in command line.
     *
     * @return boolean
     *
     */
    function cli(): bool
    {
        return strcmp(php_sapi_name(), 'cli') === 0;
    }
}

if (!function_exists('not_cli')) {

    /**
     *
     * check if the code is not executed  from the command line.
     *
     * @return bool
     *
     */
    function not_cli(): bool
    {
        return !cli();
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


if (!function_exists('collect')) {

    /**
     *
     * Get a new collection
     *
     * Transform the given parameter to a collection.
     *
     * @param array|object|null $data The data.
     *
     * @return Collect
     *
     */
    function collect($data = null): Collect
    {
        if (is_object($data)) {
            return new Collect(class_to_array($data));
        }
        if (is_array($data)) {
            return new Collect($data);
        }
        return new Collect([]);
    }
}

if (!function_exists('total')) {


    /**
     *
     * @param int $x
     *
     * @return string
     *
     */
    function total(int $x): string
    {
        if ($x >= 1000000000) {
            return round(($x / 1000000000), 2) . ' B';
        } elseif ($x >= 1000000) {
            return round(($x / 1000000), 2) . ' M';
        } elseif ($x >= 1000) {
            return round(($x / 1000), 2) . ' K';
        }

        return number_format($x);
    }
}
