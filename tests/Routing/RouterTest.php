<?php


namespace Testing\Routing {

    use Eywa\Testing\Unit;
    
    class RouterTest extends Unit
    {
        public function test_post()
        {
         $this->assertTrue($this->visit(route('send'),POST)->run()->success());

        }
    }
}
