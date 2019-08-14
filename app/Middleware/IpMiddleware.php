<?php


namespace App\Middleware;


use GuzzleHttp\Psr7\Response;
use Imperium\Exception\Kedavra;
use Imperium\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IpMiddleware implements Middleware
{
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
		is_true(collect(METHOD_SUPPORTED)->not_exist($request->getMethod()),true,'not supported');
		
		return  new Response();
	}
	
}