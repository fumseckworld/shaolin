<?php
	
	namespace Imperium\Model;
	
	use Imperium\Exception\Kedavra;
	use Imperium\File\File;
	
	class Web extends Model
	{
		
		protected $table  = "routes";
		
		protected $routes = true;
		
		/**
		 *
		 * @throws Kedavra
		 *
		 * @return bool
		 *
		 */
		public static function generate(): bool
		{
			return static::query()->connexion()->execute(static::$create_route_table_query);
		}
		
		
		
	}