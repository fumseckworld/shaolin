<?php

namespace App\Forms\Auth;

use Eywa\Database\Model\Model;
use Eywa\Html\Form\Form;
use Eywa\Http\Parameter\Bag;
use Eywa\Http\Response\Response;
use Eywa\Security\Authentication\Auth;
use Eywa\Session\Session;

class LoginForm extends Form
{
    protected static string $method = 'POST';

    protected static string $route = 'connexion';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
        'username' => 'required',
        'password' => 'required'
    ];

    public static string $redirect_error_url = '/login';

    public static string $redirect_success_url = '/home';


    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return $this->start()
            ->add('username', 'text', 'username')
            ->add('password', 'password', 'password')
            ->get('Login');
    }


    /**
     * @inheritDoc
     */
    public function success(Bag $bag): Response
    {
        return (new Auth(new Session(), new Model('auth')))
            ->login(
                $bag->get('username'),
                $bag->get('password')
            );
    }

    /**
     * @inheritDoc
     */
    public function error(array $messages): Response
    {
        return $this->redirect(static::$redirect_error_url, $messages, false);
    }
}
