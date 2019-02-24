<?php


namespace Testing\hash;


use Imperium\Security\Hashing\Hash;
use PHPUnit\Framework\TestCase;

class HashTest  extends TestCase
{

    /**
     * @throws \Exception
     */
    public function test_hash()
    {
        $this->assertNotEmpty(bcrypt('a'));

        $this->assertFalse(check('a',bcrypt('aa')));

        $this->assertFalse(check('alex',bcrypt('alexandra')));

        $this->assertTrue(check('linux',bcrypt('linux')));

        $this->assertFalse(Hash::need_rehash(bcrypt('aze')));

    }
}