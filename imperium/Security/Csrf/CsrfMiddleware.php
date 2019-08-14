<?php


	namespace Imperium\Security\Csrf
	{

		use Exception;
		use GuzzleHttp\Psr7\Response;
		use Imperium\Exception\Kedavra;
		use Imperium\Middleware\Middleware;
		use Psr\Http\Message\ResponseInterface;
		use Psr\Http\Message\ServerRequestInterface;

		/**
		 *
		 * Class CsrfMiddleware
		 *
		 * @author Willy Micieli
		 *
		 * @package Imperium\Security\Csrf
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class CsrfMiddleware implements Middleware
		{


			/**
			 * @var Csrf
			 */
			private $csrf;

			/**
			 * CsrfMiddleware constructor.
			 *
			 * @throws Exception
			 */
			public function __construct()
			{
				$this->csrf = new Csrf(app()->session());

			}
			
			/**
			 * Handles a request and produces a response.
			 *
			 * May call other collaborating code to generate the response.
			 *
			 * @param  ServerRequestInterface  $request
			 *
			 * @throws Kedavra
			 * @return ResponseInterface
			 */
			public function handle(ServerRequestInterface $request) : ResponseInterface
			{
				$this->csrf->check($request);
				return  new Response();
			}
			
		}
	}