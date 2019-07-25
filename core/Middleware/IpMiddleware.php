<?php


namespace Shaolin\Middleware;


use Imperium\Exception\Kedavra;
use Imperium\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class IpMiddleware implements Middleware
{

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws Kedavra
     */
    public function __invoke(ServerRequestInterface $request)
    {
        is_true(collect(METHOD_SUPPORTED)->not_exist($request->getMethod()),true,'not supported');

    }
}