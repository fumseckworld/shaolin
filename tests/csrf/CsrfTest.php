<?php

namespace Testing\csrf {


    use Exception;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Middleware\CsrfMiddleware;
    use PHPUnit\Framework\TestCase;

    class CsrfTest extends TestCase
    {

        /**
         * @var CsrfMiddleware
         */
        private $csrf;

        public function setUp()
        {
            $this->csrf = new CsrfMiddleware();
        }

        /**
         * @throws Exception
         */
        public function test()
        {
            $token = $this->csrf->generate();

            $this->assertNotEmpty($token);
        }

        /**
         * @throws Exception
         */
        public function test_exec()
        {
            $this->expectException(Exception::class);

            $request = new ServerRequest('POST',url('del',POST));

            app()->router($request)->run();

        }
    }
}