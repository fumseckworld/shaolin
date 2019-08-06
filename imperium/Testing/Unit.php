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
		use PHPUnit\Framework\TestCase;
		use Symfony\Component\HttpFoundation\RedirectResponse;

		/**
		 * Class Unit
		 *
		 * @package Imperium\Testing
		 *
		 * @author Willy Micieli
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
             * @param string $url
             * @param string $method
             *
             * @return RouteResult|RedirectResponse
             * @throws Kedavra
             * @throws DependencyException
             * @throws NotFoundException
             */

			public function visit(string $url, string $method = GET)
			{
				return app()->router(new ServerRequest($method, $url))->search();
			}

			/**
			 *
			 * Get a collection instance
			 *
			 * @param array $data
			 *
			 * @return Collect
			 *
			 */
			public function collect(array $data = []): Collect
			{
				return collect($data);
			}

			/**
			 *
			 * Get an instance of file
			 *
			 * @param string $filename
			 * @param string $mode
			 *
			 * @throws Kedavra
			 *
			 * @return File
			 *
			 */
			public function file(string $filename, string $mode = READ_FILE_MODE): File
			{
				return new File($filename, $mode);
			}

			/**
			 *
			 * Get the cache instance
			 *
			 * @return Cache
			 *
			 */
			public function cache(): Cache
			{
				return new Cache();
			}
		}
	}