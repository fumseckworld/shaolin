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
            $this->assertTrue($this->visit('/')->has('h1'));
            $this->assertTrue($this->visit('/')->has('<h1>Welcome</h1>'));
            $this->assertEquals(1, $this->visit('/')->sum('<h1>'));
            $this->assertEquals(0, $this->visit('/')->sum('<h2>'));
            $this->assertTrue($this->visit('/error')->success());
            $this->assertEquals(200, $this->visit('/')->status());
            $this->assertTrue($this->visit('/')->is(200));
            $this->assertTrue($this->visit('/e')->to('/error'));
            $this->assertTrue($this->visit('/e')->redirect());
            $this->assertStringContainsString('hello marc', $this->visit('/hello/marc')->content());
        }
    }
}
