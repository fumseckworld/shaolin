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
        $this->def(
            base(),
            base('imperium'),
            base('imperium/Html'),
            base('core/models/Users.php')
        )
            ->success(
                is_dir(base('bidon', 'alexandre')),
                file_exists(base('bidon', 'a.php')),
                unlink(base('bidon', 'a.php')),
                rmdir(base('bidon', 'alexandre')),
                unlink(base('core', 'models', 'Users.php')),
                rmdir(base('core', 'models')),
                rmdir(base('core'))
            );
        $this->assertStringContainsString('imperium', base('imperium'));
    }

    public function testImperium()
    {
        $this->identical('a', imperium('', 'a'));
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
        $this->identical('linux_is_super', camel_to_snake('LinuxIsSuper'))
            ->identical('LinuxIsSuper', snake_to_camel('linux_is_super'))
            ->success(is_snake('a_a_aa_adz'))
            ->failure(is_snake('LinuxIsSuper'));
    }

    public function testCamel()
    {
        $this->identical('LinuxIsSuper', snake_to_camel('linux_is_super'))
            ->success(is_camel('LinuxIsSuper'));
    }

    public function testIsSlug()
    {
        $this->success(is_slug('a-ala-12'))->failure(is_slug('ALEXANDRA-2'));
    }


    public function testsSluglify()
    {
        $this->success(
            is_slug(sluglify('a.ape.ae')),
        );
        $this->identical('a-super-website', sluglify('a.super.website'));
        $this->identical('a-axandra', sluglify('a-axandra'));
        $this->identical('a-super-website', sluglify('a,super,website'));
        $this->identical('a-super-website-alexandra-a', sluglify('a. super. website, alexandra.a'));
        $this->identical('a-super-website', sluglify('a.super.website'));
        $this->identical('a-tomato-superb', sluglify('a tomato superb'));
        $this->identical('a-tomato-superb', sluglify('a_tomato_superb'));
        $this->identical('a-tomato-superb', sluglify('a, tomato, superb'));
        $this->identical('ulyse-super-heros', sluglify('ULYSE, SUPER, HEROS'));
        $this->empty(sluglify(''));
    }
}
