<?php

namespace Testing\sqlite\dumper {

    use Testing\DatabaseTest;

    class DumperTest extends DatabaseTest
    {
        public function test_dump()
        {
            $this->assertTrue($this->mysql()->dump(true));
            $this->assertFalse($this->mysql()->dump(false,'base','helper'));
        }
    }
}
