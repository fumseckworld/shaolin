<?php

	namespace Imperium\Middleware
	{
		
		use Psr\Http\Message\ResponseInterface;
		use Psr\Http\Message\ServerRequestInterface;
		use Psr\Http\Server\RequestHandlerInterface;
		
		/**
		 * Interface Middleware
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
		interface  Middleware extends RequestHandlerInterface
		{
			
			/**
			 * Handles a request and produces a response.
			 *
			 * May call other collaborating code to generate the response.
			 *
			 * @param  ServerRequestInterface  $request
			 *
			 * @return ResponseInterface
			 */
			public function handle(ServerRequestInterface $request): ResponseInterface;
		}
	}