<?php

namespace Testing\Security;

use DI\DependencyException;
use DI\NotFoundException;
use Nol\Security\Hashing\Hash;
use Nol\Testing\Unit;

class HashTest extends Unit
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function test()
    {
        $valid = 'secret';
        $encrypt = (new Hash($valid))->generate();
        $this->different($valid, $encrypt);
        $this->success((new Hash($valid))->valid($encrypt));
    }
}
