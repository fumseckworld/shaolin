<?php


namespace Imperium\Url {


    use Exception;
    use Imperium\Request\Request;

    class Url
    {
        /**
         *
         * Get the complete url from a route name
         *
         * @param string $route_name
         * @param string $method
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function route(string $route_name,string $method = GET)
        {
            return url($route_name,$method);
        }


    }
}