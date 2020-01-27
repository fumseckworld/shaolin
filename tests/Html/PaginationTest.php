<?php


namespace Testing\Html {


    use Eywa\Exception\Kedavra;
    use Eywa\Html\Pagination\Pagination;
    use PHPUnit\Framework\TestCase;

    class PaginationTest extends TestCase
    {
        /**
         * @throws Kedavra
         */
        public function test()
        {

            $this->assertNotEmpty((new Pagination(1,15,50))->paginate());
            $this->assertNotEmpty(pagination(1,15,50));
            $this->assertNotEmpty(pagination(1,0,50));
            $this->assertNotEmpty((new Pagination(1,0,50))->paginate());
            $this->assertEmpty(pagination('1',40,2));
        }

    }
}