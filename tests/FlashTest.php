<?php
	
	namespace Testing;
	
	use PHPUnit\Framework\TestCase;
	
	class FlashTest extends TestCase
	{
		public function test()
		{
			$x = app()->flash();
			$x->success('a');
			$this->assertNotEmpty($x->display('success'));
			$this->assertEmpty($x->display('success'));
			$x = app()->flash();
			$x->failure('a');
			$this->assertNotEmpty($x->display('failure'));
			$this->assertEmpty($x->display('failure'));
			
			$x = app()->flash();
			$x->success('a');
			$this->assertNotEmpty($x->get('success'));
			$this->assertEmpty($x->get('success'));
			$x = app()->flash();
			$x->failure('a');
			$this->assertNotEmpty($x->get('failure'));
			$this->assertEmpty($x->get('failure'));
		}
	}