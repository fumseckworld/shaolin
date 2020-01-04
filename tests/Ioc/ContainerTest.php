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
            $this->assertTrue(Container::ioc(Connect::class)->has());
            $this->assertTrue(Container::ioc(Router::class)->has());
        }

        public function test_get()
        {
            $this->assertInstanceOf(Connect::class,Container::ioc(Connect::class)->get());
        }

        public function test_make()
        {
            $this->assertInstanceOf(View::class,Container::ioc(View::class)->make(['view' => 'a','title'=>'a','description'=>'a']));
        }
        public function test_set()
        {
            $this->assertInstanceOf(View::class,Container::ioc(View::class)->set(new View('a','a','description'))->get());
        }

        public function test_debug()
        {
            $this->assertNotEmpty(Container::ioc(Connect::class)->debug());
        }

        public function test_call()
        {
            $this->assertEquals(3306,Container::ioc(Connect::class)->call('port'));
        }
    }
}