<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 */

use DI\DependencyException;
use DI\NotFoundException;
use Nol\Configuration\Config;
use Nol\Configuration\Personalization\Nol;
use Nol\Console\Ji;
use Nol\Container\Ioc;
use Nol\Database\Connection\Connect;
use Nol\Exception\Kedavra;
use Nol\Security\Hashing\Hash;

if (!function_exists('validator_messages')) {

    /**
     *
     * Get validation message
     *
     * @return string[]
     *
     */
    function validator_messages(): array
    {
        $file = 'validator';

        return [
            VALIDATOR_EMAIL_NOT_VALID => config($file, 'email', 'The %s email is not a valid email'),
            VALIDATOR_ARGUMENT_NOT_DEFINED => config($file, 'required', 'The %s argument is required'),
            VALIDATOR_ARGUMENT_NOT_NUMERIC => config($file, 'digits', 'The %s argument is not a digit'),
            VALIDATOR_ARGUMENT_NOT_UNIQUE => config($file, 'unique', 'The %s value is not unique inside the %s table'),
            VALIDATOR_ARGUMENT_NOT_BETWEEN => config($file, 'between', 'The %s argument must be between %d and %d'),
            VALIDATOR_ARGUMENT_SUPERIOR_OF_MAX_VALUE => config($file, 'max', 'The %s argument is superior to %d'),
            VALIDATOR_ARGUMENT_SUPERIOR_MIN_OF_VALUE => config($file, 'min', 'The %s argument is inferior to %d'),
            VALIDATOR_ARGUMENT_SLUG => config($file, 'slug', 'The %s is not a slug'),
            VALIDATOR_ARGUMENT_SNAKE => config($file, 'snake', 'The %s is not a snake case string format'),
            VALIDATOR_ARGUMENT_CAMEL_CASE => config($file, 'camel', 'The %s is not a camel case string format'),
            VALIDATOR_ARGUMENT_ARRAY => config($file, 'array', 'The %s is not an array'),
            VALIDATOR_ARGUMENT_BOOLEAN => config($file, 'boolean', 'The %s is not an boolean'),
            VALIDATOR_ARGUMENT_IMAGE => config($file, 'image', 'The %s is not an image'),
            VALIDATOR_ARGUMENT_JSON => config($file, 'json', 'The %s is not a json'),
            VALIDATOR_ARGUMENT_STRING => config($file, 'string', 'The %s is not a string'),
            VALIDATOR_ARGUMENT_URL => config($file, 'url', 'The %s is not an url'),
            VALIDATOR_ARGUMENT_FLOAT => config($file, 'float', 'The %s is not a float'),
            VALIDATOR_ARGUMENT_INT => config($file, 'int', 'The %s is not a int'),
            VALIDATOR_ARGUMENT_MAC => config($file, 'mac', 'The %s is not a mac address'),
            VALIDATOR_ARGUMENT_IPV4 => config($file, 'ipv4', 'The %s is not a valid ipv4 address'),
            VALIDATOR_ARGUMENT_IPV6 => config($file, 'ipv6', 'The %s is not a valid ipv6 address'),
            VALIDATOR_ARGUMENT_DOMAIN => config($file, 'domain', 'The %s is not a valid domain'),
        ];
    }
}

if (!function_exists('ji')) {
    /**
     *
     * Console commands
     *
     * @param string $name    The application name
     * @param string $version The application version
     *
     * @throws Exception
     * @return int
     */
    function ji(string $name, string $version): int
    {
        return (new Ji($name, $version))->add(commands())->run();
    }
}

if (!function_exists('commands')) {

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @return array
     */
    function commands(): array
    {
        return collect(
            array_merge(
                files(
                    app('console-path') . '*.php'
                ),
                files(app('console-path') . '*/*.php')
            )
        )->for(function ($x) {
            $class = str_replace(
                strval(app('app-directory')),
                strval(app('app-namespace')),
                strval(collect(
                    explode('.', str_replace(DIRECTORY_SEPARATOR, '\\', strval(strstr($x, app('app-directory')))))
                )->first())
            );
            return new $class();
        })->all();
    }
}
if (!function_exists('class_to_array')) {

    /**
     *
     * Parse an object an return an array
     *
     * @param object $object
     *
     * @return array
     *
     */
    function class_to_array(object $object): array
    {
        return json_decode(strval(json_encode($object)), true);
    }
}

