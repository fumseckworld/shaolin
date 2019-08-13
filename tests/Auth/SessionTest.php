<?php
	
	namespace Testing\Auth;
	
	use PHPUnit\Framework\TestCase;
	
	class SessionTest extends TestCase
	{
		
		public function test_all()
		{
		
			$this->assertNotEmpty( app()->session()->def('alex','a'));
			$this->assertEmpty(app()->session()->all());
			$this->assertTrue(app()->session()->clear());
			$this->assertEmpty(app()->session()->all());
		}
	}