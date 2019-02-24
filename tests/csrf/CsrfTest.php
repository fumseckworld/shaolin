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
         * @var ServerRequest
         */
        private $request;

        /**
         * @throws Exception
         */
        public function setUp():void
        {
            $this->csrf = new Csrf(app()->session());
            $this->request = new ServerRequest('POST',name('del',POST));
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


            app()->router($this->request,'Shaolin\Controllers','core')->run();

        }

        /**
         * @throws Exception
         */
        public function test_check()
        {
            $this->expectException(Exception::class);
            $this->csrf->check($this->request);
        }

    }
}