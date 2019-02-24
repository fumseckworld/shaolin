<?php

namespace Testing\dir {

    use Imperium\File\File;
    use PHPUnit\Framework\TestCase;
    use Imperium\Directory\Dir;

    class DirTest extends TestCase
    {
        /**
         * @throws \Exception
         */
        public function test_dir_create_and_remove()
        {
            $dir = "code";
            $this->assertTrue(Dir::create($dir));
            $this->assertTrue(File::create("$dir/app.json"));
            $this->assertTrue(Dir::clear($dir));
            $this->assertTrue(File::not_exist("$dir/app.json"));
            $this->assertTrue(Dir::is($dir));
            $this->assertTrue(Dir::remove($dir));
        }
    }
}
