<?php

namespace Imperium\Security\Auth {


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
            $admin = config('auth','admin_prefix');
            $home   = config('auth','user_home');

            if (current_user()->get('id') !== "1" && is_admin())
                return to($home);

            if(strpos($request->getUri()->getPath(),$admin) === 0)
            {
                if (!app()->auth()->connected() && different($request->getUri()->getPath(),'/login'))
                    return to('/login');

                if (app()->auth()->connected() && equal($request->getUri()->getPath(),"/login"))
                    return to($admin);
            }

            if(strpos($request->getUri()->getPath(),$home) === 0)
            {
                if (!app()->auth()->connected() && different($request->getUri()->getPath(),'/login'))
                    return to('/login');

                if (app()->auth()->connected() && equal($request->getUri()->getPath(),"/login"))
                    return to($home);
            }

            if(strpos($request->getUri()->getPath(),'/login') === 0 && app()->auth()->connected())
            {
                return current_user()->get('id') === "1" ? to($admin) :  to($home);
            }
        }
    }
}
