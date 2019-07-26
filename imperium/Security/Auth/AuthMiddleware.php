<?php

	namespace Imperium\Security\Auth
	{

		use Exception;
		use Imperium\Middleware\Middleware;
		use Psr\Http\Message\ServerRequestInterface;
		use Symfony\Component\HttpFoundation\RedirectResponse;

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
			 * @param ServerRequestInterface $request
			 *
			 * @throws Exception
			 * @return RedirectResponse
			 *
			 */
			public function __invoke(ServerRequestInterface $request)
			{
				$admin = config('auth', 'admin_prefix');

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
			}
		}
	}
