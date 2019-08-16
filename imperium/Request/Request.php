<?php
	
	namespace Imperium\Request
	{
		
		use Symfony\Component\HttpFoundation\FileBag;
		use Symfony\Component\HttpFoundation\ServerBag;
		
		/**
		 * Class Request
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Request
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Request
		{
			
			/**
			 * @var \Symfony\Component\HttpFoundation\Request
			 */
			private $request;
			
			public function __construct()
			{
				
				$this->request = request();
			}
			
			/**
			 * @return \Symfony\Component\HttpFoundation\Request
			 */
			public function request()
			{
				
				return $this->request;
			}
			
			/**
			 *
			 * Get all params
			 *
			 * @return array
			 *
			 */
			public function all() : array
			{
				
				return collect($this->request()->request->all())->shift()->shift()->all();
			}
			
			/**
			 *
			 * Get client ip
			 *
			 * @return string
			 *
			 */
			public function ip() : string
			{
				
				return $this->request()->server->get('REMOTE_ADDR');
			}
			
			/**
			 *
			 * Get a value
			 *
			 * @param $key
			 *
			 * @return mixed
			 *
			 */
			public function get($key)
			{
				
				return collect($this->all())->get(htmlspecialchars($key, ENT_QUOTES));
			}
			
			/**
			 * @return array
			 */
			public function server() : array
			{
				
				return $this->request()->server->all();
			}
			
			/**
			 *
			 * Get files
			 *
			 * @return FileBag
			 *
			 */
			public function files() : FileBag
			{
				
				return $this->request()->files;
			}
			
			/**
			 *
			 * @return ServerBag
			 */
			public function serve() : ServerBag
			{
				
				return $this->request()->server;
			}
			
		}
	}