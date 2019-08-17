<?php
	
	namespace Imperium\Config
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Symfony\Component\Yaml\Yaml;
		
		/**
		 *
		 * Class Config
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Config
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Config extends Yaml
		{
			/**
			 * @var string
			 *
			 */
			const EXT = '.yaml';
			
			/**
			 *
			 * The config filename
			 *
			 * @var string
			 *
			 */
			private $file;
			
			/**
			 *
			 * The config key
			 *
			 * @var mixed
			 *
			 */
			private $key;
			
			/**
			 * @var Collect
			 */
			private $values;
			
			/**
			 *
			 * Config constructor.
			 *
			 *
			 * @param  string  $file
			 * @param          $key
			 *
			 * @throws Kedavra
			 *
			 */
			public function __construct(string $file, $key)
			{
				$file = $this->path() . DIRECTORY_SEPARATOR . collect(explode('.', $file))->first() . self::EXT;
				is_false(File::exist($file), true, "The $file file  was not found at " . $this->path());
				$this->values = collect(self::parseFile($file));
				is_false($this->values->has($key), true, "The $key key was not found in the  $file at " . $this->path());
				$this->file = $file;
				$this->key = $key;
			}
			
			/**
			 * Get config path
			 *
			 * @method path
			 *
			 * @return string
			 *
			 */
			public function path() : string
			{
				
				if(def(request()->server->get('DOCUMENT_ROOT')))
					return dirname(request()->server->get('DOCUMENT_ROOT')) . DIRECTORY_SEPARATOR .'config';
				
				return request()->server->get('PWD') . DIRECTORY_SEPARATOR . 'config';
				
			}
			
			/**
			 *
			 * Get the config value
			 *
			 * @method value
			 *
			 * @return mixed
			 *
			 */
			public function value()
			{
				return $this->values->get($this->key);
			}
			
		}
	}