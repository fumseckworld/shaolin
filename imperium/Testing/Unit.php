<?php


namespace Imperium\Testing {


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Cache\Cache;
    use Imperium\Exception\Kedavra;
    use Imperium\Routing\RouteResult;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Response;

    class Unit extends TestCase
    {

        /**
         *
         * @param string $url
         * @param string $method
         *
         * @return RouteResult|RedirectResponse
         *
         * @throws Kedavra
         *
         */

        public function visit(string $url,string $method = GET)
        {
           return  app()->router(new ServerRequest($method,$url))->search();
        }


        /**
         *
         * Get the cache instance
         *
         * @return Cache
         *
         */
        public function cache(): Cache
        {
            return new Cache();
        }
    }
}