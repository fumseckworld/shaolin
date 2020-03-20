<?php


namespace Testing\Container {


    use App\Application;
    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;
    use ReflectionException;

    class IocTest extends Unit
    {
        /**
         *
         * @throws Kedavra
         *
         * @throws ReflectionException
         *
         */
        public function test_multiple()
        {
            $this->assertInstanceOf(Application::class, ioc(Application::class, ['env'=> 'dev','table'=>'users']));
        }
    }
}
