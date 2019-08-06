<?php
	
	namespace Testing\Commands;

	use Imperium\Exception\Kedavra;
    use Imperium\Model\Routes;
    use Imperium\Testing\Unit;

    class CommandTest extends Unit
	{
        /**
         * @throws Kedavra
         */
		public function test_list()
		{
		    $this->assertNotEmpty(Routes::all());
		    $this->assertNotEmpty(Routes::find(1));
		    $this->assertNotEmpty(Routes::where('name',EQUAL,'root')->all());
		    $this->assertNotEmpty(Routes::where('url',EQUAL,'/')->all());
		    $this->assertNotEmpty(Routes::where('controller',EQUAL,'GitController')->all());
		    $this->assertNotEmpty(Routes::where('action',EQUAL,'repositories')->all());
		    $this->assertNotEmpty(Routes::where('method',EQUAL,'GET')->all());


		}
		
	
		
	}