<?php

namespace Shaolin\Middleware;


use Imperium\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware implements Middleware
{


    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $prefix = config('auth','admin_prefix');

       if(strpos($request->getUri()->getPath(),$prefix) === 0)
       {
           if (!app()->auth()->connected() && different($request->getUri()->getPath(),'/login'))
               return to('/login');

           if (app()->auth()->connected() && equal($request->getUri()->getPath(),"/login"))
               return to($prefix);
       }
    }
}
