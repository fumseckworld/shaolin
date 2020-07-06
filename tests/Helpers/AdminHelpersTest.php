<?php

namespace Testing\Helpers;

use Faker\Provider\Base;
use Imperium\Testing\Unit;

class AdminHelpersTest extends Unit
{

    public function testDef()
    {
        $value = 'a';
        $b = '';
        $this->failure(def(''))->failure(def($value, $b))->success(def($value));
    }

    public function testBase()
    {
        $this->def(base(), base('imperium'))
            ->success(
                is_dir(base('bidon', 'alexandre')),
                file_exists(base('bidon', 'a.php')),
                unlink(base('bidon', 'a.php')),
                rmdir(base('bidon', 'alexandre'))
            );
        $this->assertStringContainsString('imperium', base('imperium'));
    }

    public function testImperium()
    {
        $this->identic('a', imperium('', 'a'));
    }

    public function testLogged()
    {
        $this->failure(logged())->success(guest());
    }
    public function testPassword()
    {
        $this->def(secure_password(''))
            ->success(check_password('eywa', secure_password('eywa')))
            ->different('eywa', secure_password('eywa'));
    }

    public function testSnake()
    {
        $this->identic('linux_is_super', camel_to_snake('LinuxIsSuper'))
            ->identic('LinuxIsSuper', snake_to_camel('linux_is_super'))
            ->success(is_snake('a_a_aa_adz'))
            ->failure(is_snake('LinuxIsSuper'));
    }

    public function testCamel()
    {
        $this->identic('LinuxIsSuper', snake_to_camel('linux_is_super'))
            ->success(is_camel('LinuxIsSuper'));
    }

    public function testIsSlug()
    {
        $this->success(is_slug('a-ala-12'))->failure(is_slug('ALEXANDRA-2'));
    }

    public function testIsInteger()
    {
        $this->failure(is_integer('1'))->success(is_integer(2))->failure(is_integer(true));
    }
}
