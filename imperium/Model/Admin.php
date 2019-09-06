<?php
	
	namespace Imperium\Model;
	
	use Imperium\Exception\Kedavra;
	
	class Admin extends Model
	{
		
		protected $table  = "routes";

		protected $admin = true;

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