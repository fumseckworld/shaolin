<?php
	
	namespace Imperium\Shopping;
	
	use Imperium\Curl\Curl;
	
	class Shop
	{
		
		/**
		 * @var Curl
		 */
		private $shop;
		
		public function __construct()
		{
			
			$this->shop = Curl::init();
		}
		
	}