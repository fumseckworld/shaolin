<?php

namespace App\Forms;

use Nol\Http\Parameters\Bag;
use Nol\Http\Response\Response;

class Form extends \Nol\Html\Form\Form
{
    protected static array $fields = [
        'username' => 'type:text|label:your username|required|min:3|max:20',
        'age' => 'type:number|label:your %s|required|between:18,100'
    ];

    protected static string $redirect = '/login';

    public function success(Bag $bag): Response
    {
        return (new Response())->setContent('ok')->send();
    }
}
