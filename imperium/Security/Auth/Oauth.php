<?php

namespace Imperium\Security\Auth {


    use Exception;
    use Imperium\Session\SessionInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class Oauth
    {

        /**
         * @var SessionInterface
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


        public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }

        /**
         *
         * @return RedirectResponse
         *
         * @throws Exception
         *
         */
        public function logout(): RedirectResponse
        {
            $this->session->remove(self::CONNECTED);

            $this->session->remove(self::USERNAME);

            return to('/',collection(config('auth','messages'))->get('bye'));

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
            $table = config('auth','auth_table');

            if (app()->table_exist($table))
            {
                $user = app()->model()->from($table)->by(config('auth','column'), $username);

                superior($user, 1, true, collection(config('auth', 'messages'))->get('not_unique'));

                if (def($user))
                {
                    foreach ($user as $u)
                    {
                        if (check($password, $u->password))
                        {
                            $this->session->set(self::USERNAME, $username);

                            $this->session->set(self::CONNECTED, true);

                            return equal($u->id,1) ?  to(config('auth','admin_prefix'), collection(config('auth', 'messages'))->get('welcome')) : to(config('auth','user_home'), collection(config('auth', 'messages'))->get('welcome'))  ;
                        } else {

                            $this->session->remove(self::CONNECTED);
                            $this->session->remove(self::USERNAME);
                            return back(collection(config('auth', 'messages'))->get('password_no_match'), false);
                        }

                    }
                }

                $this->session->remove(self::CONNECTED);

                $this->session->remove(self::USERNAME);

                return back(collection(config('auth','messages'))->get('user_not_found'),false);

            }

            $this->session->remove(self::CONNECTED);

            $this->session->remove(self::USERNAME);

            return back(collection(config('auth','messages'))->get('table_not_found'),false);

        }
    }
}