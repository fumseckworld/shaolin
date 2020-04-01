<?php

namespace Testing\Helpers;

use Eywa\Application\App;
use Eywa\Database\Connexion\Connect;
use Eywa\Database\Query\Sql;
use Eywa\Testing\Unit;

class AdminTest extends Unit
{
    public function testSnake()
    {
        $this->assertFalse(is_snake('ALaMode'));
        $this->assertFalse(is_snake('aMode'));
        $this->assertFalse(is_snake('A_mode'));
        $this->assertFalse(is_snake('a_aAAEZode'));
        $this->assertFalse(is_snake('a_Aode'));
        $this->assertFalse(is_snake(''));
        $this->assertTrue(is_snake('a_superb_linux_operating_system'));
        $this->assertEquals('SuperbLinuxOperatingSystem', snake_to_camel('superb_linux_operating_system'));
    }

    public function tesCamel()
    {
        $this->assertTrue(is_camel('ALaMode'));
        $this->assertFalse(is_camel('aMode'));
        $this->assertFalse(is_camel('A_mode'));
        $this->assertFalse(is_camel('a_aAAEZode'));
        $this->assertFalse(is_camel('a_Aode'));
        $this->assertFalse(is_camel(''));
        $this->assertTrue(is_camel(snake_to_camel('a_superb_linux_operating_system')));
        $this->assertTrue(is_camel('UsersController'));
        $this->assertEquals('SuperbLinuxOperatingSystem', snake_to_camel('superb_linux_operating_system'));
    }

    public function testSlug()
    {
        $this->assertFalse(is_slug('A-AZA-AZDAZ-QQQX'));
        $this->assertFalse(is_slug('a_azda_azdazazzezcredczscdsdc_rzz'));
        $this->assertFalse(is_slug('A_HEd_adzedzEREZAczc_rzz'));
        $this->assertFalse(is_slug('Alouer'));
        $this->assertFalse(is_slug('A'));
        $this->assertFalse(is_slug(''));
        $this->assertTrue(is_slug('post'));
        $this->assertTrue(is_slug('post-3'));
        $this->assertTrue(is_slug(sluglify('I am a title')));
        $this->assertEquals('i-am-a-title', sluglify('I am a title'));
        $this->assertEquals('i-am-a-title', sluglify('I , am ,  a , title'));
        $this->assertEquals('i-am-a-title-super-easy', sluglify('I  am a title. super easy'));
        $this->assertEquals('blog', sluglify('blog'));
        $this->assertEmpty(sluglify('a'));
    }

    public function testControllers()
    {
        $this->assertNotEmpty(controllers_directory());
        $this->assertIsArray(controllers_directory());
    }

    public function testViews()
    {
        $this->assertNotEmpty(views());
        $this->assertIsArray(views());
    }

    public function testHttps()
    {
        $this->assertFalse(https());
    }
    public function testUrl()
    {
        $this->assertEquals('/blog', url('blog'));
        $this->assertEquals('/comments/all', url('comments', 'all'));
        $this->assertEquals('/comments/edit/1', url('comments', 'edit', '1'));
    }
    public function testFiles()
    {
        $this->assertIsArray(files(base('*.md')));
        $this->assertIsArray(files(base('eywa', '*', '*.php')));
        $this->assertEmpty(files(base('*.php')));
    }

    public function testBase()
    {
        $this->assertNotEmpty(base('config'));
        $this->assertStringContainsString('/config', base('config'));
        $this->assertNotEmpty(base('app', 'Views'));
        $this->assertStringContainsString('/app/Views', base('app', 'Views'));
        $this->assertNotEmpty(base(''));
    }
    public function testApp()
    {
        $this->assertInstanceOf(App::class, app());
        $this->assertInstanceOf(Connect::class, production());
        $this->assertInstanceOf(Connect::class, development());
        $this->assertNotEquals(production(), development());
    }

    public function testSql()
    {
        $this->assertInstanceOf(Sql::class, \sql('auth'));
    }
}
