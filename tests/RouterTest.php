<?php


namespace Testing {


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Exception\Kedavra;
    use Imperium\Routing\Router;
    use Imperium\Routing\RouteResult;
    use Imperium\Testing\Unit;
    use Symfony\Component\HttpFoundation\RedirectResponse;

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
         * @return RedirectResponse|RouteResult
         *
         * @throws Kedavra
         *
         */
        private function router(string $url,string $method)
        {
            return $this->visit($url,$method);
        }


        /**
         * @throws Kedavra
         */
        public function test_route_result()
        {
            $request = $this->visit('/');
            $this->assertEquals('repositories',$request->action());
            $this->assertEquals('GitController',$request->controller());
            $this->assertEquals('/',$request->url());
            $this->assertEquals('root',$request->name());
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
            $this->assertTrue($this->router('/',GET)->call()->send()->isSuccessful());
        }

        /**
         * @throws Kedavra
         */
        public function test_post()
        {
            $this->expectException(Kedavra::class);

            $this->assertFalse($this->router('/u/willy/p',POST)->call()->send()->isSuccessful());
        }

        /**
         * @throws Kedavra
         */
        public function test_redirect()
        {
            $response = $this->router('/alex/',GET);
            $this->assertEquals(302,$response->getStatusCode());
            $this->assertTrue($response->isRedirection());
            $this->assertTrue($response->isRedirect('/error'));
            $this->assertTrue($response->isRedirect(route('404')));
        }

        /**
         * @throws Kedavra
         */
        public function test_url()
        {
            $this->assertEquals('/',Router::url('root'));
            $this->assertEquals('/app',Router::url('app'));

            $this->assertEquals('/',app()->url('root'));
            $this->assertEquals('/app',app()->url('app'));
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
        public function test_root()
        {
            $this->assertTrue($this->router('/',GET)->call()->send()->isOk());
        }

        /**
         * @throws Kedavra
         */
        public function test_content()
        {
            $this->assertNotEmpty($this->router('/app',GET)->call()->send()->getContent());
        }

        /**
         * @throws Kedavra
         */
        public function test_args()
        {
            $this->assertTrue($this->router('/edit/willy/20',GET)->call()->send()->isOk());
        }

    }
}