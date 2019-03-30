<?php

namespace Imperium\Middleware {


    use Psr\Http\Message\ServerRequestInterface;

    class TrailingSlashMiddleware implements Middleware
    {

        /**
         * @param ServerRequestInterface $request
         * @return mixed
         * @throws \Exception
         */
        public function __invoke(ServerRequestInterface $request)
        {
            $url = (string)$request->getUri();

            if (different($request->getUri()->getPath(),'/'))
            {
                if ($url[-1] === '/' )
                    return to(trim($url,'/'));
            }

        }
    }
}