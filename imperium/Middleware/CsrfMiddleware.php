<?php


namespace Imperium\Middleware {

    use Exception;
    use Imperium\Session\Session;
    use Psr\Http\Message\ServerRequestInterface;

    class CsrfMiddleware extends Session implements Middleware
    {
        /**
         *
         * The form key
         *
         * @var string
         *
         */
        const KEY = '_token';

        /**
         *
         * The toke size
         *
         * @var int
         *
         */
        const SIZE = 30;

        /**
         *
         * All method to capture
         *
         * @var array
         *
         */
        const METHOD =  ['DELETE', 'PATCH', 'POST', 'PUT'];

        /**
         *
         * @param ServerRequestInterface $request
         *
         * @throws Exception
         */
        public function __invoke(ServerRequestInterface $request)
        {
            if (has($request->getMethod(),self::METHOD, true))
            {
                $params = $request->getParsedBody() ?: [];

                $token = collection($params)->get(self::KEY);

                is_true(not_def($token),true,'We have not found the csrf token');

                is_true(different($token,$this->get(self::KEY),true,"The token is invalid"));

                $this->removeToken();
            }

        }

        /**
         *
         * @return string
         *
         * @throws Exception
         */
        public function generate(): string
        {
            $this->set(bin2hex(random_bytes(self::SIZE)),self::KEY);

            return '<input type="hidden" name="'.self::KEY.'" value="'.$this->get(self::KEY).'"/>';
        }

        /**
         *
         * @return CsrfMiddleware
         *
         * @throws Exception
         */
        public static function init()
        {
            return new static();
        }



        /**
         * Remove a token from session.
         *
         */
        private function removeToken(): void
        {
            $this->remove(self::KEY);
        }


    }
}