<?php

namespace Testing\Http {

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Testing\Unit;

    class RequestTest extends Unit
    {
        /**
         */
        private Request $request;
        /**
         * @var Request
         */
        private Request $args;

        /**
         * @throws Kedavra
         */
        public function setUp(): void
        {
            $this->request = new Request();
            $this->args = new Request(
                [],
                [],
                [],
                [],
                [],
                [
                    'name' => 'Willy',
                    'username' => 'fumseckworld',
                    'age' => 31
                ]
            );
        }

        public function testsPost()
        {
            $this->assertNull($this->request->request()->get('a'));
            $this->assertEquals([], $this->request->request()->all());
            $this->assertFalse($this->request->request()->has('a'));
        }

        public function testsGet()
        {
            $this->assertNull($this->request->query()->get('a'));
            $this->assertEquals([], $this->request->query()->all());
            $this->assertFalse($this->request->query()->has('a'));
        }

        public function testsCookie()
        {
            $this->assertNull($this->request->cookie()->get('a'));
            $this->assertEquals([], $this->request->cookie()->all());
            $this->assertFalse($this->request->cookie()->has('a'));
        }

        public function testsServer()
        {
            $this->assertNull($this->request->server()->get('a'));
            $this->assertEquals([], $this->request->server()->all());
            $this->assertFalse($this->request->server()->has('a'));
        }

        public function testIp()
        {
            $this->assertEquals('127.0.0.1', $this->request->ip());
        }

        public function testArgs()
        {
            $this->assertEquals([], $this->request->args()->all());
            $this->assertEquals(
                [
                    'name' => 'Willy',
                    'username' => 'fumseckworld',
                    'age' => 31
                ],
                $this->args->args()->all()
            );
            $this->assertEquals(31, $this->args->args()->get('age', 58));
            $this->assertEquals('fumseckworld', $this->args->args()->get('username', 'will'));
            $this->assertEquals('Willy', $this->args->args()->get('name', 'will'));
            $this->assertEquals('alex', $this->args->args()->get('names', 'alex'));
        }

        public function testSecure()
        {
            $this->assertFalse($this->request->secure());
        }

        public function testLocal()
        {
            $this->assertTrue($this->request->local());
        }
    }
}
