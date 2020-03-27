<?php


namespace App\Forms\Auth;

use Eywa\Html\Form\Form;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;

class LoginForm extends Form
{
    protected static string $method = 'POST';

    protected static string $route = '';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
    
    ];

    public static string $redirect_url = '/error';
    
    public static string $success_message = '';
    
    public static string $error_message = '';
    
    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return '';
    }


    /**
     * @inheritDoc
     */
    protected function valid(Request $request): Response
    {
        return new Response('');
    }

    /**
     * @inheritDoc
     */
    protected function invalid(Request $request, array $errors): Response
    {
        return new Response('');
    }
}
