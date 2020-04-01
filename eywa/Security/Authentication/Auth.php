<?php

    declare(strict_types=1);

namespace Eywa\Security\Authentication
{

    use Eywa\Collection\Collect;
    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Message\Flash\Flash;
    use Eywa\Session\SessionInterface;
    use ReflectionClass;
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
         * @var Model
         */
        private static Model $model;

        /**
         *
         * The session
         *
         */
        private SessionInterface $session;

        /**
         * @inheritDoc
         */
        public function __construct(SessionInterface $session, string $model)
        {
            $this->session = $session;
            is_false(class_exists($model), true, 'The model not exist');

            $model = new ReflectionClass($model);
            static::$model = $model->getMethod('instance')->invoke($model->newInstance());
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
            if ($this->connected()) {
                switch ($role) {
                    case 'admin':
                        return $this->current()->id == '1';
                    case 'redac':
                        return in_array($this->current()->id, ['1','2'], true);
                    default:
                        return false;
                }
            }
            return false;
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
            return $this->session->has('user') ? unserialize($this->session->get('user')) : new stdClass();
        }

        /**
         * @inheritDoc
         */
        public function login(string $username, string $password): Response
        {
            try {
                $user = static::$model::by('username', $username);
            } catch (Kedavra $kedavra) {
                return $this->redirect('/login', alert([$this->messages()->get('user_not_found')]));
            }

            if (def($user)) {
                if (check($password, $user->password)) {
                    $this->session->set('user', serialize($user));
                    return $this->redirect('/home', alert([ $this->messages()->get('welcome')], true), true);
                } else {
                    $this->clean();

                    return $this->redirect('/login', alert([$this->messages()->get('password_no_match')]));
                }
            }

            return $this->redirect('/login', alert([$this->messages()->get('user_not_found')]));
        }

        /**
         * @inheritDoc
         */
        public function logout(): Response
        {
            $this->clean();

            return $this->redirect('/', $this->messages()->get('bye'), true);
        }

        /**
         * @inheritDoc
         */
        public function deleteAccount(): Response
        {
            if ($this->connected()) {
                if (static::$model::destroy(intval($this->current()->id))) {
                    return $this->redirect('/', $this->messages()->get('account_removed_successfully'), true);
                }
                return $this->redirect('/', $this->messages()->get('account_removed_fail'));
            }

            return $this->redirect('/', $this->messages()->get('not_connected'));
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
            return collect(config('auth', 'messages'));
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
        private function redirect(string $url, string $message, bool $success = false): Response
        {
            $success ?  (new Flash())->set(SUCCESS, $message) : (new Flash())->set(FAILURE, $message);

            return  (new RedirectResponse($url))->send();
        }
    }
}
