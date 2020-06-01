<?php

namespace Testing\Helpers;

use Faker\Provider\Base;
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
        $this->assertTrue(is_dir(base('bidon/alexandre')));
        $this->assertTrue(rmdir(base('bidon', 'alexandre')));
        $this->assertTrue(is_dir(base('alexandre')));
        $this->assertTrue(rmdir(base('alexandre')));
        $this->assertTrue(file_exists(base('bidon', 'a', 'b', 'c', 'home.html')));
        unlink(base('bidon', 'a', 'b', 'c', 'home.html'));
        rmdir(base('bidon', 'a', 'b', 'c'));
        rmdir(base('bidon', 'a', 'b'));
        rmdir(base('bidon', 'a'));

        $this->assertTrue(file_exists(base('bidon/a/b/c/home.html')));
        unlink(base('bidon', 'a', 'b', 'c', 'home.html'));
        rmdir(base('bidon', 'a', 'b', 'c'));
        rmdir(base('bidon', 'a', 'b'));
        rmdir(base('bidon', 'a'));
    }

    public function testEnv()
    {
        $this->assertNotEmpty(env('DB_DRIVER'));
        $this->assertNull(env('a'));
        $this->assertNotNull(env('DB_USERNAME'));
    }
}
