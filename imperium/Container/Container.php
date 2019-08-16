<?php
	
	namespace Imperium\Container
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\App;
		use Imperium\Zen;
		
		/**
		 *
		 * Class Container
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Container
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Container
		{
			
			/**
			 *
			 * Instance
			 *
			 * @var App
			 *
			 */
			private static $instance;
			
			/**
			 *
			 * Get all class instance
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 *
			 * @return App
			 *
			 */
			public static function get() : App
			{
				
				if(is_null(self::$instance))
				{
					self::$instance = Zen::container()->get(App::class);
				}
				
				return self::$instance;
			}
			
		}
	}