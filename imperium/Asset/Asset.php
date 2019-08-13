<?php
	
	namespace Imperium\Asset
	{
		
		use Symfony\Component\HttpFoundation\Request;
		
		/**
		 *
		 * Class Asset
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Asset
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Asset
		{
			/**
			 *
			 * The name of file
			 *
			 * @var string
			 *
			 */
			private $filename;
			
			/**
			 *
			 * The request
			 *
			 * @var Request
			 *
			 */
			private $request;
			
			/**
			 *
			 * Asset constructor.
			 *
			 * @param  string  $filename
			 *
			 */
			public function __construct(string $filename)
			{
				$this->filename = $filename;
				$this->request = request();
			}
			
			/**
			 *
			 * Generate a css link
			 *
			 * @method css
			 *
			 * @return string
			 *
			 */
			public function css() : string
			{
				$filename = collect(explode('.', $this->filename))->first();
				append($filename, '.css');
				return php_sapi_name() != 'cli' ? https() ? '<link href="https://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $filename . '"  rel="stylesheet" type="text/css">' : '<link href="http://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $filename . '"  rel="stylesheet" type="text/css">' : '<link href="/css/' . $filename . '"  rel="stylesheet" type="text/css">';
			}
			
			/**
			 *
			 * Generate a js link
			 *
			 * @method js
			 *
			 * @param  string  $type
			 *
			 * @return string
			 *
			 */
			public function js(string $type = '') : string
			{
				$type = def($type) ? 'type="' . $type . '"' : '';
				return php_sapi_name() != 'cli' ? https() ? '<script src="https://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $this->filename . '" ' . $type . '></script>' : '<script src="http://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $this->filename . '"  ' . $type . '></script>' : '<script src="/js' . DIRECTORY_SEPARATOR . $this->filename . '" ' . $type . '></script>';
			}
			
			/**
			 *
			 * Generate a image link
			 *
			 * @method img
			 *
			 * @param  string  $alt
			 *
			 * @return string
			 *
			 */
			public function img(string $alt) : string
			{
				return php_sapi_name() != 'cli' ? https() ? '<img src="https://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $this->filename . '" alt="' . $alt . '">' : '<img src="http://' . $this->request->getHost() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $this->filename . '" alt="' . $alt . '">' : '<img src="/img' . DIRECTORY_SEPARATOR . $this->filename . '" alt="' . $alt . '">';
			}
			
		}
	}