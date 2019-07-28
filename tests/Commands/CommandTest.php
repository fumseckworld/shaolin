<?php
	
	namespace Testing\Commands;
	
	use Imperium\Routing\Route;
	use Imperium\Testing\Unit;
	
	class CommandTest extends Unit
	{
		public function test_list()
		{
			$this->assertNotEmpty(Route::manage()->names());
			$this->assertNotEmpty(Route::manage()->urls());
			$this->assertNotEmpty(Route::manage()->actions());
			$this->assertNotEmpty(Route::manage()->all());
			$this->assertNotEmpty(Route::manage()->find("root"));
			$this->assertNotEmpty(Route::manage()->find("GitController"));
			$this->assertNotEmpty(Route::manage()->find("1"));
		}
		
	
		
	}