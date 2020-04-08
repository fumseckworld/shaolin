<?php

namespace App\Forms\Write;

use Eywa\Html\Form\Form;
use Eywa\Http\Parameter\Bag;
use Eywa\Http\Response\Response;

class ContactForm extends Form
{
    protected static string $method = 'POST';

    protected static string $route = 'send';

    protected static array $route_args = [];

    protected static array $options = [];

    public static string $redirect_success_url = '/contact';

    public static string $redirect_error_url = '/contact';

    protected static array $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required|min:20|max:200',
    ];

    /**
     * @inheritDoc
     */
    public function make(): string
    {
        return $this->start()
            ->row()
                ->add('name', 'text', 'type ypur name')
                ->add('email', 'email', 'type ypur email')
            ->end()
            ->row()
                ->add('message', 'textarea', 'Votre message')
            ->end()
        ->get('Send');
    }

    /**
     * @inheritDoc
     */
    public function success(Bag $bag): Response
    {
    }
}
