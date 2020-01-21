<?php


namespace Testing\Http {


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

        public function test_request()
        {
            $this->assertNull($this->request->query()->get('a'));
            $this->assertNull($this->request->request()->get('a'));
            $this->assertEmpty($this->request->attribute()->get('a'));
            $this->assertNull($this->request->cookie()->get('a'));
            $this->assertNull($this->request->server()->get('a'));
            $this->assertNull($this->request->file()->get('a'));
            $this->assertEmpty($this->request->content());
        }
    }
}