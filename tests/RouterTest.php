<?php


namespace Testing {
	
	use DI\DependencyException;
	use DI\NotFoundException;
	use Imperium\Exception\Kedavra;
    use Imperium\Model\Web;
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
		 * @param  string  $url
		 * @param  string  $method
		 *
		 * @throws Kedavra
		 * @throws DependencyException
		 * @throws NotFoundException
		 * @return RedirectResponse|RouteResult
		 *
		 */
        private function router(string $url,string $method)
        {
            return $this->visit($url,$method);
        }
	
		/**
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
        public function test_route_result()
        {
            $request = $this->visit('/');
            $this->assertEquals('home',$request->action());
            $this->assertEquals('HomeController',$request->controller());
            $this->assertEquals('/',$request->url());
            $this->assertEquals('root',$request->name());
            $this->assertEquals([],$request->args());
        }

        /**
         * @throws Kedavra
         */
        public function test_add_route()
        {

            $this->assertTrue(Web::create(['id' => 'id','name' =>'imperium', 'url' => '/imperium','controller' => 'AuthController' ,'action' => 'imperium','method' => GET]));
            $this->assertTrue(Web::destroy(Web::where('name',EQUAL,'imperium')->fetch(true)->all()->id));


        }
	
		/**
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
        public function test_get()
        {
            $this->assertTrue($this->router('/',GET)->call()->send()->isSuccessful());
            
        }
	
		/**
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
        public function test_post()
        {
            $this->expectException(Kedavra::class);

            $this->assertFalse($this->router('/u/willy/p',POST)->call()->send()->isSuccessful());
        }
	
		/**
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
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
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
        public function test_root()
        {
            $this->assertTrue($this->router('/',GET)->call()->send()->isOk());
        }
	
		/**
		 * @throws DependencyException
		 * @throws Kedavra
		 * @throws NotFoundException
		 */
        public function test_content()
        {
            $this->assertNotEmpty($this->router('/',GET)->call()->send()->getContent());
        }


    }
}