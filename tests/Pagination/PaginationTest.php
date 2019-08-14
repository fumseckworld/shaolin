<?php
	
	namespace Testing\Pagination;
	
	use Imperium\Exception\Kedavra;
	use Imperium\Html\Pagination\Pagination;
	use PHPUnit\Framework\TestCase;
	
	class PaginationTest extends TestCase
	{
		
		/**
		 * @throws Kedavra
		 */
		public function test()
		{
			$this->assertNotEmpty((new Pagination(1,15,50))->paginate());
		}
	}