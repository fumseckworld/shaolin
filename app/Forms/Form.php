<?php

namespace App\Forms;

use Imperium\Http\Parameters\Bag;
use Imperium\Http\Response\Response;

class Form extends \Imperium\Html\Form\Form
{
    protected static array $fields = [
        'username' => 'required|min:3|max:20|type:text|label:your username',
        'age' => 'required|max:100|min:18|type:number|label:your age'
    ];
    
    protected static string $redirect = '/login';
    
    protected function success(Bag $bag): Response
    {
        return (new Response())->setContent('ok')->send();
    }
}
