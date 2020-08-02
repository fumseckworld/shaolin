<?php

namespace App\Forms;

use Imperium\Http\Parameters\Bag;
use Imperium\Http\Response\Response;

class Form extends \Imperium\Html\Form\Form
{
    protected static array $fields = [
        'username' => 'type:text|label:your username|required|min:3|max:20',
        'age' => 'type:number|label:your %s|required|between:18,100'
    ];

    protected static string $redirect = '/login';

    final public function success(Bag $bag): Response
    {
        return (new Response())->setContent('ok')->send();
    }
}
