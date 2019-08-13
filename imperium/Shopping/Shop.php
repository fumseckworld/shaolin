<?php
	
	
	namespace Imperium\Shopping;
	
	
	use Imperium\Curl\Curl;
	
	class Shop
	{
		
		public function __construct()
		{
			$this->shop = Curl::init();
		}
	}