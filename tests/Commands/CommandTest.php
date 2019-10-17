<?php
	
	namespace Testing\Commands;

	use Imperium\Exception\Kedavra;
    use Imperium\Model\Web;
    use Imperium\Testing\Unit;

    class CommandTest extends Unit
	{
        /**
         * @throws Kedavra
         */
		public function test_list()
		{
		    $this->assertNotEmpty(Web::all());
		    $this->assertNotEmpty(Web::find(1));
		    $this->assertNotEmpty(Web::where('name',EQUAL,'root')->all());
		    $this->assertNotEmpty(Web::where('url',EQUAL,'/')->all());
		    $this->assertNotEmpty(Web::where('controller',EQUAL,'HomeController')->all());
		    $this->assertNotEmpty(Web::where('action',EQUAL,'home')->all());
		    $this->assertNotEmpty(Web::where('method',EQUAL,'GET')->all());


		}
		
	
		
	}