<?php

namespace App\Forms\Write;

use Eywa\Html\Form\Form;
use Eywa\Http\Parameter\Bag;
use Eywa\Http\Response\Response;

class ContactForm extends Form
{
    protected static string $method = 'POST';

    protected static string $route = '';

    protected static array $route_args = [];

    protected static array $options = [];

    protected static array $rules = [

    ];



    /**
     * @inheritDoc
     */
    public function make(): string
    {
        // TODO: Implement make() method.
    }

    /**
     * @inheritDoc
     */
    public function success(Bag $bag): Response
    {
        return new Response('send');
    }

    /**
     * @inheritDoc
     */
    public function error(array $messages): Response
    {
        return $this->redirect(static::$redirect_error_url, $messages, false);
    }
}
