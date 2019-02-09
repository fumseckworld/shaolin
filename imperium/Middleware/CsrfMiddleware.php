<?php


namespace Imperium\Middleware {


    use Exception;
    use Imperium\Session\Session;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class CsrfMiddleware extends Session implements Middleware
    {
        /**
         *
         * The form key
         *
         * @var string
         *
         */
        const FORM_KEY = '_token';

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
         * Session key
         *
         * @var string
         *
         */
        const SESSION_KEY = 'tokens';

        /**
         *
         * Limit of token
         *
         * @var int
         *
         */
        const LIMIT = 10;
        /**
         * @var \Imperium\Collection\Collection
         */
        private $tokens;

        public function __construct()
        {
            $this->tokens = collection();
        }

        /**
         * Process an incoming server request.
         *
         * Processes an incoming server request in order to produce a response.
         * If unable to produce the response itself, it may delegate to the provided
         * request handler to do so.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         *
         * @throws Exception
         *
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            if (has($request->getMethod(),self::METHOD, true))
            {
                $params = $request->getParsedBody() ?: [];

                if (collection($params)->not_exist(self::FORM_KEY))
                    throw new Exception('Missing ');


                if (!\in_array($params[self::FORM_KEY], $this->get(self::SESSION_KEY) ?? [], true))
                    throw new Exception('');

                $this->removeToken($params[self::FORM_KEY]);
            }
            return $handler->handle($request);

        }

        /**
         * Generate and store a random token.
         *
         * @throws \Exception
         *
         * @return string
         */
        public function generateToken(): string
        {
            $token = bin2hex(random_bytes(16));

            $this->tokens->add($token);

            $this->set( $this->limitTokens($this->tokens->collection()),self::SESSION_KEY);

            return $token;
        }

        /**
         *
         * @return string
         *
         * @throws Exception
         */
        public function generate(): string
        {
           return '<input type="hidden" name="'.self::FORM_KEY.'" value="'.$this->generateToken().'"/>';
        }

        /**
         * @return CsrfMiddleware
         */
        public static function init()
        {
            return new static();
        }

        /**
         *
         * Limit the number of tokens.
         *
         * @param array $tokens
         *
         * @return array
         *
         * @throws Exception
         *
         */
        private function limitTokens(array $tokens): array
        {
            if (superior($tokens,self::LIMIT))
                array_shift($tokens);

            return $tokens;
        }

        /**
         * Remove a token from session.
         *
         * @param string $token
         */
        private function removeToken(string $token): void
        {
            $this->remove($token);
        }


    }
}