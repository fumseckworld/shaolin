<?php
	
	namespace Imperium\Testing
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use GuzzleHttp\Psr7\ServerRequest;
		use Imperium\Cache\Cache;
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Imperium\Routing\RouteResult;
		use Imperium\Writing\Write;
		use PHPUnit\Framework\TestCase;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		
		/**
		 * Class Unit
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Testing
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Unit extends TestCase
		{
			
			/**
			 *
			 * @param  string  $url
			 * @param  string  $method
			 *
			 * @throws Kedavra
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return RouteResult|RedirectResponse
			 */
			public function visit(string $url, string $method = GET)
			{
				
				return app()->router(new ServerRequest($method, $url))->search();
			}
			
			/**
			 *
			 * Get a collection instance
			 *
			 * @param  array  $data
			 *
			 * @return Collect
			 *
			 */
			public function collect(array $data = []) : Collect
			{
				
				return collect($data);
			}
			
			/**
			 *
			 * Get an instance of file
			 *
			 * @param  string  $filename
			 * @param  string  $mode
			 *
			 * @throws Kedavra
			 *
			 * @return File
			 *
			 */
			public function file(string $filename, string $mode = READ_FILE_MODE) : File
			{
				
				return new File($filename, $mode);
			}
			
			/**
			 * @param  string  $subject
			 * @param  string  $message
			 * @param  string  $author_email
			 * @param  string  $to
			 *
			 * @throws Kedavra
			 *
			 * @return Write
			 *
			 */
			public function write(string $subject, string $message, string $author_email, string $to) : Write
			{
				
				return new Write($subject, $message, $author_email, $to);
			}
			
			/**
			 *
			 * Get the cache instance
			 *
			 * @return Cache
			 *
			 */
			public function cache() : Cache
			{
				
				return new Cache();
			}
			
		}
	}