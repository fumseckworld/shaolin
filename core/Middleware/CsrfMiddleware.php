<?php


namespace Shaolin\Middleware {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Middleware\Middleware;
    use Imperium\Security\Csrf\Csrf;
    use Imperium\Session\Session;
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