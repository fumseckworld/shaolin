<?php

namespace Imperium\Router {

    use Exception;
    use Imperium\Route\Route;

    /**
     *
     * Router management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class Router extends Route
    {
        /**
         *
         * @param string $url
         *
         * @param string $namespace
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public static function run(string $url,string $namespace)
        {
            return (new Route())->namespace($namespace)->capture($url)->launch();
        }

    }
}