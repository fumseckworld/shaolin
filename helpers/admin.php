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
 *
 */

use Imperium\Configuration\Config;
use Imperium\Configuration\Personalization\Imperium;
use Imperium\Container\Ioc;
use Imperium\Environment\Env;
use Imperium\Exception\Kedavra;

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
        $base = php_sapi_name() == 'cli' ? strval(realpath('.')) : dirname(strval(realpath('./')));

        if (def($values)) {
            foreach ($values as $dir) {
                if (def($dir)) {
                    if (def(strstr($dir, DIRECTORY_SEPARATOR))) {
                        foreach (explode(DIRECTORY_SEPARATOR, $dir) as $x) {
                            $base .= DIRECTORY_SEPARATOR . $x;

                            if (strcmp($x, '*') !== 0) {
                                if (!file_exists($base)) {
                                    if (def(strstr($base, '.'))) {
                                        if (!file_exists($base)) {
                                            touch($base);
                                        }
                                    } else {
                                        if (!is_dir($base)) {
                                            mkdir($base);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $base .=   DIRECTORY_SEPARATOR . $dir;
                        if (strcmp($dir, '*') !== 0) {
                            if (!file_exists($base)) {
                                if (def(strstr($base, '.'))) {
                                    if (!file_exists($base)) {
                                        touch($base);
                                    }
                                } else {
                                    if (!is_dir($base)) {
                                        mkdir($base);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $base;
    }
}

if (!function_exists('app')) {

    /**
     *
     * Get an instance of a class.
     *
     * @param string $key THe class key.
     *
     * @return mixed
     *
     */
    function app(string $key)
    {
        return (new Ioc())->get($key);
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
     * @param string $key       The environment key.
     * @param mixed  $default   The default value if not found.
     *
     * @return mixed
     *
     */
    function env(string $key, $default = null)
    {
        $value = Env::get($key);
        return def($value) ? $value : $default;
    }
}

if (!function_exists('config')) {

    /**
     *
     * Get a config value
     *
     * @param string $file  The config filename.
     * @param string $key   The config key value.
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

if (!function_exists('imperium')) {

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
    function imperium(string $key, string $default = '')
    {
        return (new Imperium($key))->get($default);
    }
}
