<?php
	
	namespace Imperium\Security\Auth
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use App\Models\Users;
		use Imperium\Request\Request;
		use Imperium\Session\SessionInterface;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		
		/**
		 * Class Oauth
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Security\Auth
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
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
			 *
			 * @param  SessionInterface  $session
			 */
			public function __construct(SessionInterface $session)
			{
				
				$this->session = $session;
			}
			
			/**
			 *
			 * Logout the user
			 *
			 * @throws Kedavra
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function logout() : RedirectResponse
			{
				
				$this->clean_session();
				
				return to('/', $this->messages()->get('bye'));
			}
			
			/**
			 *
			 *
			 * @throws Kedavra
			 *
			 * @return object|string
			 *
			 */
			public function current()
			{
				
				return $this->connected() ? Users::find(intval($this->session->get(self::ID))) : '';
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
			 * Count users found
			 *
			 *
			 * @throws Kedavra
			 *
			 * @return int
			 *
			 */
			public function count() : int
			{
				
				return Users::count();
			}
			
			/**
			 *
			 *
			 * @param  string  $expected
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public function find(string $expected)
			{
				
				return Users::where($this->column(), EQUAL, $expected)->fetch(true)->all();
			}
			
			/**
			 *
			 * Connect an user on success
			 *
			 * @param  string  $password
			 * @param          $user_value
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function login(string $password, $user_value) : RedirectResponse
			{
				
				$request = new Request();
				$user = $this->find($user_value);
				$password_column = $this->columns()->get('password');
				if(def($user))
				{
					if(check($password, $user->$password_column))
					{
						$this->session->put(self::USERNAME, $request->get($this->column()));
						$this->session->put(self::CONNECTED, true);
						$this->session->put(self::ID, $user->id);
						
						return $this->redirect();
					}
					else
					{
						$this->clean_session();
						
						return back($this->messages()->get('password_no_match'), false);
					}
				}
				
				return $this->user_not_found();
			}
			
			/**
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			private function user_not_found() : RedirectResponse
			{
				
				$this->clean_session();
				
				return back($this->messages()->get('user_not_found'), false);
			}
			
			/**
			 *
			 * Remove auth information
			 *
			 */
			private function clean_session() : void
			{
				
				$this->session->remove(self::CONNECTED, self::USERNAME, self::ID);
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
				
				return Users::key();
			}
			
			/**
			 *
			 * Redirect user
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function redirect() : RedirectResponse
			{
				
				return to('/home', $this->messages()->get('welcome'));
			}
			
			/**
			 *
			 * Remove user account
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function remove_account()
			{
				
				if($this->connected())
				{
					is_false(Users::destroy(intval($this->session->get(self::ID))), true, 'Failed to remove user');
					$this->clean_session();
					
					return to('/', $this->messages()->get('account_deleted'));
				}
				
				return to('/');
			}
			
			/**
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
			
		}
	}