if (!function_exists('not_def')) {
    /**
     *
     * Check if all values are not define
     *
     * @param array<int, mixed> $values
     *
     * @return bool
     *
     */
    function not_def(...$values): bool
    {
        foreach ($values as $value) {
            if (def($value)) {
                return false;
            }
        }

        return true;
    }
}


if (!function_exists('camel_to_snake')) {


    /**
     *
     * Get a snake case string format with camel case format
     *
     * @param string $snake
     *
     * @return string
     *
     */
    function camel_to_snake(string $snake): string
    {
        return strtolower(strval(preg_replace('/(?<!^)[A-Z]/', '_$0', $snake)));
    }
}

if (!function_exists('is_snake')) {


    /**
     *
     * check if string is a snake case
     *
     * @param string $snake
     *
     * @return bool
     *
     */
    function is_snake(string $snake): bool
    {
        return preg_match("#^[a-z]([a-z_]+)$#", $snake) == 1;
    }
}

if (!function_exists('is_slug')) {


    /**
     *
     * check if string is a snake case
     *
     * @param string $slug
     *
     * @return bool
     *
     */
    function is_slug(string $slug): bool
    {
        return preg_match("#^[a-z]([a-z0-9\-]+)$#", $slug) == 1;
    }
}

if (!function_exists('sluglify')) {


    /**
     *
     * convert a string to a slug
     *
     * @param string $slug the string to sluglify.
     *
     * @return string
     *
     */
    function sluglify(string $slug): string
    {
        $space = function (string $x) {
            return collect(explode(' ', $x))->for('trim')->for('strtolower')->join('-');
        };

        $point = function (string $point) {
            $x = function ($x) {
                return str_replace(
                    '__',
                    '_',
                    str_replace(
                        '-',
                        '_',
                        str_replace(
                            '--',
                            '_',
                            str_replace('.', '_', $x)
                        )
                    )
                );
            };

            return collect(explode('_', call_user_func_array($x, [$point])))->join('-');
        };
        $comma = function (string $comma) {
            $x = function ($x) {
                return str_replace('-', '', str_replace(',', '_', $x));
            };

            return collect(explode('_', call_user_func_array($x, [$comma])))->join('-');
        };

        if (is_slug($slug)) {
            return $slug;
        }

        if (def(strstr($slug, ' '))) {
            $slug = call_user_func_array($space, [$slug]);
        }


        if (is_slug($slug)) {
            return $slug;
        }

        if (def(strstr($slug, ','))) {
            $slug = call_user_func_array($comma, [$slug]);
        }

        if (is_slug($slug)) {
            return $slug;
        }
        if (def(strstr($slug, '.'))) {
            $slug = call_user_func_array($point, [$slug]);
        }

        if (is_slug($slug)) {
            return $slug;
        }
        if (def(strstr($slug, '_'))) {
            $slug = collect(explode('_', $slug))->join('-');
        }

        if (is_slug($slug)) {
            return $slug;
        }
        return '';
    }
}

if (!function_exists('is_camel')) {


    /**
     *
     * convert a sing in a slug
     *
     * @param string $camel
     *
     * @return bool
     *
     */
    function is_camel(string $camel): bool
    {
        return preg_match("#^[A-Z]([A-Za-z]+)$#", $camel) == 1;
    }
}
if (!function_exists('snake_to_camel')) {


    /**
     *
     * Get a camel case string format with snake case format
     *
     * @param string $snake
     *
     * @return string
     *
     */
    function snake_to_camel(string $snake): string
    {
        return collect(explode('_', $snake))->for('ucfirst')->join('');
    }
}

if (!function_exists('logged')) {
    /**
     *
     * Check if the user is logged
     *
     * @throws NotFoundException
     * @throws Exception
     * @throws DependencyException
     * @return boolean
     *
     */
    function logged(): bool
    {
        return cli() ? false : app('session')->has('user');
    }
}

if (!function_exists('guest')) {

    /**
     *
     * Check if te user is not logged.
     *
     * @throws NotFoundException
     * @throws Exception
     * @throws DependencyException
     *
     * @return boolean
     *
     */
    function guest(): bool
    {
        return !logged();
    }
}
if (!function_exists('secure_password')) {
    /**
     *
     * Hash a plain text value.
     *
     * @param string $value The password to hash.
     *
     * @throws DependencyException
     * @throws NotFoundException
     *
     * @return string
     *
     */
    function secure_password(string $value): string
    {
        return (new Hash($value))->generate();
    }
}

