<?php

	namespace Imperium\Security\Auth
	{

		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\Html\Form\Form;
		use Imperium\Model\Model;
		use Imperium\Request\Request;
		use Imperium\Session\SessionInterface;
		use Imperium\Writing\Write;
		use Symfony\Component\HttpFoundation\RedirectResponse;

		/**
		 * Class Oauth
		 *
		 * @package Imperium\Security\Auth
		 *
		 * @author Willy Micieli
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
			 * @var Model
			 */
			private $model;

			/**
			 * @var string
			 */
			private $table;


			/**
			 *
			 * Oauth constructor.
			 *
			 *
			 * @param SessionInterface $session
			 * @param Model            $model
			 */
			public function __construct(SessionInterface $session, Model $model)
			{
				$this->session = $session;

				$this->model = $model;

			}

			/**
			 *
			 * Generate a form to update account
			 *
			 * @param string $route_name
			 * @param string $username_placeholder
			 * @param string $last_name_placeholder
			 * @param string $email_placeholder
			 * @param string $password_placeholder
			 * @param string $submit_text
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function account(string $route_name, string $username_placeholder, string $last_name_placeholder, string $email_placeholder, string $password_placeholder, string $submit_text): string
			{
				if ($this->connected())
				{
					$form = new Form();
					$form->start(route($route_name), POST);
					$user = $this->model->find($this->session->get(self::ID));
					$columns = $this->model->columns();
					foreach ($columns as $column)
					{
						if (!is_null($user->$column))
						{

							switch ($column)
							{
							case 'id':
								$form->hide()->input(Form::HIDDEN, $column, $column, '', '', '', $user->$column)->end_hide();
								break;
							case 'email':
								$form->input(Form::EMAIL, $column, $email_placeholder, '', '', '', $user->$column);
								break;
							case 'password':
								$form->input(Form::PASSWORD, $column, $password_placeholder, '', '', '', '', false);
								break;
							case 'created_at':
							case 'updated_at':
								$form->hide()->input(Form::HIDDEN, $column, $column, '', '', '', $user->$column)->end_hide();
								break;
							case 'username':
								$form->input(Form::TEXT, $column, $username_placeholder, '', '', '', $user->$column);
								break;
							case 'lastname':
								$form->input(Form::TEXT, $column, $last_name_placeholder, '', '', '', $user->$column);
								break;
							default:
								$form->input(Form::TEXT, $column, $column, '', '', '', $user->$column);
								break;
							}
						} else
						{
							$form->hide()->input(Form::HIDDEN, $column, $column)->end_hide();
						}
					}
					return $form->submit($submit_text)->get();

				}
				return '';
			}

			/**
			 *
			 * Send an email to reset password
			 *
			 * @param string $subject
			 * @param string $to
			 * @param string $message
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function send_reset_email(string $subject, string $to, string $message): bool
			{
				return Write::email($subject, $message, config('mail', 'from'), $to)->send();
			}

			/**
			 *
			 * Logout the user
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function logout(): RedirectResponse
			{
				$this->clean_session();

				return to('/', $this->messages()->get('bye'));
			}

			/**
			 *
			 * @throws Kedavra
			 *
			 * @return object|string
			 *
			 */
			public function current()
			{
				return $this->connected() ? $this->model->from('users')->find($this->session->get(self::ID)) : '';
			}

			/**
			 *
			 * Display the connected username
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function connected_username(): string
			{
				$x = $this->current();

				return is_object($x) ? $x->username : '';
			}

			/**
			 *
			 * Create the new user from a form
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function create(): RedirectResponse
			{
				$request = new Request();
				$password = $this->columns()->get('password');

				foreach ($this->model->from('users')->columns() as $column)
				{
					if (different($column, $this->columns()->get('confirm')))
					{
						if (not_def($request->get($column)))
						{
							$this->model->set($column, $column);
						} else
						{
							if (equal($column, $password))
								$this->model->set($column, bcrypt($request->get($column))); else
								$this->model->set($column, $request->get($column));
						}
					}
				}
				return $this->model->save() ? back($this->messages()->get('account_created_successfully')) : back($this->messages()->get('account_creation_fail'), false);
			}

			/**
			 *
			 * Check if an user is connected
			 *
			 * @return bool
			 *
			 */
			public function connected(): bool
			{
				return $this->session->has(self::CONNECTED) && $this->session->has(self::ID) && $this->session->has(self::USERNAME) && $this->session->get(self::CONNECTED) === true;
			}

			/**
			 *
			 * Count users found
			 *
			 * @throws Kedavra
			 *
			 * @return int
			 *
			 */
			public function count(): int
			{
				return $this->model->count($this->table);
			}

			/**
			 *
			 *
			 * @param string $expected
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public function find(string $expected)
			{
				return $this->model->by($this->column(), $expected);
			}

			/**
			 *
			 * Reset the user password
			 *
			 * @param string $expected
			 * @param string $new_password
			 *
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function reset(string $expected, string $new_password): RedirectResponse
			{

				$id = $this->columns()->get('id');

				$password = $this->columns()->get('password');

				$user = $this->model->by($this->column(), $expected);

				$data = collect();

				if (def($user))
				{
					foreach ($user as $u)
					{
						$data->merge(collect($u)->all());

						$data->refresh($u->$password, bcrypt($new_password));

						return $this->model->update_record($u->$id, $data->all()) ? $this->redirect() : to('/', $this->messages()->get('reset_fail'));
					}
				}
				return $this->user_not_found();
			}

			/**
			 *
			 * Connect an user on success
			 *
			 * @throws Kedavra
			 * @return RedirectResponse
			 *
			 */
			public function login(): RedirectResponse
			{
				$request = new Request();

				$user = $this->model->from('users')->by($this->column(), $request->get($this->column()));

				$password_column = $this->columns()->get('password');

				if (def($user))
				{
					if (check($request->get($password_column), $user->$password_column))
					{
						$this->session->put(self::USERNAME, $request->get($this->column()));

						$this->session->put(self::CONNECTED, true);
						$this->session->put(self::ID, $user->id);

						return $this->redirect();
					} else
					{
						$this->clean_session();

						return back($this->messages()->get('password_no_match'), false);
					}

				}
				return $this->user_not_found();

			}

			/**
			 * @throws Kedavra
			 * @return RedirectResponse
			 */
			private function user_not_found(): RedirectResponse
			{
				$this->clean_session();

				return back($this->messages()->get('user_not_found'), false);
			}

			/**
			 *
			 * Remove auth information
			 *
			 */
			private function clean_session(): void
			{
				$this->session->remove(self::CONNECTED, self::USERNAME, self::ID);
			}

			/**
			 *
			 * Get the auth column name
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			private function column(): string
			{
				return $this->columns()->get('auth');
			}

			/**
			 *
			 * Redirect user
			 *
			 * @throws Kedavra
			 * @return RedirectResponse
			 *
			 */
			public function redirect(): RedirectResponse
			{
				return to('/home', $this->messages()->get('welcome'));
			}

			/**
			 *
			 * Remove user account
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function remove_account()
			{
				if ($this->connected())
				{
					is_false($this->model->remove($this->session->get(self::ID)), true, 'Failed to remove user');

					$this->clean_session();

					return to('/', $this->messages()->get('account_deleted'));
				}
				return to('/');
			}


			/**
			 *
			 * Remove an user on success
			 *
			 * @param int $id
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function remove_user(int $id)
			{
				if ($this->connected())
				{
					return $this->model->remove($id) ? back($this->messages()->get('user_removed')) : back($this->messages()->get('user_remove_failure'));
				}
				return to('/');
			}

			/**
			 *
			 * Update the user account
			 *
			 * @param array $data
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function update_account(array $data): RedirectResponse
			{
				if ($this->connected())
				{
					return $this->model->update_record($this->session->get(self::ID), $data, [\request()->request->get(CSRF_TOKEN)]) ? back($this->messages()->get('account_updated_successfully')) : back($this->messages()->get('account_updated_failure'), false);

				}
				return to('/');
			}

			/**
			 * @throws Kedavra
			 *
			 * @return Collect
			 *
			 */
			private function messages(): Collect
			{
				return collect(config('auth', 'messages'));
			}

			/**
			 * @throws Kedavra
			 *
			 * @return Collect
			 *
			 */
			private function columns(): Collect
			{
				return collect(config('auth', 'columns'));
			}
		}
	}