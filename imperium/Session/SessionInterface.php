<?php
	
	namespace Imperium\Session
	{
		
		use Imperium\Collection\Collect;
		
		/**
		 *
		 * Interface SessionInterface
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Session
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		interface SessionInterface
		{
			
			/**
			 * Get a session key
			 *
			 * @param $key
			 *
			 * @return mixed
			 *
			 */
			public function get($key);
			
			/**
			 *
			 * Check if the session has a key
			 *
			 * @param $key
			 *
			 * @return bool
			 *
			 */
			public function has($key) : bool;
			
			/**
			 *
			 * Define a value
			 *
			 * @param $key
			 * @param $value
			 *
			 * @return  void
			 *
			 */
			public function put($key, $value) : void;
			
			/**
			 *
			 * Set and return value
			 *
			 * @param $key
			 * @param $value
			 *
			 * @return mixed
			 *
			 */
			public function def($key, $value);
			
			/**
			 *
			 * Remove a key
			 *
			 * @param  array  $keys
			 *
			 * @return bool
			 *
			 */
			public function remove(...$keys) : bool;
			
			/**
			 *
			 * Get all value
			 *
			 * @return array
			 *
			 */
			public function all() : array;
			
			/**
			 * SessionInterface constructor.
			 */
			public function __construct();
			
			/**
			 *
			 * Clear the session
			 *
			 * @return bool
			 *
			 */
			public function clear() : bool;
			
		}
	}