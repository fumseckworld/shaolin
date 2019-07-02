<?php


namespace Testing {


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

            $this->assertTrue($this->route()->save_route(['id' => 'id','name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue($this->route()->update_route(app()->routes()->by_or_fail('name','imperium')->id,['name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue($this->route()->remove_route( 'imperium'));
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
            $this->assertEquals('/imperium/diff/fff',app()->url('commit','imperium','fff'));
            $this->assertTrue($this->visit(app()->url('commit','imperium','fff'))->call()->send()->isOk());
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


    }
}