<?php

namespace tests\dumper;

use Testing\DatabaseTest;

class DumperTest extends DatabaseTest
{
    public function test_dump()
    {
        $this->assertTrue($this->mysql()->dump(true,'',''));
        $this->assertTrue($this->mysql()->dump(false,'base','helper'));

        $this->assertTrue($this->postgresql()->dump(true,'',''));
        $this->assertTrue($this->postgresql()->dump(false,'base','helper'));
        
        $this->assertTrue($this->sqlite()->dump(true,'',''));
        $this->assertFalse($this->sqlite()->dump(false,'base','helper'));
    }
}
