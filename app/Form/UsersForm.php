<?php


namespace App\Form;

use Eywa\Html\Form\Form;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;

class UsersForm extends Form
{
    protected static string $method = GET;

    protected static string $route = 'root';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
        'username' => 'required'
    ];

    public static string $redirect_url = '/error';

    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return $this->start()->add('username', 'text', 'your username', 'must be uniq', ['autofocus'=> 'autofocus'])->add('bio', 'textarea', 'your bio')->get();
    }


    /**
     * @inheritDoc
     */
    protected function valid(Request $request): Response
    {
        return new Response('valid');
    }

    /**
     * @inheritDoc
     */
    protected function invalid(Request $request, array $errors): Response
    {
        return new Response('invalid');
    }
}
