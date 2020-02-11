<?php
	
	namespace Testing\Crypt
	{


        use Exception;
        use Eywa\Exception\Kedavra;
        use Eywa\Security\Crypt\Crypter;
        use Eywa\Testing\Unit;

        class CryptTest extends Unit
		{

            /**
             * @throws Exception
             */
			public function test_generate_key()
			{
				$this->assertNotEmpty(Crypter::generateKey());
			}

            /**
             * @throws Kedavra
             */
			public function test_encrypt_and_decryt()
			{
				$this->assertNotEmpty($this->crypter()->encrypt('ALEX'));
				$this->assertNotEmpty($this->crypter()->encryptString('ALEX'));
				$this->assertEquals('ALEX',$this->crypter()->decrypt($this->crypter()->encrypt('ALEX')));
				$this->assertEquals('ALEX',$this->crypter()->decryptString($this->crypter()->encryptString('ALEX')));
			}


        }
	}