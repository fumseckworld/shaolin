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

    public function testImperium()
    {
        $this->assertEquals('a', imperium('', 'a'));
    }

    public function testLogged()
    {
        $this->assertFalse(logged());
        $this->assertTrue(guest());
    }
    public function testPassword()
    {
        $this->assertNotEmpty(secure_password(''));
        $this->assertTrue(check_password('eywa', secure_password('eywa')));
        $this->assertNotEquals('eywa', secure_password('eywa'));
    }

    public function testSnake()
    {
        $this->assertEquals('linux_is_super', camel_to_snake('LinuxIsSuper'));
        $this->assertEquals('LinuxIsSuper', snake_to_camel('linux_is_super'));
        $this->assertTrue(is_snake('a_a_aa_adz'));
        $this->assertFalse(is_snake('LinuxIsSuper'));
    }

    public function testCamel()
    {
        $this->assertEquals('LinuxIsSuper', snake_to_camel('linux_is_super'));
        $this->assertTrue(is_camel('LinuxIsSuper'));
    }

    public function testIsSlug()
    {
        $this->assertTrue(is_slug('a-ala-12'));
        $this->assertFalse(is_slug('ALEXANDRA-2'));
    }

    public function testIsInteger()
    {
        $this->assertFalse(is_integer('1'));
        $this->assertTrue(is_integer(2));
        $this->assertFalse(is_integer(true));
    }
}
