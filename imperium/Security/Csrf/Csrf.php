<?php

namespace Imperium\Security\Csrf {


    use Imperium\Exception\Kedavra;
    use Imperium\Security\Hashing\Hash;
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
        const METHOD = ['POST', 'PUT', 'DELETE'];

        /**
         *
         * The csrf token name
         *
         * @var string
         *
         */
        const KEY = CSRF_TOKEN;


        const SERVER = 'VALID_SERVER';


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
         * @throws Kedavra
         *
         */
        public function token()
        {
            return $this->session->has(self::KEY) ? $this->session->get(self::KEY) : $this->generate();
        }

        /**
         *
         * Set the token in the session
         *
         * @return string
         *
         * @throws Kedavra
         */
        private function generate():string
        {
            $this->session->set(self::SERVER,base64_encode((new Hash(request()->getHost()))->generate()));

            $token = base64_encode($this->session->get(self::SERVER)) . base64_encode(bin2hex(random_bytes(16)));

            $this->session->set(self::KEY,$token);

            return $token;

        }

        /**
         *
         * Check if the token is valid
         *
         * @param ServerRequestInterface $request
         *
         * @throws Kedavra
         *
         */
        public function check(ServerRequestInterface $request)
        {

            if (has($request->getMethod(),self::METHOD, true))
            {

                $params = $request->getParsedBody() ?: [];

                $token = collection($params)->get(self::KEY);

                is_true(not_def($token),true,'We have not found the csrf token');

                different($this->session->get(self::SERVER) ,base64_decode(collection(explode('==',$token))->get(0)),true,"The Server is not valid");

                is_true(different($token,$this->session->get(self::KEY),true,"The token is invalid"));

                $this->session->remove(self::KEY);
            }
        }
    }
}