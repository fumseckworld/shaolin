<?php

namespace Imperium\Middleware;


use Imperium\Auth\Oauth;
use Psr\Http\Message\ServerRequestInterface;

class Auth implements Middleware
{

    public function auth()
    {
        return new Oauth(app()->session());
    }
    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
       if(strpos($request->getUri()->getPath(),config('admin','prefix')) === 0)
       {
           if (!$this->auth()->connected())
               return to('/login');
       }
    }
}
