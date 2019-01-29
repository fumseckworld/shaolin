<?php

namespace Testing\pgsql\dumper {

    use Testing\DatabaseTest;

    class DumperTest extends DatabaseTest
    {
        public function test_dump()
        {
            $this->assertTrue($this->postgresql()->dump(true,'',''));
            $this->assertTrue($this->postgresql()->dump(false,'base','helper'));
        }
    }
}
