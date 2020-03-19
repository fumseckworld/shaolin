<?php


namespace Testing\Helpers {

    use Carbon\Carbon;
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
        public function test_collect()
        {
            $this->assertInstanceOf(Collect::class, collect());
        }

        /**
         *
         */
        public function test_env()
        {
            $this->assertEquals('not_found', env('A', 'not_found'));
            $this->assertEquals(env('DB_DRIVER', ''), env('DB_DRIVER', ''));
        }

        /**
         *
         */
        public function test_now()
        {
            $this->assertInstanceOf(Carbon::class, now());
        }

        /**
         * @throws Kedavra
         */
        public function test_root()
        {
            $this->assertEquals('/', root());
        }

        /**
         *
         */
        public function test_cli()
        {
            $this->assertTrue(cli());
            $this->assertFalse(not_cli());
        }

        /**
         * @throws Kedavra
         */
        public function test_route()
        {
            $this->assertEquals('/', route('root'));
        }

        /**
         *
         */
        public function test_total()
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
        public function test_append()
        {
            $x = '';
            append($x, 'php', 'is', 'super');
            $this->assertEquals('phpissuper', $x);
        }

        /**
         *
         */
        public function test_is_pair()
        {
            $this->assertTrue(is_pair(2));
            $this->assertTrue(is_pair(4));
            $this->assertFalse(is_pair(5));
            $this->assertFalse(is_pair(15));
            $this->assertFalse(is_pair(15));
        }

        /**
         * @throws \Exception
         */
        public function test()
        {
            $this->assertFalse(mobile());
        }
    }
}
