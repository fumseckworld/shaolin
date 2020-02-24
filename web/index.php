<?php

use Eywa\Html\Form\Form;
use Eywa\Html\Form\FormBuilder;
use Eywa\Http\Request\Request;
use Eywa\Validate\Validator;

require '../vendor/autoload.php';
whoops();
return app()->run();
class A  extends Form {
    /**
     *
     * The form method
     *
     */
    public static string $method = POST;

    /**
     *
     * The form route
     *
     */
    public static string $route = 'root';

    public static array $rules =
    [
        'email' => 'email|required',
        'name' => 'required|unique:auth',
        'age'     => 'numeric|between:0,100'
    ];
}

$request = new Request(['email'=> 'lemicdefeu@gmail.com','name'=>'a','age' => 1]);

$validator = new Validator(A::$rules,$request);

d($validator->capture()->valid());


echo  (new FormBuilder(new A()))->add('email','email','email','must be a valid email')->add('username','textarea','name')->get();
