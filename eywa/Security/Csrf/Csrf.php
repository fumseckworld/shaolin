<?php


namespace Eywa\Security\Csrf {


    use Exception;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Session\SessionInterface;

    class Csrf
    {
        /**
         * @var SessionInterface
         */
        private SessionInterface $session;

        public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }

        /**
         *
         * CHeck if the token exist
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function check(): bool
        {
            is_false($this->session->has(CSRF_TOKEN),true,'Csrf token was not found');

            is_true(different((new Crypter())->decrypt($this->session->get('server')),Request::generate()->server()->get('SERVER_NAME','eywa')),true,'Server is invalid');

            $this->session->destroy(CSRF_TOKEN);
            return true;
        }

        /**
         *
         * Remove the token
         *
         * @return bool
         *
         */
        public function remove_token(): bool
        {
            return $this->session->destroy(CSRF_TOKEN);
        }


        /**
         * @return string
         * @throws Kedavra
         * @throws Exception
         */
        public function token(): string
        {
            if ($this->session->has(CSRF_TOKEN))
                return '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $this->session->get(CSRF_TOKEN) . '">';

            $server = $this->session->has('server') ? $this->session->get('server') : $this->session->set('server', (new Crypter())->encrypt(Request::generate()->server()->get('SERVER_NAME', 'eywa')))->get('server');            $x = bin2hex(random_bytes(16));
            $csrf = $this->session->set('csrf', $x)->get('csrf');
            $token = "$server@$csrf";
            $this->session->set(CSRF_TOKEN, $token);

            return '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $token . '">';
        }
    }
}