<?php

namespace Testing\pgsql\import {

    use Testing\DatabaseTest;

    /**
     *
     */
    class ImportTest extends DatabaseTest
    {

        public function test_import()
        {
            $base = "marion";
            $this->assertTrue($this->postgresql()->bases()->copy($base));
        }
    }
}
