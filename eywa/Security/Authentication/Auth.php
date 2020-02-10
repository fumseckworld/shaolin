<?php
	
	namespace Eywa\Security\Authentication
	{

        use App\Models\User;
        use DI\DependencyException;
        use DI\NotFoundException;
        use Eywa\Collection\Collect;
        use Eywa\Exception\Kedavra;
        use Eywa\Http\Request\Request;
        use Eywa\Http\Response\RedirectResponse;
        use Eywa\Http\Response\Response;
        use Eywa\Message\Flash\Flash;
        use Eywa\Session\SessionInterface;


        /**
		 * Class Oauth
		 *
		 * @author  Willy Micieli
		 *
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Auth
		{
			
			/**
			 *
             * The session
             *
             */
			private SessionInterface $session;
			
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
			 * The username session id key
			 *
			 * @var string
			 *
			 */
			const ID = '__id__';
			
			/**
			 *
			 * Oauth constructor.
			 *
			 * @param  SessionInterface  $session
             *
			 */
			public function __construct(SessionInterface $session)
			{
				$this->session = $session;
			}

            /**
             *
             * Logout the user
             *
             * @return Response
             *
             * @throws Kedavra
             *
             */
			public function logout() : Response
			{
				$this->clean_session();
				
				return $this->to('/', $this->messages()->get('bye'));
			}

            /**
             *
             * Get the current user values
             *
             * @return array
             * @throws Kedavra
             * @throws DependencyException
             * @throws NotFoundException
             */
			public function current(): array
			{
				return $this->connected() ? User::find(intval($this->session->get(self::ID))) :  [];
			}
			
			/**
			 *
			 * Check if an user is connected
			 *
			 * @return bool
			 *
			 */
			public function connected() : bool
			{
				return $this->session->has(self::CONNECTED) && $this->session->has(self::ID) && $this->session->has(self::USERNAME) && $this->session->get(self::CONNECTED) === true;
			}

            /**
             *
             * Find by expected column
             *
             * @param string $expected
             *
             * @return array
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public function find(string $expected): array
			{
				return User::where($this->column(), EQUAL, $expected)->execute();
			}

            /**
             *
             * Connect an user on success
             *
             * @param string $password
             * @param $expected
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public function login(string $password, $expected) : Response
			{
				
				$request = Request::generate();
				$user = $this->find($expected);
				$password_column = $this->columns()->get('password');

				if(def($user))
				{

					if(check($password, $user[0]->$password_column))
					{
						$this->session->set(self::USERNAME, $request->request()->get($this->column(),'id'))->set(self::CONNECTED, true)->set(self::ID, $user[0]->id);
						
						return $this->redirect();
					}
					else
					{
						$this->clean_session();
						
						return $this->to($request->server()->get('HTTP_REFERER','/'),$this->messages()->get('password_no_match'), false);
					}
				}
				
				return $this->user_not_found();
			}

            /**
             *
             * @return Response
             *
             * @throws Kedavra
             *
             */
			private function user_not_found() : Response
			{
				
				$this->clean_session();
				
				return $this->to($this->back(),$this->messages()->get('user_not_found'), false);
			}
			
			/**
			 *
			 * Remove auth information
			 *
			 */
			private function clean_session() : bool
			{
			    return $this->session->destroy(self::CONNECTED, self::USERNAME, self::ID);
			}
			
			/**
			 *
			 * Get the auth column name
			 *
			 *
			 * @return string
			 *
			 */
			private function column() : string
			{
				return addslashes(User::key());
			}

            /**
             *
             * Redirect user
             *
             * @return Response
             *
             * @throws Kedavra
             *
             */
			public function redirect() : Response
			{
				return $this->to('/home', $this->messages()->get('welcome'));
			}

            /**
             *
             * Remove user account
             *
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public function remove_account(): Response
			{
				if($this->connected())
				{
					is_false(User::destroy(intval($this->session->get(self::ID))), true, 'Failed to remove user');
					$this->clean_session();
					
					return $this->to('/', $this->messages()->get('account_deleted'));
				}
				return $this->to('/');
			}
			
			/**
             *
			 * @throws Kedavra
			 *
			 * @return Collect
			 *
			 */
			private function messages() : Collect
			{
				return collect(config('auth', 'messages'));
			}
			
			/**
			 * @throws Kedavra
			 *
			 * @return Collect
			 *
			 */
			private function columns() : Collect
			{
				return collect(config('auth', 'columns'));
			}

            /**
             * @param string $url
             * @param string $message
             * @param bool $success
             *
             * @return Response
             *
             * @throws Kedavra
             *
             */
			private function to(string $url,string $message ='',bool $success = true): Response
            {
                if (def($message))
                    $success ?  (new Flash())->set(SUCCESS,$message) : (new Flash())->set(FAILURE,$message);

                return (new RedirectResponse($url))->send();
            }

            /**
             * @return mixed|string|null
             */
            private function back()
            {
                return Request::generate()->server()->get('HTTP_REFERER', '/');
            }
        }
	}