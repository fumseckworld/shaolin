<?php

namespace tests\dumper;

use Testing\DatabaseTest;

/**
 *
 */
class ImportTest extends DatabaseTest
{

    public function test_import()
    {
        $base = "marion";
        $this->assertTrue($this->sqlite()->bases()->copy($base));
    }
}
