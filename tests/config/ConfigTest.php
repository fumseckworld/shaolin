<?php

namespace Testing\config {


    use PHPUnit\Framework\TestCase;

    class ConfigTest extends TestCase
    {

        /**
         * @throws \Exception
         */
        public function test_get()
        {
            $this->assertEquals('zen',config('db.example','base'));
            $this->assertEquals('root',config('db.example','password'));
            $this->assertEquals('root',config('db.example','username'));
            $this->assertEquals(['phinxlog'],config('db.example','hidden_tables'));
            $this->assertEquals([],config('db.example','hidden_bases'));
        }

        public function test_exception()
        {
            $this->expectException(\Exception::class);
            config('a','a');
        }
    }
}