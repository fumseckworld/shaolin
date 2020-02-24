<?php

declare(strict_types=1);

namespace Eywa\Html\Form {




    abstract class Form
    {

        /**
         *
         * The form method
         *
         */
        public static string $method = POST;

        /**
         *
         * The form route
         *
         */
        public static string $route = '';

        /**
         *
         * All form rules
         *
         */
        public static array $rules = [];



        /**
         *
         * Get the form method
         *
         * @return string
         *
         */
        public function method()
        {
            return static::$method;
        }

        /**
         *
         * Get the form route
         *
         * @return string
         *
         */
        public function route()
        {
            return static::$route;
        }
    }
}