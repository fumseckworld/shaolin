<?php


namespace Imperium\Request {


    class Request
    {
        /**
         *
         * Get all params
         *
         * @return array
         */
        public static function all(): array
        {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals()->request->all();
        }

        /**
         *
         * Get a value
         *
         * @param $key
         *
         * @return mixed
         *
         */
        public static function get($key)
        {
            return collection(self::all())->get($key);
        }

        /**
         * @return array
         */
        public static function server(): array
        {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals()->server->all();
        }

        /**
         *
         * @return \Symfony\Component\HttpFoundation\Request
         *
         */
        public static function request()
        {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        }

        /**
         *
         * @return \Symfony\Component\HttpFoundation\ServerBag
         */
        public static  function serve()
        {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals()->server;
        }
    }
}