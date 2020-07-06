<?php

namespace Testing\Security;

use Imperium\Security\Hashing\Hash;
use Imperium\Testing\Unit;

class HashTest extends Unit
{
    public function test()
    {
        $valid = 'secret';
        $encrypt = (new Hash($valid))->generate();
        $this->different($valid, $encrypt);
        $this->success((new Hash($valid))->valid($encrypt));
    }
}
