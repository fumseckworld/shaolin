<?php


namespace App\Forms;

use Eywa\Html\Form\Form;

class UsersForm extends Form
{
    protected static string $method = GET;

    protected static string $route = 'root';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
        'username' => 'required|between:1,3|max:3'
    ];

    public static string $redirect_url = '/error';

    public static string $success_message = '';

    public static string $error_message = '';


    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return $this->start()->add('username', 'text', 'your username', 'must be uniq', ['autofocus'=> 'autofocus'])->add('bio', 'textarea', 'your bio')->get();
    }
}
