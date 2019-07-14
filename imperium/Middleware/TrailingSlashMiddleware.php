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
            $url = $request->getUri()->getPath();

            if (different($url,'/'))
            {
                $end = collection(explode('/',$url))->last();

                if (equal($end,'/'))
                    return to(trim($url,'/'));
            }

        }
    }
}