<?php

namespace Imperium\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class Auth implements Middleware
{


    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
       if(strpos($request->getUri()->getPath(),config('admin','prefix')) === 0)
       {
           if (!app()->auth()->connected() && different($request->getUri()->getPath(),'/login'))
               return to('/login');
       }
    }
}
