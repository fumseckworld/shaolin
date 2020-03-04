<?php


namespace Testing\Ioc {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Router;
    use Eywa\Http\View\View;
    use Eywa\Ioc\Ioc;
    use Eywa\Message\Flash\Flash;
    use PHPUnit\Framework\TestCase;
    use ReflectionException;

    class ContainerTest extends TestCase
    {

        /**
         * @var Ioc
         */
        private Ioc $ioc;

        public function setUp(): void
        {
           $this->ioc = new Ioc();
        }

        /**
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function test_has()
        {

            $this->assertTrue($this->ioc->has(Connect::class));
            $this->assertFalse($this->ioc->has(Table::class));
            $this->assertFalse($this->ioc->has(Router::class));

        }

        /**
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function test_get()
        {
            $this->assertInstanceOf(Connect::class,$this->ioc->get(Connect::class));
            $this->assertInstanceOf(Table::class,$this->ioc->get(Table::class));

        }

        public function test_get_variable()
        {
            $this->assertInstanceOf(Flash::class,$this->ioc->get('flash'));
        }

    }
}