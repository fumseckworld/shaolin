<?php

namespace Testing\dir {

    use PHPUnit\Framework\TestCase;
    use Imperium\Directory\Dir;

    class DirTest extends TestCase
    {
        public function test_dir_create_and_remove()
        {
            $dir = "code";
            $this->assertTrue(Dir::create($dir));
            $this->assertTrue(Dir::is($dir));
            $this->assertTrue(Dir::remove($dir));
        }
    }
}
