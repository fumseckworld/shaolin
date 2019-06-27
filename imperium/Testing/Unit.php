<?php


namespace Imperium\Testing {


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Exception\Kedavra;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\Response;

    class Unit extends TestCase
    {

        /**
         *
         * @param string $url
         * @param string $method
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */

        public function visit(string $url,string $method = GET): Response
        {
            return app()->router(new ServerRequest($method,$url))->run();
        }

    }
}