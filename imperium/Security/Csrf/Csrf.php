<?php

namespace Imperium\Security\Csrf {

    use Exception;
    use Imperium\Session\Session;
    use Psr\Http\Message\ServerRequestInterface;

    class Csrf
    {

        /**
         *
         * The limit of tokens
         *
         * @var int
         *
         */
        const LIMIT = 50;

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
         * @var int
         */
        private $i;

        /**
         * @var \Imperium\Collection\Collection
         */
        private $tokens;


        /**
         *
         * Csrf constructor.
         *
         * @param Session $session
         *
         * @throws Exception
         *
         */
        public function __construct(Session $session)
        {
            $this->session = $session;

            $this->tokens = collection();

            $this->generate();
        }

        /**
         *
         * Return a token
         *
         * @return string
         *
         */
        public function token()
        {
            if ($this->i > self::LIMIT)
                $this->i = 0;

            $this->i++;

            return $this->tokens->get($this->i);
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

                is_true(!has($token,$this->session->all()),true,"The token is invalid");


                $this->clean();
            }
        }

        /**
         * @throws \Exception
         */
        private  function generate()
        {
            $this->clean();

            for ($i =0;different($i,self::LIMIT);$i++)
            {
                $token = bin2hex(random_bytes(16));

                $this->tokens->add($token,$i);
                $this->session->set($token,$token);
            }
        }

        /**
         * @throws Exception
         */
        private function clean()
        {
            foreach ($this->tokens->collection() as $k => $v)
            {
                $this->session->remove($this->tokens->get($k));
            }

        }

    }
}