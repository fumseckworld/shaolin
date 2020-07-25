<?php

namespace App\Forms;

use Imperium\Html\Form\Form;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Response\Response;

class LoginForm extends Form
{
    protected static array $fields = [
        'email' => 'required|email|type:email|label:email',
        'password' => 'required|max:255|min:8|type:password|label:password'
    ];
    
    protected static string $action = '/';
    
    protected static string $redirect = "/";
    
    protected function success(Bag $bag): Response
    {
        return (new Response())->setContent('ok')->send();
    }
}
