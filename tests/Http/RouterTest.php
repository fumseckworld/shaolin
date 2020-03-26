<?php


namespace Testing\Http {

    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;
    use ReflectionException;

    class RouterTest extends Unit
    {
        /**
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function test()
        {
            $this->assertTrue($this->visit('/')->success());
            $this->assertTrue($this->visit('/error')->success());
            $this->assertTrue($this->visit('/e')->to('/error'));
            $this->assertStringContainsString('hello marc', $this->visit('/hello/marc')->content());
        }
    }
}
