<?php
	
	namespace Testing;
	
	use Imperium\Testing\Unit;
	
	class MailTest extends Unit
	{
		public function test()
		{
			$this->assertTrue($this->write('simple','i am simple message','git@git.fumseck.eu','micieli@gmail.com')->send());
			$this->assertTrue($this->write('simple','i am simple message','git@git.fumseck.eu','micieli@gmail.com')->add_bcc('git@git.fumseck.eu','git')->cc('micieli@gmail.com')->bcc('micieli@gmail.com')->add_to('micieli@gmail.com','micieli')->add_cc('micieli@gmail.com','github')->attach(base('web') .DIRECTORY_SEPARATOR . 'img','astronomy.jpg','image/jpg')->sign()->send());
			$this->assertTrue($this->write('simple','i am simple message','git@git.fumseck.eu','micieli@gmail.com')->add_bcc('git@git.fumseck.eu','git')->cc('micieli@gmail.com')->bcc('micieli@gmail.com')->add_to('micieli@gmail.com','micieli')->add_cc('micieli@gmail.com','github')->attach(base('web') .DIRECTORY_SEPARATOR . 'img','astronomy.jpg','image/jpg','inline')->sign()->send());
		}
	}