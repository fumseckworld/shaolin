<?php

namespace App\Forms;

use Eywa\Html\Form\Form;
use Eywa\Http\Parameter\Bag;
use Eywa\Http\Response\Response;

class UsersForm extends Form
{
    protected static string $method = GET;

    protected static string $route = 'root';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
        'username' => 'required|between:1,3|max:3'
    ];

    public static string $redirect_error_url = '/error';

    public static string $redirect_success_url = '/';

    public static string $success_message = '';

    public static string $error_message = '';


    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return $this->start()
            ->add('username', 'text', 'your username', 'must be uniq', ['autofocus' => 'autofocus'])
            ->add('bio', 'textarea', 'your bio')
            ->get();
    }


    /**
     * @inheritDoc
     */
    public function success(Bag $bag): Response
    {
        return $this->redirect(static::$redirect_success_url, [static::$success_message]);
    }

    /**
     * @inheritDoc
     */
    public function error(array $messages): Response
    {
        return $this->redirect(static::$redirect_error_url, $messages, false);
    }
}
