<?php

	namespace Imperium\Middleware
	{


		use Exception;
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
			 * @param ServerRequestInterface $request
			 *
			 * @throws Exception
			 * @return mixed
			 */
			public function __invoke(ServerRequestInterface $request)
			{
				$url = $request->getUri()->getPath();

				if (different($url, '/'))
				{
					$end = collect(explode('/', $url))->last();

					if (equal($end, '/'))
						return to(trim($url, '/'));
				}

			}
		}
	}