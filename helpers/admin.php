<?php

use Imperium\Environment\Env;

if (!function_exists('def')) {


    /**
     *
     * Check if a values are define an not empty.
     *
     * @param mixed ...$values
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

if (!file_exists('base')) {

    /**
     *
     * Found the base path of the application.
     *
     * Can generate a path from the base by the directories and the files name.
     *
     * @param string ...$values
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

if (!function_exists('env')) {
    /**
     *
     * Get an environment variable.
     *
     * If the variable is not define
     * the default value is returned.
     *
     * @param string $key
     * @param mixed  $default
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
