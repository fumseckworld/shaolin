<?php


namespace Imperium\Security\Csrf {

    use Exception;
    use Imperium\Middleware\Middleware;
    use Psr\Http\Message\ServerRequestInterface;

    class CsrfMiddleware implements Middleware
    {


        /**
         * @var Csrf
         */
        private $csrf;

        /**
         * CsrfMiddleware constructor.
         * @throws Exception
         */
        public function __construct()
        {
           $this->csrf = new Csrf(app()->session());

        }

        /**
         * @param ServerRequestInterface $request
         * @return mixed
         * @throws Exception
         */
        public function __invoke(ServerRequestInterface $request)
        {
            $this->csrf->check($request);

        }
    }
}