<?php

	namespace Imperium\Middleware
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use GuzzleHttp\Psr7\Response;
		use Imperium\Exception\Kedavra;
		use Psr\Http\Message\ResponseInterface;
		use Psr\Http\Message\ServerRequestInterface;

		/**
		 * Class TrailingSlashMiddleware
		 *
		 * @package Imperium\Middleware
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class TrailingSlashMiddleware implements Middleware
		{
			
			/**
			 * Handles a request and produces a response.
			 *
			 * May call other collaborating code to generate the response.
			 *
			 * @param  ServerRequestInterface  $request
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return ResponseInterface
			 */
			public function handle(ServerRequestInterface $request) : ResponseInterface
			{
				
				$url = $request->getUri()->getPath();
				
				if (different($url, '/'))
				{
					$end = collect(explode('/', $url))->last();
					
					if (equal($end, '/'))
						return to(trim($url, '/'));
				}
				return new Response();
			}
			
			
			
		}
	}