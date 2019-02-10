<?php

namespace Testing\csrf {


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
         * @throws \Exception
         */
        public function test()
        {
            $token = $this->csrf->generate();

            $this->assertNotEmpty($token);
        }

    }
}