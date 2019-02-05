<?php


namespace Testing\app {


    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\Request;

    class AppTest extends TestCase
    {

        /**
         * @throws \Exception
         */
        public function test_request()
        {
            $this->assertInstanceOf(Request::class,app()->request());
        }

    }
}