<?php

namespace Imperium\Security\Auth {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Exception\Kedavra;
    use Imperium\Html\Form\Form;
    use Imperium\Model\Model;
    use Imperium\Request\Request;
    use Imperium\Security\Csrf\Csrf;
    use Imperium\Session\SessionInterface;
    use Imperium\Writing\Write;
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
         * @param SessionInterface $session
         *
         * @throws Kedavra
         *
         */
        public function __construct(SessionInterface $session)
        {
            $this->table = config('auth','table');

            is_true(app()->table_not_exist($this->table),true,"The {$this->table}'s table was not found on your system");

            $this->session = $session;

            $this->model = app()->model()->from($this->table);
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
         * @return string
         *
         * @throws Kedavra
         */
        public function account(string $route_name,string $username_placeholder,string $last_name_placeholder,string $email_placeholder,string $password_placeholder,string $submit_text): string
        {
            if($this->connected())
            {
                $form = new Form();
                $form->start(route($route_name),POST);
                $user = $this->model->find($this->session->get(self::ID));
                $columns = $this->model->columns();
                foreach ($columns as $column)
                {
                    if (!is_null($user->$column))
                    {

                       switch ($column)
                        {
                            case 'id':
                                $form->hide()->input(Form::HIDDEN,$column,$column,'','','',$user->$column)->end_hide();
                            break;
                            case 'email':
                                $form->input(Form::EMAIL,$column,$email_placeholder,'','','',$user->$column);
                            break;
                            case 'password':
                                $form->input(Form::PASSWORD,$column,$password_placeholder,'','','','',false);
                            break;
                            case 'created_at':
                            case 'updated_at':
                                $form->hide()->input(Form::HIDDEN,$column,$column,'','','',$user->$column)->end_hide();
                            break;
                           case 'username':
                               $form->input(Form::TEXT,$column,$username_placeholder,'','','',$user->$column);
                            break;
                           case 'lastname':
                               $form->input(Form::TEXT,$column,$last_name_placeholder,'','','',$user->$column);
                           break;
                            default:
                                $form->input(Form::TEXT,$column,$column,'','','',$user->$column);
                            break;
                        }
                    }else{
                        $form->hide()->input(Form::HIDDEN,$column,$column)->end_hide();
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
         * @return bool
         * 
         * @throws Kedavra
         * 
         */
        public function send_reset_email(string $subject,string $to,string $message): bool
        {
            return  Write::email($subject,$message,config('mail','from'),$to)->send();
        }

        /**
         *
         * Logout the user
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
         *
         */
        public function logout(): RedirectResponse
        {
            $this->clean_session();

            return to('/',$this->messages()->get('bye'));
        }


        /**
         *
         * Return the current logged user
         *
         * @return object
         *
         * @throws Kedavra
         *
         */
        public function current()
        {
            if ($this->connected())
            {
                return $this->model->find($this->session->get(self::ID));
            }
            throw new Exception('No user connected');
        }

        /**
         *
         * Create the new user from a form
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function create(): bool
        {
            
            $password = $this->columns()->get('password');

            foreach ($this->model->columns() as $column)
            {
                if (different($column,$this->columns()->get('confirm')))
                {
                    if (not_def(Request::get($column)))
                    {
                        $this->model->set($column,$column);
                    }else
                    {
                        if (equal($column,$password))
                            $this->model->set($column,bcrypt(Request::get($column)));
                        else
                            $this->model->set($column,Request::get($column));
                    }
                }
            }
            return $this->model->save();
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
         * @return int
         *
         * @throws Kedavra
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
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function find(string $expected): array
        {
            return $this->model->by($this->column(),$expected);
        }

        /**
         *
         * Reset the user password
         *
         * @param string $expected
         * @param string $new_password
         *
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
         *
         */
        public function reset(string $expected,string $new_password): RedirectResponse
        {

            $id  = $this->columns()->get('id');

            $password  = $this->columns()->get('password');

            $user =  $this->model->by($this->column(), $expected);

            $data = collection();

            if (def($user))
            {
                foreach ($user as $u)
                {
                    $data->merge(collection($u)->collection());

                    $data->change_value($u->$password,bcrypt($new_password));

                    return $this->model->update_record($u->$id,$data->collection()) ? $this->redirect($u->$id) : to('/',$this->messages()->get('reset_fail'));
                }
            }
            return $this->user_not_found();
        }
        
        /**
         *
         * Connect an user on success
         *
         * @param string $username
         * @param string $password
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
         *
         */
        public function login(string $username,string $password): RedirectResponse
        {
            $user = $this->model->by($this->column(), $username);

            $column = $this->columns()->get('password');

            superior($user, 1, true,$this->messages()->get('not_unique'));

            if (def($user))
            {
                foreach ($user as $u)
                {
                    if (check($password, $u->$column))
                    {
                        $this->session->set(self::USERNAME, $username);

                        $this->session->set(self::CONNECTED, true);
                        $this->session->set(self::ID,$u->id);

                        return $this->redirect($u->id);
                    } else 
                    {
                        $this->clean_session();

                        return back(collection(config('auth', 'messages'))->get('password_no_match'), false);
                    }

                }
            }
            return $this->user_not_found();
            
        }

        /**
         * @return RedirectResponse
         * @throws Kedavra
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
            $this->session->remove(self::CONNECTED);

            $this->session->remove(self::USERNAME);

            $this->session->remove(self::ID);
        }

        /**
         * 
         * Get the auth column name
         * 
         * @return string
         * 
         * @throws Kedavra
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
         * @param string $id
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
         */
        public function redirect(string $id): RedirectResponse
        {
            return equal($id,'1')  ?  to(config('auth','admin_prefix'), collection(config('auth', 'messages'))->get('welcome')) : to(config('auth','user_home'), collection(config('auth', 'messages'))->get('welcome'))  ;
        }

        /**
         *
         * Remove user account
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
         *
         */
        public function remove_account()
        {
            if ($this->connected())
            {
                is_false($this->model->remove($this->session->get(self::ID)),true,'Failed to remove user');

                $this->clean_session();

                return to('/',$this->messages()->get('account_deleted'));
            }
            return to('/');
        }


        /**
         *
         * Remove an user on success
         *
         * @param int $id
         *
         * @return RedirectResponse
         *
         * @throws Kedavra
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
         * @return RedirectResponse
         *
         * @throws Kedavra
         *
         */
        public function update_account(array $data): RedirectResponse
        {
            if ($this->connected())
            {
                return $this->model->update_record($this->session->get(self::ID),$data,[Request::get(Csrf::KEY)]) ? back($this->messages()->get('account_updated_successfully')) : back($this->messages()->get('account_updated_failure'),false);

            }
            return to('/');
        }

        /**
         * @return Collection
         *
         * @throws Kedavra
         *
         */
        private function messages(): Collection
        {
            return collection(config('auth', 'messages'));
        }

        /**
         * @return Collection
         *
         * @throws Kedavra
         *
         */
        private function columns(): Collection
        {
            return collection(config('auth','columns'));
        }
    }
}