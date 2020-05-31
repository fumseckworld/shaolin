<?php

namespace Testing\Helpers;

use PHPUnit\Framework\TestCase;

class AdminHelpersTest extends TestCase
{

    public function testDef()
    {
        $value = 'a';
        $b = '';
        $this->assertFalse(def(''));
        $this->assertFalse(def($value, $b));
        $this->assertTrue(def($value));
    }

    public function testBase()
    {
        $this->assertNotEmpty(base());
        $this->assertNotEmpty(base('imperium'));
        $this->assertStringContainsString('imperium', base('imperium'));
    }

    public function testEnv()
    {
        $this->assertNotEmpty(env('DB_DRIVER'));
        $this->assertNull(env('a'));
        $this->assertNotNull(env('DB_USERNAME'));
    }
}
