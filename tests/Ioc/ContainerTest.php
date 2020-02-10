<?php


namespace Testing\Ioc {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Http\Routing\Router;
    use Eywa\Http\View\View;
    use Eywa\Ioc\Container;
    use PHPUnit\Framework\TestCase;

    class ContainerTest extends TestCase
    {

        public function test_has()
        {
            $this->assertTrue(Container::ioc()->has(Connect::class));
            $this->assertTrue(Container::ioc()->has(Router::class));
        }

        public function test_get()
        {
            $this->assertInstanceOf(Connect::class,Container::ioc()->get(Connect::class));
        }

        public function test_make()
        {
            $this->assertInstanceOf(View::class,Container::ioc()->make(View::class,['view' => 'a','title'=>'a','description'=>'a']));
        }
        public function test_set()
        {
            $this->assertInstanceOf(View::class,Container::ioc()->set(View::class,new View('a','a','description'))->get(View::class));
        }

        public function test_debug()
        {
            $this->assertNotEmpty(Container::ioc()->debug(Connect::class));
        }

        public function test_call()
        {
            $this->assertEquals(3306,Container::ioc()->call(Connect::class,'port'));
        }
    }
}