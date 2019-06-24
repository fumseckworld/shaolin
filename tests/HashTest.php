<?php


namespace Testing {


    use Imperium\Exception\Kedavra;
    use Imperium\Security\Hashing\Hash;
    use PHPUnit\Framework\TestCase;

    class HashTest extends TestCase
    {
        /**
         * @var Hash
         */
        private $hash;

        public function setUp(): void
        {
            $this->hash = new Hash('data');
        }

        public function test_generate()
        {
            $this->assertNotEmpty($this->hash->generate());
        }

        /**
         * @throws Kedavra
         */
        public function test_valid()
        {
            $this->assertTrue($this->hash->valid($this->hash->generate()));
            $this->assertFalse($this->hash->valid((new Hash('a'))->generate()));
        }
    }
}