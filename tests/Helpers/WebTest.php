<?php

namespace Testing\Helpers {

    use Carbon\Carbon;
    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;

    /**
     * Class WebTest
     * @package Testing\Helpers
     */
    class WebTest extends Unit
    {
        /**
         *
         */
        public function testCollect()
        {
            $this->assertInstanceOf(Collect::class, collect());
        }

        /**
         *
         */
        public function testEnv()
        {
            $this->assertEquals('not_found', env('A', 'not_found'));
            $this->assertEquals(env('DB_DRIVER', ''), env('DB_DRIVER', ''));
        }

        /**
         *
         */
        public function testNow()
        {
            $this->assertInstanceOf(Carbon::class, now());
        }

        /**
         * @throws Kedavra
         */
        public function testRoot()
        {
            $this->assertEquals('/', root());
        }

        /**
         * @throws Kedavra
         */
        public function testFlash()
        {
            $this->assertEquals('', flash());
        }

        /**
         * @throws Kedavra
         */
        public function testSum()
        {
            $this->assertEquals(5, sum([0,1,2,3,4]));
            $this->assertEquals(5, sum(5));
            $this->assertEquals(5, sum('equal'));
        }

        public function testSumError()
        {
            $this->expectException(Kedavra::class);
            sum(false);
        }
        /**
         *
         */
        public function testCli()
        {
            $this->assertTrue(cli());
            $this->assertFalse(not_cli());
        }

        /**
         * @throws Kedavra
         */
        public function testRoute()
        {
            $this->assertEquals('/', route('root'));
            $this->assertEquals('hello/marc', route('hello', ['name' => 'marc']));
        }

        /***
         * @throws Kedavra
         */
        public function testAgo()
        {
            $this->assertNotEmpty(ago('2020-02-17'));
        }
        public function testTotal()
        {
            $this->assertEquals('1', total(1));
            $this->assertEquals('10', total(10));
            $this->assertEquals('100', total(100));
            $this->assertEquals('1 K', total(1_000));
            $this->assertEquals('10 K', total(10_000));
            $this->assertEquals('100 K', total(100_000));
            $this->assertEquals('1 M', total(1_000_000));
            $this->assertEquals('10 M', total(10_000_000));
            $this->assertEquals('100 M', total(100_000_000));
            $this->assertEquals('1 B', total(1_000_000_000));
        }

        /**
         *
         */
        public function testAppend()
        {
            $x = '';
            append($x, 'php', 'is', 'super');
            $this->assertEquals('phpissuper', $x);
        }

        /**
         *
         */
        public function testPair()
        {
            $this->assertTrue(is_pair(2));
            $this->assertTrue(is_pair(4));
            $this->assertFalse(is_pair(5));
            $this->assertFalse(is_pair(15));
            $this->assertFalse(is_pair(15));
        }

        /**
         * @throws Exception
         */
        public function test()
        {
            $this->assertFalse(mobile());
        }
    }
}
