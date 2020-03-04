<?php


namespace Testing\Http {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use PHPUnit\Framework\TestCase;

    class RequestTest extends TestCase
    {

        /**
         *
         */
        private Request $request;

        public function setUp(): void
        {
            $this->request = new Request();
        }

        /**
         * @throws Kedavra
         */
        public function test_request()
        {
            $this->expectException(Kedavra::class);
            $this->request->query()->get('a');
            $this->request->request()->get('a');
            $this->request->attribute()->get('a');
            $this->request->cookie()->get('a');
            $this->request->server()->get('a');
        }
        public function test_ip()
        {
            $this->assertEquals(LOCALHOST_IP,$this->request->ip());
        }
    }
}