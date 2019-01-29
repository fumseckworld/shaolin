<?php

namespace Testing\mysql\dumper {

    use Testing\DatabaseTest;

    class DumperTest extends DatabaseTest
    {
        public function test_dump()
        {
            $this->assertTrue($this->mysql()->dump(true,'',''));
            $this->assertTrue($this->mysql()->dump(false,'base','helper'));
        }
    }
}
