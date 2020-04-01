<?php

namespace Testing\Container {


    use App\Application;
    use Eywa\Cache\FileCache;
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
        public function testMultiple()
        {
            $this->assertInstanceOf(Application::class, ioc(Application::class, ['env' => 'dev','table' => 'users']));
            $this->assertInstanceOf(Application::class, ioc(Application::class));
            $this->assertInstanceOf(FileCache::class, ioc('cache'));
        }

        public function testException()
        {
            $this->expectException(Kedavra::class);
            ioc('a');
        }

        /**
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function testSuccess()
        {
            $this->assertInstanceOf(FileCache::class, ioc('cache'));
        }
    }
}