if (!function_exists('check_password')) {

    /**
     *
     * Check the password
     *
     * @param string $plain_text_password
     * @param string $hash_value
     *
     * @throws DependencyException
     * @throws NotFoundException
     *
     * @return bool
     *
     */
    function check_password(string $plain_text_password, string $hash_value): bool
    {
        return (new Hash($plain_text_password))->valid($hash_value);
    }
}


if (!function_exists('def')) {


    /**
     *
     * Check if a values are define an not empty.
     *
     * @param mixed ...$values The values to check.
     *
     * @return boolean
     *
     */
    function def(...$values): bool
    {
        foreach ($values as $value) {
            if (!isset($value) || empty($value)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('base')) {

    /**
     *
     * Found the base path of the application.
     *
     * Can generate a path from the base by the directories and the files name.
     *
     * @param string ...$values The directories or files values.
     *
     * @return string
     *
     */
    function base(string ...$values): string
    {
        $base = [strval(realpath('./'))];

        return collect(array_merge($base, $values))->join(DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('is_serial')) {
    /**
     *
     * Check if a string is serialized
     *
     * @param mixed $x The string to analyse
     *
     * @return boolean
     *
     */
    function is_serial($x): bool
    {
        if (!is_string($x)) {
            return false;
        }
        return unserialize($x) !== false;
    }
}
if (!function_exists('form_token')) {

    /**
     *
     * Generate the form token
     *
     * @throws Exception
     *
     * @return string
     *
     */
    function form_token(): string
    {
        $value = bin2hex(random_bytes(16));

        if (not_cli()) {
            $value =
                app('session')->has('form_token') ? app('session')->get('form_token')
                    : app('session')->set('form_token', $value)->get('form_token');
        }
        return sprintf(
            '<input type="hidden" name="form_token" value="%s">',
            $value
        );
    }
}
if (!function_exists('app')) {

    /**
     *
     * Get an instance of a class.
     *
     * @param string $key THe class key.
     *
     * @throws NotFoundException
     * @throws Exception
     * @throws DependencyException
     *
     * @return mixed
     *
     */
    function app(string $key)
    {
        return (new Ioc())->get($key);
    }
}

if (!function_exists('connect')) {

    /**
     *
     * Create a new instance of connect.
     *
     * @param string $driver   The driver to use.
     * @param string $base     THe base name or path.
     * @param string $username The base username.
     * @param string $password The base password.
     * @param string $host     The base hostname.
     *
     * @return Connect
     *
     */
    function connect(
        string $driver,
        string $base,
        string $username = '',
        string $password = '',
        string $host = 'localhost'
    ): Connect {
        return new Connect($driver, $base, $username, $password, $host);
    }
}
if (!function_exists('files')) {

    /**
     *
     * Get all files who matches the pattern.
     *
     * @param string $pattern The glob pattern.
     *
     * @return array
     *
     */
    function files(string $pattern): array
    {
        $x = glob($pattern);
        return is_bool($x) ? [] : $x;
    }
}


if (!function_exists('env')) {

    /**
     *
     * Get an environment variable.
     *
     * If the variable is not define
     * the default value is returned.
     *
     * @param string $key     The environment key.
     * @param mixed  $default The default value if not found.
     *
     * @throws NotFoundException
     * @throws Exception
     * @throws DependencyException
     *
     * @return mixed
     *
     */
    function env(string $key, $default = null)
    {
        return app('env')->get($key, $default);
    }
}

if (!function_exists('config')) {

    /**
     *
     * Get a config value
     *
     * @param string $file    The config filename.
     * @param string $key     The config key value.
     * @param mixed  $default The default value if not exist.
     *
     * @return mixed
     *
     */
    function config(string $file, string $key, $default = null)
    {
        try {
            return (new Config($file, $key))->get();
        } catch (Kedavra $e) {
            return $default;
        }
    }
}

if (!function_exists('nol')) {

    /**
     *
     * Get a personalized config value
     *
     * @param string $key     The personalization key.
     * @param string $default The default value.
     *
     * @return mixed
     *
     */
    function nol(string $key, string $default = '')
    {
        return (new Nol($key))->get($default);
    }
}
