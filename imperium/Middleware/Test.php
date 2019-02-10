<?php

namespace Imperium\Middleware;



use Psr\Http\Message\ServerRequestInterface;

class Test implements Middleware
{

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $url = (string)$request->getUri();
        if (!empty($url) && $url[-1] === '/') {
            $response = new \GuzzleHttp\Psr7\Response();
            return $response
                ->withHeader('Location', substr($url, 0, -1))
                ->withStatus(301);
        }
    }
}