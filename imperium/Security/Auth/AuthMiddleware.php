<?php

	namespace Imperium\Security\Auth
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use GuzzleHttp\Psr7\Response;
		use Imperium\Exception\Kedavra;
		use Imperium\Middleware\Middleware;
		use Psr\Http\Message\ResponseInterface;
		use Psr\Http\Message\ServerRequestInterface;

		/**
		 * Class AuthMiddleware
		 *
		 * @package Imperium\Security\Auth
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class AuthMiddleware implements Middleware
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
			 * @return ResponseInterface
			 */
			public function handle(ServerRequestInterface $request) : ResponseInterface
			{	$admin = config('auth', 'admin_prefix');
				
				$home = config('auth', 'user_home');
				
				if (strpos($request->getUri()->getPath(), $admin) === 0 || strpos($request->getUri()->getPath(), $home))
				{
					if (is_false(app()->auth()->connected()))
						return back();
				}
				
				if (app()->auth()->connected())
				{
					if (strpos($request->getUri()->getPath(), '/login') === 0 || equal($request->getUri()->getPath(), "/register"))
					{
						return to($home);
					}
				}
				return  new Response();
			}
			
		}
	}
