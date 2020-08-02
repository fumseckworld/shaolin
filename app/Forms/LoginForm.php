<?php

namespace App\Forms;

use Imperium\Html\Form\Form;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Response\Response;

class LoginForm extends Form
{
    protected static array $fields = [
        'email' => 'type:email | label:email | required | email | max:255 | min:2',
        'password' => 'type:password | label:%s | required | max:255 | min:8'
    ];
    
    protected static string $action = '/';
    
    protected static string $redirect = "/";
    
    final public function success(Bag $bag): Response
    {
        return (new Response())->setContent('ok')->send();
    }
}
