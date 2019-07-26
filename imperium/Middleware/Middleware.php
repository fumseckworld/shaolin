<?php

	namespace Imperium\Middleware
	{


		use Psr\Http\Message\ServerRequestInterface;

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
		interface  Middleware
		{
			/**
			 * @param ServerRequestInterface $request
			 *
			 * @return mixed
			 */
			public function __invoke(ServerRequestInterface $request);
		}
	}