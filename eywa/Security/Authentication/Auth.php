<?php
	declare(strict_types=1);

	namespace Eywa\Security\Authentication
	{

        use App\Models\User;
        use Eywa\Collection\Collect;
        use Eywa\Exception\Kedavra;
        use Eywa\Http\Response\RedirectResponse;
        use Eywa\Http\Response\Response;
        use Eywa\Message\Flash\Flash;
        use Eywa\Session\SessionInterface;
        use stdClass;

        /**
         *
         * Class Auth
         *
         * @package Eywa\Security\Authentication
         *
         */
		class Auth implements AuthInterface
		{

            /**
             *
             * The session
             *
             */
            private SessionInterface $session;

            /**
             * @inheritDoc
             */
            public function __construct(SessionInterface $session)
            {
                $this->session = $session;
            }

            /**
             * @inheritDoc
             */
            public function connected(): bool
            {
                return $this->session->has('user');
            }

            /**
             * @inheritDoc
             */
            public function is(string $role): bool
            {
                switch ($role)
                {
                    case 'admin':
                        return $this->current()->id === 1;
                    case 'redac':
                        return in_array($this->current()->id,[1,2],true);
                    default:
                        return false;
                }
            }

            /**
             * @inheritDoc
             */
            public function clean(): bool
            {
                return $this->session->destroy(['user']);
            }

            /**
             * @inheritDoc
             */
            public function current(): stdClass
            {
               return unserialize($this->session->get('user'));
            }

            /**
             * @inheritDoc
             */
            public function login(string $username, string $password): Response
            {

                $user = User::by('username',$username);

                if(def($user))
                {
                    if(check($password, $user->pwd))
                    {
                        $this->session->set('user',serialize($user));
                        return $this->redirect('/home',$this->messages()->get('welcome'),true);
                    }
                    else
                    {
                        $this->clean();

                        return $this->redirect('/login',$this->messages()->get('password_no_match'));
                    }
                }

                return $this->redirect('/login',$this->messages()->get('not_exist'));
            }

            /**
             * @inheritDoc
             */
            public function logout(): Response
            {
                $this->clean();

                return $this->redirect('/',$this->messages()->get('bye'),true);
            }

            /**
             * @inheritDoc
             */
            public function delete_account(): Response
            {
                if(User::destroy(intval($this->current()->id)))
                    return $this->redirect('/',$this->messages()->get('account_removed_successfully'),true);

                return $this->redirect('/login',$this->messages()->get('account_removed_fail'));
            }

            /**
             *
             * Get all messages
             *
             * @return Collect
             *
             * @throws Kedavra
             *
             */
            private function messages(): Collect
            {
                return collect(config('auth','messages'));
            }

            /**
             *
             * Get all messages
             *
             * @param string $url
             * @param string $message
             * @param bool $success
             * @return Response
             *
             * @throws Kedavra
             */
            private function redirect(string $url,string $message,bool $success = false): Response
            {
                $success ?  (new Flash())->set(SUCCESS,$message) : (new Flash())->set(FAILURE,$message);

                return  (new RedirectResponse($url))->send();
            }
        }
	}