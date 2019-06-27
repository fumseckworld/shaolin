<?php


namespace Testing {


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Exception\Kedavra;
    use Imperium\Testing\Unit;
    use Symfony\Component\HttpFoundation\Response;

    /**
     * Class RouterTest
     * @package Testing
     */
    class RouterTest extends Unit
    {

        /**
         * @param string $url
         * @param string $method
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        private function router(string $url,string $method): Response
        {
            return $this->visit($url,$method);
        }


        /**
         * @throws Kedavra
         */
        public function test_add_route()
        {
            $request = new ServerRequest(GET,'/');
            $this->assertTrue(app()->router($request)->save_route( ['id' => 'id','name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue(app()->router($request)->update_route( 'imperium',['id' => 'id','name' =>'imperium', 'url' => '/imperiums','controller' => 'IMperiumController' ,'action' => 'define','method' => GET]));
            $this->assertTrue(app()->router($request)->remove_route( 'imperium'));
        }

        /**
         * @throws Kedavra
         */
        public function test_get()
        {
            $this->assertTrue($this->router('/',GET)->isSuccessful());
        }

        /**
         * @throws Kedavra
         */
        public function test_post()
        {
            $this->expectException(Kedavra::class);

            $this->assertFalse($this->router('/u/willy/p',POST)->isSuccessful());
        }

        /**
         * @throws Kedavra
         */
        public function test_redirect()
        {
            $response = $this->router('/alex/',GET)->send();
            $this->assertEquals(302,$response->getStatusCode());
            $this->assertTrue($response->isRedirection());
            $this->assertTrue($response->isRedirect('/error'));
            $this->assertTrue($response->isRedirect(route('404')));
        }

        /**
         * @throws Kedavra
         */
        public function test_params()
        {
            $this->assertEquals('/edit/willy/20',app()->url('u','willy',20));
        }


        /**
         * @throws Kedavra
         */
        public function test_url()
        {
            $this->assertEquals("/",app()->url('root'));
        }

        /**
         * @throws Kedavra
         */
        public function test_root()
        {
            $this->assertTrue($this->router('/',GET)->isOk());
        }

        /**
         * @throws Kedavra
         */
        public function test_content()
        {
            $this->assertStringContainsString('<h1>welcome</h1>',$this->router('/',GET)->getContent());
        }

        /**
         * @throws Kedavra
         */
        public function test_args()
        {
            $this->assertTrue($this->router('/edit/willy/20',GET)->isOk());
        }

    }
}