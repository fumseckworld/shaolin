<?php


namespace App\Forms\Write;

use Eywa\Html\Form\Form;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;

class ContactForm extends Form
{
    protected static string $method = 'POST';

    protected static string $route = '';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [
    
    ];

    public static string $redirect_url = '/error';

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
