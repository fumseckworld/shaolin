<?php

namespace Imperium\Security\Csrf {

    use Exception;
    use Imperium\Session\Session;
    use Imperium\Session\SessionInterface;
    use Psr\Http\Message\ServerRequestInterface;

    class Csrf
    {
        /**
         *
         * All method to secure
         *
         *
         * @var array
         *
         */
        const METHOD = ['DELETE', 'PATCH', 'POST', 'PUT'];

        /**
         *
         * The csrf token name
         *
         * @var string
         *
         */
        const KEY = 'csrf_token';


        /**
         * @var Session
         */
        private $session;

        /**
         *
         * Csrf constructor.
         *
         * @param SessionInterface $session
         *
         * @throws Exception
         *
         */
        public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }

        /**
         *
         * Return a token
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function token()
        {
            if ($this->session->has(self::KEY))
                return $this->session->get(self::KEY);

            $this->session->set(self::KEY,bin2hex(random_bytes(16)));

            return $this->session->get(self::KEY);
        }

        /**
         *
         * Check if the token is valid
         *
         * @param ServerRequestInterface $request
         *
         * @throws Exception
         *
         */
        public function check(ServerRequestInterface $request)
        {

            if (has($request->getMethod(),self::METHOD, true))
            {
                $params = $request->getParsedBody() ?: [];

                $token = collection($params)->get(self::KEY);

                is_true(not_def($token),true,'We have not found the csrf token');

                is_true(different($token,$this->session->get(self::KEY)),true,"The token is invalid");

            }
        }
    }
}