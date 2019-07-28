<?php


namespace Testing {


    use Imperium\Exception\Kedavra;
	use Imperium\Routing\Route;
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
            $this->assertEquals([],$request->args());
        }

        /**
         * @throws Kedavra
         */
        public function test_add_route()
        {
            $this->assertTrue(Route::manage()->create(['id' => 'id','name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue(Route::manage()->update(Route::manage()->by('imperium')->id,['name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue(Route::manage()->del('imperium'));
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
            $this->assertEquals('/imperium/diff/#AEA',app()->url('commit','imperium','#AEA'));
            $this->assertTrue($this->visit(app()->url('repository','willy','imperium','master'))->call()->send()->isOk());
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
         4*/
        public function test_content()
        {
            $this->assertNotEmpty($this->router('/',GET)->call()->send()->getContent());
        }


    }
}