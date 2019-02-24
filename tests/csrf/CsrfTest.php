<?php

namespace Testing\csrf {


    use Exception;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Security\Csrf\Csrf;
    use PHPUnit\Framework\TestCase;

    class CsrfTest extends TestCase
    {

        /**
         * @var Csrf
         *
         */
        private $csrf;

        /**
         * @throws Exception
         */
        public function setUp():void
        {
            $this->csrf = new Csrf(app()->session());
        }

        /**
         * @throws Exception
         */
        public function test()
        {
            $token = $this->csrf->token();

            $this->assertNotEmpty($token);
        }

        /**
         * @throws Exception
         */
        public function test_exec()
        {
            $this->expectException(Exception::class);

            $request = new ServerRequest('POST',name('del',POST));

            app()->router($request,'Shaolin\Controllers','a')->run();

        }


    }
}