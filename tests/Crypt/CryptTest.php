<?php
	
	namespace Testing\Crypt
	{
		
		use Imperium\Encrypt\Crypt;
		use Imperium\Testing\Unit;
		
		class CryptTest extends Unit
		{
			
			public function test_generate_key()
			{
				$this->assertNotEmpty(Crypt::generateKey());
			}
			
			
			public function test_encrypt_and_decryt()
			{
				$this->assertNotEmpty($this->crypt()->encrypt('ALEX'));
				$this->assertNotEmpty($this->crypt()->encryptString('ALEX'));
				$this->assertEquals('ALEX',$this->crypt()->decrypt($this->crypt()->encrypt('ALEX')));
				$this->assertEquals('ALEX',$this->crypt()->decryptString($this->crypt()->encryptString('ALEX')));
			}
		}
	}