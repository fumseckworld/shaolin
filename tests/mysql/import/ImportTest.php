<?php

namespace Testing\mysql\import {

    use Testing\DatabaseTest;

    /**
     *
     */
    class ImportTest extends DatabaseTest
    {

        public function test_import()
        {
            $base = "marion";
            $this->assertTrue($this->mysql()->bases()->copy($base));
        }
    }
}
