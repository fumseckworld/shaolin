<?php


namespace Testing {


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Exception\Kedavra;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\Response;

    class RouterTest extends TestCase
    {

        private function router(string $url,string $method): Response
        {
            return app()->router(new ServerRequest($method,$url))->run();
        }


        /**
         * @throws \Exception
         */
        public function test_get()
        {
            $this->assertTrue($this->router('/',GET)->isSuccessful());
        }
        public function test_post()
        {
            $this->expectException(Kedavra::class);

            $this->assertFalse($this->router('/u/willy/p',POST)->isSuccessful());
        }

        public function test_redirect()
        {
            $response = $this->router('/alex/',GET)->send();
            $this->assertEquals(302,$response->getStatusCode());
            $this->assertTrue($response->isRedirection());
            $this->assertTrue($response->isRedirect('/error'));
            $this->assertTrue($response->isRedirect(route('404')));
        }

        public function test_params()
        {
            $this->assertEquals('/edit/willy/20',app()->url('u','willy',20));
        }


        public function test_url()
        {
            $this->assertEquals("/",app()->url('root'));
        }

        public function test_root()
        {
            $this->assertTrue($this->router('/',GET)->isOk());
        }

        public function test_content()
        {
            $this->assertStringContainsString('<h1>welcome</h1>',$this->router('/',GET)->getContent());
        }

        public function test_args()
        {
            $this->assertTrue($this->router('/edit/willy/20',GET)->isOk());
        }

    }
}