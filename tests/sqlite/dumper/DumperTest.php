<?php

namespace tests\dumper;

use Testing\DatabaseTest;

class DumperTest extends DatabaseTest
{
    public function test_dump()
    {
        $this->assertTrue($this->sqlite()->dump(true,'',''));
        $this->assertFalse($this->sqlite()->dump(false,'base','helper'));
    }
}
