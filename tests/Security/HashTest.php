<?php

namespace Testing\Security;

use Imperium\Security\Hashing\Hash;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    public function test()
    {
        $valid = 'secret';
        $encrypt = (new Hash($valid))->generate();
        $this->assertNotEquals($valid, $encrypt);
        $this->assertTrue((new Hash($valid))->valid($encrypt));
    }
}
