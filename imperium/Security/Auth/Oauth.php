<?php

namespace Imperium\Security\Auth {


    use Exception;
    use Imperium\Session\Session;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class Oauth
    {

        /**
         * @var Session
         */
        private $session;

        /**
         *
         * The session connected key
         *
         * @var string
         *
         */
        const CONNECTED = '__connected__';

        /**
         *
         * The username session key
         *
         * @var string
         *
         */
        const USERNAME = '__username__';

        /**
         *
         * The url path
         *
         * @var string
         */
        private $path;

        public function __construct(Session $session)
        {
            $this->session = $session;
        }

        /**
         *
         * @return RedirectResponse
         *
         */
        public function logout(): RedirectResponse
        {
            $this->session->remove(self::CONNECTED);

            $this->session->remove(self::USERNAME);

            return to('/','bye');
        }

        /**
         *
         * Set the login redirect url
         *
         * @param string $path
         *
         * @return Oauth
         *
         */
        public function redirect_url(string $path): Oauth
        {
            $this->path = $path;

            return $this;
        }

        /**
         *
         * Check if an user is connected
         *
         *
         * @return bool
         *
         */
        public function connected(): bool
        {
            return $this->session->has(self::CONNECTED) && $this->session->has(self::USERNAME) && $this->session->get(self::CONNECTED) === true;
        }

        /**
         *
         * Connect an user on success
         *
         * @param string $username
         * @param string $password
         * @return RedirectResponse
         *
         * @throws Exception
         *
         */
        public function login(string $username,string $password): RedirectResponse
        {
            $user = app()->model()->from(config('auth','auth_table'))->by('username',$username);

            superior($user,1,true,'We have found multiple username identical');

            if (def($user))
            {
                foreach ($user as $u)
                {
                    if (check($password,$u->password))
                    {
                        $this->session->set($username,self::USERNAME);

                        $this->session->set(true,self::CONNECTED);

                        is_true(not_def($this->path),true,'Please set the redirect url');

                        return to($this->path,'welcome');
                    }else
                    {
                        $this->session->remove(self::CONNECTED);
                        $this->session->remove(self::USERNAME);
                        return back('Password not math',false);
                    }

                }
            }

            $this->session->remove(self::CONNECTED);

            $this->session->remove(self::USERNAME);

            return back('Not found user',false);
        }
    }
}