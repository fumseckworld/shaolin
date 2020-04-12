<?php

declare(strict_types=1);

use Carbon\Carbon;
use Eywa\Application\Environment\Env;
use Eywa\Collection\Collect;
use Eywa\Database\Query\Sql;
use Eywa\Exception\Kedavra;
use Eywa\Http\Request\Request;
use Eywa\Message\Flash\Flash;

if (!function_exists('collect')) {
    /**
     *
     * Return an instance of collection
     *
     * @param array<mixed> $data
     *
     * @return Collect
     *
     */
    function collect(array $data = []): Collect
    {
        return new Collect($data);
    }
}


if (!function_exists('env')) {

    /**
     *
     * Get an env value
     *
     * @param mixed $variable
     * @param mixed $default
     *
     * @return array|false|string
     *
     */
    function env($variable, $default)
    {
        $x =  (new Env())->get($variable);

        return  def($x) ? $x : $default;
    }
}


if (!function_exists('now')) {
    /**
     *
     * Return an instance of Carbon
     *
     * @param mixed $tz
     *
     * @return Carbon
     *
     */
    function now($tz = null): Carbon
    {
        return Carbon::now($tz);
    }
}
if (!function_exists('not_in')) {

    /**
     *
     * Check if a value is not in the array
     *
     * @param array $array
     * @param mixed $value
     * @param bool $run_exception
     * @param string $message
     *
     * @return bool
     *
     *
     * @throws Kedavra
     *
     */
    function not_in(array $array, $value, bool $run_exception = false, string $message = ''): bool
    {
        $x = !in_array($value, $array, true);

        is_true($x, $run_exception, $message);

        return $x;
    }
}

if (!function_exists('sum')) {
    /**
     *
     * Return the length of data
     *
     * @param mixed $data
     *
     * @return int
     *
     * @throws Kedavra
     *
     */
    function sum($data): int
    {
        if (is_array($data)) {
            return count($data);
        } elseif (is_string($data)) {
            return mb_strlen($data);
        } elseif (is_integer($data) || is_numeric($data)) {
            return intval($data);
        } else {
            throw new Kedavra('The parameter must be a string or an array');
        }
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
if (!function_exists('root')) {

    /**
     * root
     *
     * @return string
     *
     */
    function root(): string
    {
        return php_sapi_name() !== 'cli'
            ? https()
                ? 'https://' . Request::make()->server()->get('HTTP_HOST')
                : 'http://' . Request::make()->server()->get('HTTP_HOST')
            : '/';
    }
}

if (!function_exists('cli')) {
    /**
     *
     * Check if the code is executed in cli
     *
     * @return bool
     *
     */
    function cli(): bool
    {
        return  php_sapi_name() === 'cli';
    }
}

if (!function_exists('not_cli')) {
    /**
     *
     * Check if the code is executed in cli
     *
     * @return bool
     *
     */
    function not_cli(): bool
    {
        return ! cli();
    }
}
if (!function_exists('url')) {

    /**
     *
     * Generate the url
     *
     * @param string ...$urls
     *
     * @return string
     *
     */
    function url(string ...$urls): string
    {
        return php_sapi_name() !== 'cli'
        ? https()
            ?
                sprintf('https://%s/%s', Request::make()->server()->get('HTTP_HOST'), collect($urls)->join('/'))
            :   sprintf('http://%s/%s', Request::make()->server()->get('HTTP_HOST'), collect($urls)->join('/'))
        : sprintf('/%s', collect($urls)->join('/'));
    }
}
if (!function_exists('alert')) {

    /**
     * @param array $messages
     * @param bool $success
     * @return string
     */
    function alert(array $messages, bool $success = false): string
    {
        $file = 'alert';
        $ul_class = config($file, 'ul-class');
        $class = $success ? config($file, 'success-class') : config($file, 'failure-class');
        $html = sprintf('<ul class="%s" >', $ul_class);
        foreach ($messages as $message) {
            append($html, sprintf('<li class="%s">%s</li>', $class, $message));
        }

        append($html, '</ul>');
        return $html;
    }
}
if (!function_exists('route')) {

    /**
     *
     * Get a route url
     *
     * @param string $route
     * @param array $args
     * @return string
     *
     * @throws Kedavra
     */
    function route(string $route, array $args = []): string
    {
        $x = (new Sql(
            connect(
                SQLITE,
                base('routes', 'web.sqlite3')
            )
        ))->from('routes')
            ->where('name', EQUAL, $route)->get();

        is_true(not_def($x), true, sprintf('The %s route has not been found', $route));



        if (cli()) {
            $url = $x[0]->url;

            if (def($args)) {
                $x = '';

                foreach ($args as $k => $v) {
                    append($x, str_replace(":$k", "$v", $url));
                }
                return trim($x, '/');
            }
            return  $url;
        }
        $url =
            https() ? 'https://' . Request::make()->server()->get('SERVER_NAME') . $x[0]->url
            : 'http://' . Request::make()->server()->get('SERVER_NAME') . $x[0]->url;

        if (def($args)) {
            foreach ($args as $k => $v) {
                append($url, str_replace(":$k", "$v", $url));
            }
            return trim($url, '/');
        }
        return  $url;
    }
}

if (!function_exists('ago')) {
    /**
     *
     * @param string $time
     * @param null $tz
     *
     * @return string
     *
     * @throws Kedavra
     * @throws Exception
     *
     */
    function ago(string $time, $tz = null): string
    {
        Carbon::setLocale(app()->lang());

        return Carbon::parse($time, $tz)->diffForHumans();
    }
}

if (!function_exists('append')) {

    /**
     *
     * Append contents to the variable
     *
     * @param mixed $variable
     * @param string ...$contents
     *
     * @return void
     */
    function append(&$variable, string ...$contents): void
    {
        foreach ($contents as $content) {
            $variable .= $content;
        }
    }
}

if (!function_exists('flash')) {
    /**
     *
     * Display flash message
     *
     * @return string
     *
     * @throws Kedavra
     *
     */
    function flash(): string
    {
        return (new Flash())->display();
    }
}

if (!function_exists('mobile')) {
    /**
     *
     * Check if device is mobile
     *
     * @return bool
     *
     * @throws Exception
     */
    function mobile(): bool
    {
        return app()->detect()->mobile();
    }
}

if (!function_exists('is_pair')) {
    /**
     * Check if number is pair
     *
     * @param int $x
     *
     * @return bool
     *
     */
    function is_pair(int $x): bool
    {
        return $x % 2 === 0;
    }
}
