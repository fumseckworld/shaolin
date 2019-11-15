<?php
	
	namespace Imperium\Testing
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use GuzzleHttp\Psr7\ServerRequest;
        use Imperium\App;
        use Imperium\Cache\Cache;
		use Imperium\Collection\Collect;
        use Imperium\Cookies\Cookies;
		use Imperium\Encrypt\Crypt;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Imperium\Routing\RouteResult;
        use Imperium\String\Text;
        use Imperium\Validator\Validator;
        use Imperium\Versioning\Git;
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
             * Get an instance of text
             *
             * @param string $text
             *
             * @return Text
             *
             */
			public function text(string $text)
            {
                return new Text($text);
            }

            /**
             *
             * Get app instance
             *
             * @return App
             *
             * @throws DependencyException
             * @throws NotFoundException
             *
             */
			public function app(): App
            {
                return  app();
            }

            /**
             *
             * Get an instance of cookies
             *
             * @return Cookies
             *
             */
			public function cookies(): Cookies
            {
                return new Cookies();
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
			 * Get an instance of validator
			 *
			 * @param  array  $data
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return Validator
			 *
			 */
			public function validate(array $data): Validator
            {
                return app()->validator($data);
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
			
			/**
			 *
			 * Get an instance of crypt
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 *
			 * @return Crypt
			 *
			 */
			public function crypt(): Crypt
			{
				return new Crypt();
			}

            /**
             *
             * Get an instance of git class
             *
             * @param string $repository
             * @param string $branch
             * @return Git
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public function git(string $repository,string $branch): Git
            {
                return  app()->git($repository,$branch);
            }
		}
	}